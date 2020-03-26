<?php declare (strict_types = 1);

namespace Wavevision\Utils;

use Nette\SmartObject;

class LongString
{

	use SmartObject;

	private string $glue;

	/**
	 * @var string[]
	 */
	private array $strings;

	public function __construct(string ...$strings)
	{
		$this->glue = ' ';
		$this->strings = $strings;
	}

	public function __toString(): string
	{
		return $this->string();
	}

	public static function create(string ...$strings): self
	{
		return new self(...$strings);
	}

	public function addString(string $string): self
	{
		$this->strings[] = $string;
		return $this;
	}

	public function addStrings(string ...$strings): self
	{
		Arrays::each($strings, [$this, 'addString']);
		return $this;
	}

	public function getGlue(): string
	{
		return $this->glue;
	}

	public function setGlue(string $glue): self
	{
		$this->glue = $glue;
		return $this;
	}

	/**
	 * @return string[]
	 */
	public function getStrings(): array
	{
		return $this->strings;
	}

	public function string(?string $glue = null): string
	{
		return implode($glue ?? $this->glue, $this->strings);
	}

}
