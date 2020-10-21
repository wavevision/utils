<?php declare (strict_types = 1);

namespace Wavevision\Utils;

use Nette\InvalidArgumentException;
use Nette\IOException;
use Nette\Utils\Strings as NetteStrings;
use function count;
use function iconv;
use function lcfirst;
use function preg_match;
use function preg_replace;
use function rtrim;
use function str_replace;
use function stripos;
use function strlen;
use function strrchr;
use function strtolower;
use function strtr;
use function substr;
use function ucwords;

class Strings extends NetteStrings
{

	public static function autoUtf(string $s): string
	{
		if (preg_match(Encoding::UTF_PATTERN, $s)) {
			$output = $s;
		} elseif (preg_match(Encoding::WIN_PATTERN, $s)) {
			$output = self::convertEncoding($s, Encoding::WINDOWS_1250, Encoding::UTF);
		} else {
			$output = self::convertEncoding($s, Encoding::LATIN, Encoding::UTF);
		}
		return $output;
	}

	public static function camelCaseToDashCase(string $s): string
	{
		return strtolower(self::replace($s, '/([a-zA-Z])(?=[A-Z])/', '$1-'));
	}

	public static function camelCaseToSnakeCase(string $s): string
	{
		return self::replace(self::camelCaseToDashCase($s), '/-/', '_');
	}

	public static function contains(string $haystack, string $needle, bool $caseSensitive = true): bool
	{
		if ($caseSensitive === true) {
			return parent::contains($haystack, $needle);
		}
		return stripos($haystack, $needle) !== false;
	}

	public static function convertBytes(string $s): int
	{
		$matches = self::match(str_replace(' ', '', $s), '/([0-9]+)([a-z]{0,2})/i');
		if ($matches === null || count($matches) !== 3) {
			throw new InvalidArgumentException("Invalid size '$s'!");
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

	public static function convertEncoding(string $s, string $sourceEncoding, string $targetEncoding): string
	{
		$converted = @iconv($sourceEncoding, $targetEncoding, $s);
		if ($converted === false) {
			throw self::createEncodingIOException($sourceEncoding, $targetEncoding);
		}
		return $converted;
	}

	public static function dashCaseToCamelCase(string $s): string
	{
		return lcfirst(str_replace('-', '', ucwords($s, '-')));
	}

	public static function getClassName(string $class, bool $camelCase = false): string
	{
		$className = substr(strrchr($class, '\\') ?: $class, 1);
		return $camelCase ? lcfirst($className) : $className;
	}

	public static function getNamespace(string $class): string
	{
		return rtrim(str_replace(self::getClassName($class), '', $class), '\\');
	}

	public static function removeAccentedChars(string $s): string
	{
		return self::replace(
			self::convertEncoding(self::autoUtf($s), Encoding::UTF, 'ascii//TRANSLIT'),
			'/[^a-zA-z]/',
			''
		);
	}

	public static function removeBlankLines(string $s): string
	{
		return self::replace($s, "/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n");
	}

	public static function removeBOM(string $s): string
	{
		return self::replace($s, "#\xEF\xBB\xBF#", '');
	}

	public static function removeEmoji(string $s): string
	{
		foreach (Encoding::EMOJI_PATTERNS as $pattern) {
			$s = self::replace($s, $pattern, '');
		}
		return $s;
	}

	public static function removeWhitespace(string $s): string
	{
		return self::replace($s, '/\s+/', '');
	}

	public static function utf2win(string $s): string
	{
		$output = preg_replace(Encoding::UTF_2_WIN_PATTERN, '', $s);
		if ($output === null) {
			throw self::createEncodingIOException(Encoding::UTF, Encoding::WINDOWS_1250);
		}
		return strtr($output, Encoding::UTF_2_WIN_TABLE);
	}

	public static function win2utf(string $s): string
	{
		return self::convertEncoding($s, Encoding::WINDOWS_1250, Encoding::UTF);
	}

	private static function createEncodingIOException(string $source, string $target): IOException
	{
		return new IOException("Unable to convert '$source' to '$target' encoding.");
	}

}
