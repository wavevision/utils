<?php declare (strict_types = 1);

namespace Wavevision\Utils;

use Flow\JSONPath\JSONPath;
use Flow\JSONPath\JSONPathException;
use Nette\InvalidArgumentException;
use Nette\InvalidStateException;
use Nette\Utils\ArrayHash;
use Nette\Utils\Arrays as NetteArrays;

class Arrays extends NetteArrays
{

	/**
	 * @param array<mixed> $a1
	 * @param array<mixed> $a2
	 * @return array<mixed>
	 */
	public static function appendAll(array $a1, array $a2): array
	{
		foreach ($a2 as $value) {
			$a1[] = $value;
		}
		return $a1;
	}

	/**
	 * @param array<int|string> $keys
	 * @param mixed $value
	 * @param array<mixed> $tree
	 * @return array<mixed>
	 */
	public static function buildTree(array $keys, $value, array &$tree = []): array
	{
		$currentKey = array_values($keys)[0];
		if (!isset($tree[$currentKey])) {
			$tree[$currentKey] = [];
		}
		$remainingKeys = array_slice($keys, 1);
		if (count($remainingKeys) > 0) {
			self::buildTree($remainingKeys, $value, $tree[$currentKey]);
		} else {
			$tree[$currentKey] = $value;
		}
		return $tree;
	}

	/**
	 * @param array<mixed> $source
	 * @param int|string ...$keys
	 * @return array<mixed>
	 */
	public static function copySelected(array $source, ...$keys): array
	{
		$copied = [];
		foreach ($keys as $key) {
			$copied[$key] = $source[$key];
		}
		return $copied;
	}

	/**
	 * @param iterable<mixed> $values
	 * @return int
	 */
	public static function countFilled(iterable $values): int
	{
		$count = 0;
		foreach ($values as $value) {
			if ($value === null || $value === '') {
				continue;
			}
			$count++;
		}
		return $count;
	}

	/**
	 * @param array<mixed> $a1
	 * @param array<mixed> $a2
	 * @param bool $showMissingKeys
	 * @return array<mixed>
	 */
	public static function diff(array $a1, array $a2, bool $showMissingKeys = true): array
	{
		$diff = [];
		foreach ($a1 as $key => $value) {
			if (array_key_exists($key, $a2)) {
				if (is_array($value)) {
					$recursiveDiff = self::diff($value, $a2[$key], $showMissingKeys);
					if (count($recursiveDiff)) {
						$diff[$key] = $recursiveDiff;
					}
				} else {
					if ($value && trim($value) !== trim($a2[$key])) {
						$diff[$key] = $value;
					}
				}
			} elseif ($showMissingKeys) {
				$diff[$key] = $value;
			}
		}
		return $diff;
	}

	/**
	 * @param iterable<object> $collection
	 * @return array<int|string>
	 */
	public static function extractObjectIds(iterable $collection): array
	{
		return self::extractObjectValues($collection, 'id');
	}

	/**
	 * @param iterable<object> $collection
	 * @param string $property
	 * @return array<mixed>
	 */
	public static function extractObjectValues(iterable $collection, string $property): array
	{
		return self::mapCollection(
			$collection,
			function (object $item) use ($property) {
				return $item->$property;
			}
		);
	}

	/**
	 * @param array<mixed> $array
	 * @param int|string $key
	 * @return array<mixed>
	 */
	public static function extractValues(array $array, $key): array
	{
		return array_map(
			function (array $item) use ($key) {
				return $item[$key];
			},
			$array
		);
	}

	/**
	 * @param iterable<mixed> $collection
	 * @return mixed|null
	 */
	public static function firstItem(iterable $collection)
	{
		return $collection[self::firstKey($collection)] ?? null;
	}

	/**
	 * @param iterable<mixed> $collection
	 * @return int|string|null
	 */
	public static function firstKey(iterable $collection)
	{
		$key = null;
		foreach (self::iterableKeys($collection) as $key) {
			break;
		}
		return $key;
	}

