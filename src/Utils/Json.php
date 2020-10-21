<?php declare (strict_types = 1);

namespace Wavevision\Utils;

use Nette\StaticClass;
use Nette\Utils\Json as NetteJson;
use Nette\Utils\JsonException;
use function preg_replace_callback;
use function str_repeat;
use function strlen;

class Json
{

	use StaticClass;

	public const INDENT_JS = '  ';

	public const INDENT_PHP = '    ';

	/**
	 * @param mixed $value
	 * @throws JsonException
	 */
	public static function encodePretty($value, string $indent = self::INDENT_PHP): ?string
	{
		return preg_replace_callback(
			'/^ +/m',
			function ($m) use ($indent) {
				return str_repeat($indent, strlen($m[0]) / 4);
			},
			NetteJson::encode($value, NetteJson::PRETTY)
		);
	}

}
