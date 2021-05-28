<?php declare (strict_types = 1);

namespace Wavevision\Utils;

use Flow\JSONPath\JSONPath;
use Flow\JSONPath\JSONPathException;
use Nette\InvalidArgumentException;
use Nette\InvalidStateException;
use Nette\Utils\ArrayHash;
use Nette\Utils\Arrays as NetteArrays;
use function abs;
use function array_key_exists;
use function array_keys;
use function array_map;
use function array_merge;
use function array_pop;
use function array_push;
use function array_reduce;
use function array_slice;
use function array_unique;
use function array_values;
use function count;
use function end;
use function is_array;
use function is_int;
use function is_string;
use function key;
use function key_exists;
use function sort;
use function str_replace;
use function strpos;
use function trim;

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
		$result = [];
		foreach ($keys as $key) {
			$result[$key] = $source[$key];
		}
		return $result;
	}

	/**
	 * @param iterable<mixed> $values
	 */
	public static function countFilled(iterable $values): int
	{
		$result = 0;
		foreach ($values as $value) {
			if ($value === null || $value === '') {
				continue;
			}
			$result++;
		}
		return $result;
	}

	/**
	 * @param array<mixed> $a1
	 * @param array<mixed> $a2
	 * @return array<mixed>
	 */
	public static function diff(array $a1, array $a2, bool $includeMissingKeys = true): array
	{
		$result = [];
		foreach ($a1 as $key => $value) {
			if (array_key_exists($key, $a2)) {
				if (is_array($value) && is_array($a2[$key])) {
					$recursiveDiff = self::diff($value, $a2[$key], $includeMissingKeys);
					if (count($recursiveDiff)) {
						$result[$key] = $recursiveDiff;
					}
				} else {
					if ($value && !self::sameValues($value, $a2[$key])) {
						$result[$key] = $value;
					}
				}
			} elseif ($includeMissingKeys) {
				$result[$key] = $value;
			}
		}
		return $result;
	}

	/**
	 * @param iterable<mixed> $collection
	 * @param callable(mixed): mixed $callback
	 */
	public static function each(iterable $collection, callable $callback): void
	{
		foreach ($collection as $item) {
			$callback($item);
		}
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
	 * @return array<mixed>
	 */
	public static function extractObjectValues(iterable $collection, string $property): array
	{
		return self::mapCollection(
			$collection,
			function (object $item) use ($property) {
				if (Objects::hasGetter($item, $property)) {
					return Objects::get($item, $property);
				}
				return $item->$property ?? null;
			}
		);
	}

	/**
	 * @param array<array<mixed>> $array
	 * @param int|string $key
	 * @return array<mixed>
	 */
	public static function extractValues(array $array, $key): array
	{
		return array_map(
			function (array $item) use ($key) {
				return $item[$key] ?? null;
			},
			$array
		);
	}

	/**
	 * @param mixed $collection
	 * @return mixed
	 */
	public static function firstItem($collection)
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
	 * @param iterable<mixed> $collection
	 * @param callable(mixed): bool $callback
	 * @return array<mixed>
	 */
	public static function filter(iterable $collection, callable $callback, bool $preserveKeys = false): array
	{
		$filtered = [];
		foreach ($collection as $key => $item) {
			if ($callback($item)) {
				if ($preserveKeys) {
					$filtered[$key] = $item;
				} else {
					$filtered[] = $item;
				}
			}
		}
		return $filtered;
	}

	/**
	 * @param array<mixed> $array
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
	 * @param ArrayHash<mixed> $arrayHash
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
	 * @return mixed
	 */
	public static function getNestedValue(array $array, string ...$keys)
	{
		if (self::hasNestedKey($array, ...$keys)) {
			$current = $array;
			foreach ($keys as $key) {
				$current = $current[$key];
			}
			return $current;
		}
		return null;
	}

	/**
	 * @param array<mixed> $array
	 */
	public static function hasNestedKey(array $array, string ...$keys): bool
	{
		foreach ($keys as $keyPart) {
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
	 */
	public static function hasSameValues(array $a1, array $a2): bool
	{
		return self::sortedValues($a1) === self::sortedValues($a2);
	}

	/**
	 * @param array<string> $array
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
	 */
	public static function isArrayOfArrays($array): bool
	{
		if (!is_array($array)) {
			return false;
		}
		foreach ($array as $item) {
			if (is_array($item)) {
				return true;
			}
		}
		return false;
	}

	/**
	 * @param iterable<mixed> $collection
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
	 * @param array<mixed> $data
	 * @return mixed
	 * @throws JSONPathException
	 */
	public static function jsonPath(array $data, string $expression)
	{
		return (new JSONPath($data))->find($expression)->getData();
	}

	/**
	 * @param mixed $collection
	 * @return mixed
	 */
	public static function lastItem($collection)
	{
		return $collection[self::lastKey($collection)] ?? null;
	}

	/**
	 * @param iterable<mixed> $collection
	 * @return int|string|null
	 */
	public static function lastKey(iterable $collection)
	{
		$array = [];
		if (is_array($collection)) {
			$array = $collection;
		} else {
			array_push($array, ...$collection);
		}
		end($array);
		return key($array);
	}

	/**
	 * @param mixed $collection
	 * @return array<mixed>
	 */
	public static function mapCollection($collection, callable $callback): array
	{
		$result = [];
		foreach ($collection as $item) {
			$result[] = $callback($item);
		}
		return $result;
	}

	/**
	 * @param iterable<mixed> $collection
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
	 * @param iterable<mixed> $collection
	 * @return array<int|string, mixed>
	 */
	public static function mapKeysFromValues(iterable $collection, callable $callback): array
	{
		return self::mapKeys($collection, $callback);
	}

	/**
	 * @param array<mixed> $array
	 * @return array<mixed>
	 */
	public static function mapValues(array $array, callable $callback): array
	{
		return array_values(array_map($callback, $array));
	}

	/**
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
	public static function mergeAllRecursive(array ...$arrays): array
	{
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
		array $a1,
		array $a2
	): array {
		$result = $a1;
		foreach ($a2 as $key => $value) {
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
	 * @return mixed
	 */
	public static function nthItem(array $array, int $index = 0)
	{
		if (abs($index) + 1 > count($array)) {
			throw new InvalidArgumentException("Index '$index' is greater than array length.");
		}
		$slice = array_slice($array, $index);
		if ($index < 0) {
			return self::lastItem($slice);
		}
		return self::firstItem($slice);
	}

	/**
	 * @param array<mixed> $array
	 * @return mixed
	 */
	public static function pop(array &$array)
	{
		return array_pop($array);
	}

	/**
	 * @param array<mixed> $array
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
				$nestedPath = $path;
				array_push($nestedPath, $key);
				$result[$key] = self::replaceByCallbackWithKeys(
					$value,
					$replacer,
					$condition,
					$nestedPath
				);
			} else {
				$result[$key] = $value;
			}
		}
		return $result;
	}

	/**
	 * @param array<mixed> $array
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
	 * @param array<mixed> $values
	 * @param array<mixed> $keys
	 * @return array<mixed>
	 */
	public static function replaceByPath(array $values, array $keys, callable $replacer): array
	{
		return self::replaceByCallbackWithKeys(
			$values,
			function ($key, $value) use ($replacer) {
				return [$key, $replacer($value)];
			},
			function ($key, $value, $path) use ($keys) {
				$path = Arrays::appendAll($path, [$key]);
				return $path === $keys;
			}
		);
	}

	/**
	 * @param iterable<mixed> $collection
	 * @return array<int|string, mixed>
	 */
	private static function mapKeys(iterable $collection, callable $callback, bool $keys = false): array
	{
		$result = [];
		foreach ($collection as $key => $item) {
			$kv = $keys ? $callback($key, $item) : $callback($item);
			if ($kv === null) {
				continue;
			}
			[$key, $value] = $kv;
			if (isset($result[$key])) {
				throw new InvalidStateException("Unable to rewrite key '$key'! Check if returned keys are unique.");
			}
			$result[$key] = $value;
		}
		return $result;
	}

	/**
	 * @param mixed $v1
	 * @param mixed $v2
	 */
	private static function sameValues($v1, $v2): bool
	{
		return self::trimValue($v1) === self::trimValue($v2);
	}

	/**
	 * @param mixed $value
	 * @return mixed
	 */
	private static function trimValue($value)
	{
		return is_string($value) ? trim($value) : $value;
	}

}
