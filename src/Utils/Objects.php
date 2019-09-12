<?php declare (strict_types = 1);

namespace Wavevision\Utils;

use Nette\SmartObject;

class Objects
{

	use SmartObject;

	/**
	 * @param object $object
	 * @param string $property
	 * @return mixed
	 */
	public static function get(object $object, string $property)
	{
		return $object->{self::name('get', $property)}();
	}

	/**
	 * @param object|null $object
	 * @param string $property
	 * @return mixed|null
	 */
	public static function getIfNotNull(?object $object, string $property)
	{
		return self::ifNotNull(
			$object,
			function (object $object) use ($property) {
				return self::get($object, $property);
			}
		);
	}

	public static function hasGetter(object $object, string $property): bool
	{
		return method_exists($object, self::name('get', $property));
	}

	public static function hasSetter(object $object, string $property): bool
	{
		return method_exists($object, self::name('set', $property));
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
	 * @param string $property
	 * @param mixed $value
	 * @return mixed
	 */
	public static function set(object $object, string $property, $value)
	{
		return $object->{self::name('set', $property)}($value);
	}

	private static function name(string $prefix, string $name): string
	{
		return $prefix . ucfirst($name);
	}
}