	/**
	 * @param array<mixed> $array
	 * @param string $prefix
	 * @param string $glue
	 * @return array<mixed>
	 */
	public static function flattenKeys(array $array, string $prefix = '', string $glue = '.'): array
	{
		$result = [];
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$result = $result + self::flattenKeys($value, $prefix . $key . $glue);
			} else {
				$result[$prefix . $key] = $value;
			}
		}
		return $result;
	}

	/**
	 * @param ArrayHash $arrayHash
	 * @return array<mixed>
	 */
	public static function fromArrayHash(ArrayHash $arrayHash): array
	{
		$array = [];
		foreach ($arrayHash as $key => $value) {
			if ($value instanceof ArrayHash) {
				$array[$key] = self::fromArrayHash($arrayHash[$key]);
			} else {
				$array[$key] = $value;
			}
		}
		return $array;
	}

	/**
	 * @param array<mixed> $array
	 * @param string ...$keyParts
	 * @return bool
	 */
	public static function hasNestedKey(array $array, string ...$keyParts): bool
	{
		if (count($keyParts) === 0) {
			throw new InvalidArgumentException('Argument "keyParts" should have at least one element.');
		}
		foreach ($keyParts as $keyPart) {
			if (key_exists($keyPart, $array)) {
				$array = $array[$keyPart];
				if (!is_array($array)) {
					break;
				}
			} else {
				return false;
			}
		}
		return true;
	}

	/**
	 * @param array<mixed> $a1
	 * @param array<mixed> $a2
	 * @return bool
	 */
	public static function hasSameValues(array $a1, array $a2): bool
	{
		return self::sortedValues($a1) === self::sortedValues($a2);
	}

	/**
	 * @param array<mixed> $array
	 * @param string $separator
	 * @param string|null $last
	 * @return string
	 */
	public static function implode(array $array, string $separator = ',', ?string $last = null): string
	{
		$s = '';
		$count = count($array);
		$i = 0;
		foreach ($array as $value) {
			$s .= $value;
			if ($i < $count - 2) {
				$s .= $separator;
			} elseif ($i < $count - 1) {
				$s .= $last ?: $separator;
			}
			$i++;
		}
		return $s;
	}

	/**
	 * @param array<mixed> $array
	 * @param int|string $index
	 * @return array<int|string, mixed>
	 */
	public static function indexByValue(array $array, $index): array
	{
		return self::mapWithKeys(
			$array,
			function ($key, $value) use ($index): array {
				if (is_array($value)) {
					if (isset($value[$index]) && is_int($key)) {
						$key = $value[$index];
					}
					if (self::isArrayOfArrays($value)) {
						$value = self::indexByValue($value, $index);
					}
				}
				return [$key, $value];
			}
		);
	}

	/**
	 * @param mixed $array
	 * @return bool
	 */
	public static function isArrayOfArrays($array): bool
	{
		if (!is_array($array)) {
			return false;
		}
		foreach ($array as $value) {
			if (!is_array($value)) {
				return false;
			}
		}
		return true;
	}

	/**
	 * @param iterable<mixed> $collection
	 * @return bool
	 */
	public static function isEmpty(iterable $collection): bool
	{
		return self::firstKey($collection) === null;
	}

	/**
	 * @param iterable<mixed> $collection
	 * @return array<int|string>
	 */
	public static function iterableKeys(iterable $collection): array
	{
		return array_keys(
			self::mapWithKeys(
				$collection,
				function ($key): array {
					return [$key, null];
				}
			)
		);
	}

	/**
	 * @param array<mixed> $array
	 * @param string $expression
	 * @return mixed
	 * @throws JSONPathException
	 */
	public static function jsonPath(array $array, string $expression)
	{
		return (new JSONPath($array))->find($expression)->data();
	}

	/**
	 * @param iterable<mixed> $collection
	 * @param callable $callback
	 * @return array<mixed>
	 */
	public static function mapCollection(iterable $collection, callable $callback): array
	{
		$result = [];
		foreach ($collection as $item) {
			$result[] = $callback($item);
		}
		return $result;
	}

	/**
	 * @param iterable<mixed> $collection
	 * @param callable $callback
	 * @return array<mixed>
	 */
	public static function mapIterable(iterable $collection, callable $callback): array
	{
		$result = [];
		foreach ($collection as $key => $item) {
			$result[$key] = $callback($item, $key, $collection);
		}
		return $result;
	}

	/**
	 * @param callable $callback
	 * @param iterable<mixed> $collection
	 * @return array<int|string, mixed>
	 */
	public static function mapKeysFromValues(iterable $collection, callable $callback): array
	{
		return self::mapKeys($collection, $callback);
	}

	/**
	 * @param callable $callback
	 * @param array<mixed> $array
	 * @return array<mixed>
	 */
	public static function mapValues(array $array, callable $callback): array
	{
		return array_values(array_map($callback, $array));
	}

	/**
	 * @param callable $callback
	 * @param iterable<mixed> $collection
	 * @return array<int|string, mixed>
	 */
	public static function mapWithKeys(iterable $collection, callable $callback): array
	{
		return self::mapKeys($collection, $callback, true);
	}

	/**
	 * @param array<mixed> ...$arrays
	 * @return array<mixed>
	 */
	public static function mergeAllRecursive(
		array ...$arrays
	): array {
		return array_reduce(
			$arrays,
			[self::class, 'mergeRecursiveDistinct'],
			[]
		);
	}

	/**
	 * @param array<mixed> $a1
	 * @param array<mixed> $a2
	 * @return array<mixed>
	 */
	public static function mergeRecursiveDistinct(
		array &$a1,
		array &$a2
	): array {
		$result = $a1;
		foreach ($a2 as $key => &$value) {
			if (is_array($value) && isset($result[$key]) && is_array($result[$key])) {
				$result[$key] = self::mergeRecursiveDistinct($result[$key], $value);
			} else {
				$result[$key] = $value;
			}
		}
		return $result;
	}

	/**
	 * @param array<mixed> $array
	 * @param callable $replacer
	 * @param callable|null $condition
	 * @param array<int|string> $path
	 * @return array<mixed>
	 */
	public static function replaceByCallbackWithKeys(
		array $array,
		callable $replacer,
		?callable $condition = null,
		array $path = []
	): array {
		$result = [];
		foreach ($array as $key => $value) {
			if ($condition === null || $condition($key, $value, $path)) {
				$r = $replacer($key, $value, $path);
				if ($r === null) {
					continue;
				}
				[$k, $v] = $r;
				$result[$k] = $v;
			} elseif (is_array($value)) {
				array_push($path, $key);
				$result[$key] = self::replaceByCallbackWithKeys(
					$value,
					$replacer,
					$condition,
					$path
				);
			} else {
				$result[$key] = $value;
			}
		}
		return $result;
	}

	/**
	 * @param array<mixed> $array
	 * @param string $prefix
	 * @param callable $replacer
	 * @return array<mixed>
	 */
	public static function replaceByPrefix(
		array $array,
		string $prefix,
		callable $replacer
	): array {
		return self::replaceByPrefixWithKeys(
			$array,
			$prefix,
			function ($key, $value) use ($prefix, $replacer): array {
				return [
					str_replace($prefix, '', $key),
					$replacer($value),
				];
			}
		);
	}

	/**
	 * @param array<mixed> $array
	 * @param string $prefix
	 * @param callable $replacer
	 * @return array<mixed>
	 */
	public static function replaceByPrefixWithKeys(
		array $array,
		string $prefix,
		callable $replacer
	): array {
		return self::replaceByCallbackWithKeys(
			$array,
			$replacer,
			function ($k) use ($prefix): bool {
				return strpos($k, $prefix) === 0;
			}
		);
	}

	/**
	 * @param array<mixed> $array
	 * @return array<mixed>
	 */
	public static function sortedValues(
		array $array
	): array {
		$values = array_values($array);
		sort($values);
		return $values;
	}

	/**
	 * @param array<mixed> $array
	 * @param callable $compare
	 * @return array<mixed>
	 */
	public static function splitBy(
		array $array,
		callable $compare
	): array {
		$result = [];
		foreach ($array as $value) {
			$key = $compare($value);
			$result[$key][] = $value;
		}
		return $result;
	}

	/**
	 * @param array<mixed> $a1
	 * @param array<mixed> $a2
	 * @return array<mixed>
	 */
	public static function unionUniqueValues(
		array $a1,
		array $a2
	): array {
		return array_unique(array_merge(array_values($a1), array_values($a2)));
	}

	/**
	 * @param iterable<mixed> $collection
	 * @param callable $callback
	 * @param bool $keys
	 * @return array<int|string, mixed>
	 */
	private static function mapKeys(
		iterable $collection,
		callable $callback,
		bool $keys = false
	): array {
		$result = [];
		foreach ($collection as $key => $item) {
			$kv = $keys ? $callback($key, $item) : $callback($item);
			if ($kv === null) {
				continue;
			}
			[$key, $item] = $kv;
			if (isset($result[$key])) {
				throw new InvalidStateException("Unable to rewrite key '$key'. Check if returned keys are unique.");
			}
			$result[$key] = $item;
		}
		return $result;
	}
}
