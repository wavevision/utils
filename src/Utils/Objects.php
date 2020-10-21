<?php declare (strict_types = 1);

namespace Wavevision\Utils;

use Nette\SmartObject;
use function get_class;
use function implode;
use function is_array;
use function is_callable;
use function is_object;
use function is_string;
use function method_exists;
use function ucfirst;

class Objects
{

	use SmartObject;

	/**
	 * @return mixed
	 */
	public static function get(object $object, string $property)
	{
		return $object->{self::name('get', $property)}();
	}

	/**
	 * @return mixed
	 */
	public static function getNested(object $object, string ...$properties)
	{
		foreach ($properties as $property) {
			$object = self::get($object, $property);
			if (!is_object($object)) {
				return $object;
			}
		}
		return $object;
	}

	public static function getClassName(object $object): string
	{
		return Strings::getClassName(get_class($object));
	}

	/**
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
	 * @return mixed|null
	 */
	public static function ifNotNull(?object $object, callable $callable)
	{
		return $object ? $callable($object) : null;
	}

	/**
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
		foreach ($keys as $key => $name) {
			if (is_array($name)) {
				$values[is_string($key) ? $key : implode('.', $name)] = self::getNested($object, ...$name);
			} else {
				$values[$name] = self::get($object, $name);
			}
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
