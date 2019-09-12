<?php declare (strict_types = 1);

namespace Wavevision\Utils;

use Nette\SmartObject;

class Objects
{

	use SmartObject;

	/**
	 * @param object $object
	 * @param string $propertyName
	 * @return mixed
	 */
	public static function get(object $object, string $propertyName)
	{
		return $object->{self::name('get', $propertyName)}();
	}

	/**
	 * @param object|null $object
	 * @param string $propertyName
	 * @return mixed|null
	 */
	public static function getIfNotNull(?object $object, string $propertyName)
	{
		return self::ifNotNull(
			$object,
			function (object $object) use ($propertyName) {
				return self::get($object, $propertyName);
			}
		);
	}

	/**
	 * @param object|null $object
	 * @param callable $callable
	 * @return mixed|null
	 */
	public static function ifNotNull(?object $object, callable $callable)
	{
		return $object ? $callable($object) : null;
	}

	/**
	 * @param object $object
	 * @param string $propertyName
	 * @param mixed $value
	 * @return mixed
	 */
	public static function set(object $object, string $propertyName, $value)
	{
		return $object->{self::name('set', $propertyName)}($value);
	}

	private static function name(string $prefix, string $name): string
	{
		return $prefix . ucfirst($name);
	}
}
