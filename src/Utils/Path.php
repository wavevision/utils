<?php declare (strict_types = 1);

namespace Wavevision\Utils;

use Nette\InvalidStateException;
use Nette\SmartObject;
use function array_map;
use function array_merge;
use function implode;
use function realpath;
use function rtrim;
use function sprintf;

class Path
{

	use SmartObject;

	public const DELIMITER = '/';

	/**
	 * @var array<string|null>
	 */
	private array $path;

	private function __construct(?string ...$path)
	{
		$this->path = $path;
	}

	public static function create(?string ...$path): self
	{
		return new self(...$path);
	}

	public static function join(?string ...$parts): string
	{
		return Strings::replace(
			Strings::replace(
				implode(self::DELIMITER, array_map([self::class, 'trim'], $parts)),
				['#\\\#', '#//+#'],
				self::DELIMITER
			),
			'#:/#',
			'://'
		);
	}

	public static function trim(?string $path): ?string
	{
		if ($path === null) {
			return null;
		}
		return rtrim($path, self::DELIMITER);
	}

	public static function realpath(string $path): string
	{
		$realpath = realpath($path);
		if ($realpath === false) {
			throw new InvalidStateException(
				sprintf("Unable to get real path for '%s'. Check if directory exists.", $path)
			);
		}
		return $realpath;
	}

	public function path(?string ...$path): self
	{
		return self::create(self::join(...array_merge($this->path, $path)));
	}

	public function string(?string ...$path): string
	{
		return (string)$this->path(...$path);
	}

	public function __toString(): string
	{
		return self::join(...$this->path);
	}

}
