<?php declare (strict_types = 1);

namespace Wavevision\Utils;

use Nette\IOException;
use Nette\StaticClass;
use function ini_get;
use function min;
use function php_sapi_name;

class Server
{

	use StaticClass;

	public static function getIni(string $key): string
	{
		$result = ini_get($key);
		if ($result === false) {
			throw new IOException("Unable to get php.ini key '$key'.");
		}
		return $result;
	}

	public static function getMaxUploadSize(?int $custom = null, ?string $unit = null): int
	{
		$uploadMaxFileSize = Strings::convertBytes(self::getIni('upload_max_filesize'));
		$postMaxSize = Strings::convertBytes(self::getIni('post_max_size'));
		if ($custom !== null) {
			if ($unit !== null) {
				$custom = Strings::convertBytes($custom . $unit);
			}
			return min($uploadMaxFileSize, $postMaxSize, $custom);
		}
		return min($uploadMaxFileSize, $postMaxSize);
	}

	public static function isCLI(): bool
	{
		return php_sapi_name() === 'cli';
	}

}
