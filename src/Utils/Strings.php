<?php declare (strict_types=1);

namespace Wavevision\Utils;

use Nette\InvalidArgumentException;
use Nette\IOException;
use Nette\Utils\Strings as NetteStrings;

class Strings extends NetteStrings
{

	public static function autoUtf(string $s): string
	{
		set_error_handler([self::class, 'handleAutoUtfError']);
		if (preg_match(Encoding::UTF_PATTERN, $s)) {
			$output = $s;
		} elseif (preg_match(Encoding::WIN_PATTERN, $s)) {
			$output = iconv(Encoding::WINDOWS_1250, Encoding::UTF, $s);
		} else {
			$output = iconv(Encoding::LATIN, Encoding::UTF, $s);
		}
		restore_error_handler();
		return $output;
	}

	public static function concat(array $s, string $glue = ''): string
	{
		return implode($glue, $s);
	}

	public static function contains($haystack, $needle, bool $caseSensitive = true): bool
	{
		if ($caseSensitive === true) {
			return parent::contains($haystack, $needle);
		}
		return stripos($haystack, $needle) !== false;
	}

	public static function convertBytes(string $s): int
	{
		$matches = self::match(str_replace(' ', '', $s), '/([0-9]+)([a-z]{0,2})/i');
		if (count($matches) !== 3) {
			throw new InvalidArgumentException("Invalid size $s.");
		}
		[, $value, $unit] = $matches;
		if (strlen($unit) === 2) {
			$unit = substr($unit, 0, 1);
		}
		switch (strtolower($unit)) {
			case 'k':
				return (int)$value * 1024;
			case 'm':
				return (int)$value * (1024 ** 2);
			case 'g':
				return (int)$value * (1024 ** 3);
			default:
				return (int)$value;
		}
	}

	public static function removeBlankLines(string $s): string
	{
		return preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $s);
	}

	public static function trimBlankLines(string $s): string
	{
		return preg_replace('\A[ \t]*\r?\n|\r?\n[ \t]*\Z', '', $s);
	}

	public static function utf2win(string $s): string
	{
		return strtr(preg_replace(Encoding::UTF_2_WIN_PATTERN, '', $s), Encoding::UTF_2_WIN_TABLE);
	}

	public static function win2utf(string $s): string
	{
		return iconv(Encoding::WINDOWS_1250, Encoding::UTF, $s);
	}

	private static function handleAutoUtfError(): void
	{
		restore_error_handler();
		throw new IOException('Unsupported encoding!');
	}
}
