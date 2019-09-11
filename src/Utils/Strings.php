<?php declare (strict_types = 1);

namespace Wavevision\Utils;

use Nette\InvalidArgumentException;
use Nette\IOException;
use Nette\Utils\Strings as NetteStrings;

class Strings extends NetteStrings
{

	public static function autoUtf(string $s): string
	{
		if (preg_match(Encoding::UTF_PATTERN, $s)) {
			$output = $s;
		} elseif (preg_match(Encoding::WIN_PATTERN, $s)) {
			$output = iconv(Encoding::WINDOWS_1250, Encoding::UTF, $s);
		} else {
			$output = iconv(Encoding::LATIN, Encoding::UTF, $s);
		}
		if ($output === false) {
			throw new IOException('Unsupported encoding!');
		}
		return $output;
	}

	public static function camelCaseToDashCase(string $s): string
	{
		return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $s) ?: $s);
	}

	/**
	 * @param array<string> $s
	 * @param string $glue
	 * @return string
	 */
	public static function concat(array $s, string $glue = ''): string
	{
		return implode($glue, $s);
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

	public static function dashCaseToCamelCase(string $string): string
	{
		return lcfirst(str_replace('-', '', ucwords($string, '-')));
	}

	public static function getClassNameFromNamespace(string $namespace, bool $camelCase = false): string
	{
		$className = substr(strrchr($namespace, '\\') ?: $namespace, 1);
		return $camelCase ? lcfirst($className) : $className;
	}

	public static function removeBlankLines(string $s): string
	{
		return preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $s) ?: $s;
	}

	public static function removeEmoji(string $s): string
	{
		foreach (Encoding::EMOJI_PATTERNS as $pattern) {
			$s = self::replace($s, $pattern, '');
		}
		return $s;
	}

	public static function trimBlankLines(string $s): string
	{
		return preg_replace('\A[ \t]*\r?\n|\r?\n[ \t]*\Z', '', $s) ?: $s;
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
		$output = iconv(Encoding::WINDOWS_1250, Encoding::UTF, $s);
		if ($output === false) {
			throw self::createEncodingIOException(Encoding::WINDOWS_1250, Encoding::UTF);
		}
		return $output;
	}

	private static function createEncodingIOException(string $source, string $target): IOException
	{
		return new IOException(sprintf('Could not convert %s to %s encoding!', $source, $target));
	}
}
