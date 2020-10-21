<?php declare(strict_types = 1);

namespace Wavevision\Utils;

use Nette\StaticClass;
use function count;
use function date;
use function sprintf;
use function str_replace;
use function strlen;

class SerialNumber
{

	use StaticClass;

	public const YEAR_REGEXP = '/(\%Y{2}\%|\%Y{4}\%)/';

	public const NUMBER_REGEXP = '/(\%X{1,}\%)/';

	public static function generate(string $mask, int $nextNumber): string
	{
		$yearPattern = self::pattern($mask, self::YEAR_REGEXP);
		$yearFormat = strlen($yearPattern) - 2 === 4 ? 'Y' : 'y';
		$year = date($yearFormat);
		$partialSerialNumber = str_replace($yearPattern, $year, $mask);
		$numberPattern = self::pattern($partialSerialNumber, self::NUMBER_REGEXP);
		$length = strlen($numberPattern) - 2;
		$number = sprintf('%0' . $length . 'd', $nextNumber);
		return str_replace($numberPattern, $number, $partialSerialNumber);
	}

	private static function pattern(string $mask, string $pattern): string
	{
		$matches = Strings::matchAll($mask, $pattern);
		if (count($matches) !== 1) {
			throw new SerialNumberInvalidMask(sprintf("Mask '%s' is invalid for pattern '%s'.", $mask, $pattern));
		}
		return $matches[0][1];
	}

}
