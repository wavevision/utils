<?php declare (strict_types = 1);

namespace Wavevision\Utils;

use Nette\Utils\Validators as NetteValidators;
use function checkdate;
use function implode;
use function preg_match;
use function str_repeat;
use function str_replace;
use function trim;

class Validators extends NetteValidators
{

	private const BUSINESS_PREFIXES = ['CZ', 'SK'];

	private const PHONE_PREFIXES = [
		'\+420',
		'\+421',
	];

	public static function isCzechBusinessNumber(string $businessNumber): bool
	{
		$ic = Strings::trim(Strings::upper(Strings::removeWhitespace($businessNumber)));
		foreach (self::BUSINESS_PREFIXES as $prefix) {
			$ic = str_replace($prefix, '', $ic);
		}
		if (!preg_match('/^\d{8}$/', $ic)) {
			return false;
		}
		$a = 0;
		for ($i = 0; $i < 7; $i++) {
			$a += (int)$ic[$i] * (8 - $i);
		}
		$a = $a % 11;
		if ($a === 0) {
			$c = 1;
		} elseif ($a === 10) {
			$c = 1;
		} elseif ($a === 1) {
			$c = 0;
		} else {
			$c = 11 - $a;
		}
		return (int)$ic[7] === $c;
	}

	public static function isCzechPersonalNumber(string $personalNumber): bool
	{
		if (!preg_match('#^\s*(\d\d)(\d\d)(\d\d)[ /]*(\d\d\d)(\d?)\s*$#', $personalNumber, $matches)) {
			return false;
		}
		[, $year, $month, $day, $ext, $c] = $matches;
		if ($c === '') {
			return $year < 54;
		}
		$mod = (implode('', [$year, $month, $day, $ext])) % 11;
		if ($mod === 10) {
			$mod = 0;
		}
		if ($mod !== (int)$c) {
			return false;
		}
		$year += $year < 54 ? 2000 : 1900;
		if ($month > 70 && $year > 2003) {
			$month -= 70;
		} elseif ($month > 50) {
			$month -= 50;
		} elseif ($month > 20 && $year > 2003) {
			$month -= 20;
		}
		return checkdate((int)$month, (int)$day, (int)$year);
	}

	public static function isCzechPhoneNumber(string $phoneNumber): bool
	{
		$prefixes = '(' . implode('|', self::PHONE_PREFIXES) . ')?';
		$pattern = str_repeat(' ?[0-9]{3}', 3);
		$match = preg_match(
			'/^' . $prefixes . $pattern . '$/',
			trim($phoneNumber)
		);
		return $match === 1;
	}

	public static function isRgbColor(string $color): bool
	{
		// phpcs:disable
		$pattern = '/^([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5])( *),( *)([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5])( *),( *)([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5])( *)$/';
		// phpcs:enable
		return preg_match($pattern, $color) === 1;
	}

}
