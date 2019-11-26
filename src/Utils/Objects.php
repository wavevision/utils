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

	public static function getClassName(object $object): string
	{
		return Strings::getClassName(get_class($object));
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

	public static function getNamespace(object $object): string
	{
		return Strings::getNamespace(get_class($object));
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

	/**
	 * @param array<mixed> $keys
	 * @param array<mixed> $extra
	 * @return array<mixed>
	 */
	public static function toArray(object $object, array $keys, array $extra = []): array
	{
		$values = [];
		foreach ($keys as $name) {
			$values[$name] = self::get($object, $name);
		}
		foreach ($extra as $key => $value) {
			$values[$key] = is_callable($value) ? $value(self::get($object, $key)) : $value;
		}
		return $values;
	}

	/**
	 * @param array<mixed> $attributes
	 */
	public static function copyAttributes(object $source, object $destination, array $attributes): void
	{
		foreach ($attributes as $attribute) {
			Objects::set($destination, $attribute, Objects::get($source, $attribute));
		}
	}

	private static function name(string $prefix, string $name): string
	{
		return $prefix . ucfirst($name);
	}

}
