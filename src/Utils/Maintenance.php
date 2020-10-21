<?php declare(strict_types = 1);

namespace Wavevision\Utils;

use Nette\StaticClass;
use function is_file;
use function touch;
use function unlink;

class Maintenance
{

	use StaticClass;

	private const FILE = 'maintenance.lock';

	private static string $directory;

	public static function init(string $directory): void
	{
		self::$directory = $directory;
	}

	public static function enable(): void
	{
		touch(self::filePathName());
	}

	public static function isActive(): bool
	{
		return is_file(self::filePathName());
	}

	public static function disable(): void
	{
		unlink(self::filePathName());
	}

	private static function filePathName(): string
	{
		return Path::join(self::$directory, self::FILE);
	}

}
