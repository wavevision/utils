<?php declare (strict_types = 1);

namespace Wavevision\Utils;

use ArrayIterator;
use Iterator;
use Nette\Utils\Finder as NetteFinder;
use SplFileInfo;

class Finder extends NetteFinder
{

	public const CASE_INSENSITIVE = 'CI';

	public const CASE_SENSITIVE = 'CS';

	public const ORDER_ASC = 'ASC';

	public const ORDER_DESC = 'DESC';

	/**
	 * @var callable
	 */
	private $sort;

	public function getIterator(): Iterator
	{
		$iterator = parent::getIterator();
		if (!is_callable($this->sort)) {
			return $iterator;
		}
		$iterator = new ArrayIterator(iterator_to_array($iterator));
		$iterator->uasort($this->sort);
		return $iterator;
	}

	public function setSort(callable $sort): self
	{
		$this->sort = $sort;
		return $this;
	}

	public function sortByMTime(string $order = self::ORDER_DESC): self
	{
		$this->sort = function (SplFileInfo $f1, SplFileInfo $f2) use ($order): int {
			if ($order === self::ORDER_DESC) {
				return $f2->getMTime() - $f1->getMTime();
			}
			return $f1->getMTime() - $f2->getMTime();
		};
		return $this;
	}

	public function sortByName(string $order = self::ORDER_ASC, string $case = self::CASE_INSENSITIVE): self
	{
		$fn = $case === self::CASE_INSENSITIVE ? 'strcasecmp' : 'strcmp';
		$this->sort = function (SplFileInfo $f1, SplFileInfo $f2) use ($fn, $order): int {
			if ($order === self::ORDER_ASC) {
				return $fn(
					Strings::removeAccentedChars($f1->getFilename()),
					Strings::removeAccentedChars($f2->getFilename())
				);
			}
			return $fn(
				Strings::removeAccentedChars($f2->getFilename()),
				Strings::removeAccentedChars($f1->getFilename())
			);
		};
		return $this;
	}
}
