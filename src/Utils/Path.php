<?php declare (strict_types = 1);

namespace Wavevision\Utils;

use Nette\StaticClass;

class Path
{

	use StaticClass;

	public const DELIMITER = '/';

	public static function join(?string ...$parts): string
	{
		return Strings::replace(
			implode(self::DELIMITER, array_map([self::class, 'trim'], $parts)),
			['#\\\#', '#//+#'],
			self::DELIMITER
		);
	}

	public static function trim(?string $path): ?string
	{
		if ($path === null) {
			return null;
		}
		return rtrim($path, self::DELIMITER);
	}
}
