<?php declare (strict_types = 1);

namespace Wavevision\Utils;

use Nette\SmartObject;

class Path
{

	public const DELIMITER = '/';

	use SmartObject;

	/**
	 * @var array<string|null>
	 */
	private $path;

	/**
	 * @return static
	 */
	public static function create(?string ...$path)
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

	/**
	 * @return static
	 */
	public function path(?string ...$path)
	{
		return self::create(self::join(...array_merge($this->path, $path)));
	}

	public function __toString(): string
	{
		return self::join(...$this->path);
	}

	private function __construct(?string ...$path)
	{
		$this->path = $path;
	}

}
