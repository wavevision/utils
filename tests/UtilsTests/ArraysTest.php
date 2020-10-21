<?php declare (strict_types = 1);

namespace Wavevision\UtilsTests;

use ArrayIterator;
use Nette\InvalidArgumentException;
use Nette\InvalidStateException;
use Nette\Utils\ArrayHash;
use PHPUnit\Framework\TestCase;
use stdClass;
use Wavevision\Utils\Arrays;
use function array_key_first;
use function is_int;
use function is_string;
use function reset;

/**
 * @covers \Wavevision\Utils\Arrays
 */
class ArraysTest extends TestCase
{

	public function testPop(): void
	{
		$a = ['one'];
		$this->assertEquals('one', Arrays::pop($a));
	}

	public function testAppendAll(): void
	{
		$this->assertEquals(['one', 'two', 'three', 4], Arrays::appendAll(['one', 'two'], ['three', 4]));
	}

	public function testBuildTree(): void
	{
		$this->assertEquals(['one' => ['two' => [3 => 'value']]], Arrays::buildTree(['one', 'two', 3], 'value'));
	}

	public function testCopySelected(): void
	{
		$this->assertEquals(['one' => 1], Arrays::copySelected(['one' => 1, 'two' => 2], 'one'));
	}

	public function testCountFilled(): void
	{
		$this->assertEquals(1, Arrays::countFilled(['', '', '1']));
		$this->assertEquals(2, Arrays::countFilled(['', '1', 1]));
		$this->assertEquals(0, Arrays::countFilled(['']));
		$this->assertEquals(0, Arrays::countFilled([]));
	}

	public function testDiff(): void
	{
		$this->assertEquals([2 => 3], Arrays::diff(['one', 'two', 3], ['one', 'two', 'three']));
		$this->assertEquals([[1 => 'one']], Arrays::diff([[1 => 'one']], ['one', 'two']));
		$this->assertEquals(
			['three' => 3],
			Arrays::diff([['one' => 'two'], 'three' => 3], [['one' => 'two']])
		);
		$this->assertEquals([], Arrays::diff(['one', 'two'], [2 => 'two'], false));
	}

	public function testEach(): void
	{
		$one = [];
		$two = ['one', 'two'];
		Arrays::each(
			$two,
			function (string $item) use (&$one): void {
				$one[] = $item;
			}
		);
		$this->assertEquals($one, $two);
	}

	public function testExtractObjectIds(): void
	{
		$o1 = new stdClass();
		$o1->id = 1;
		$o2 = new stdClass();
		$o2->id = 'some-id';
		$o3 = new stdClass();
		$this->assertEquals([1, 'some-id', null], Arrays::extractObjectIds([$o1, $o2, $o3]));
	}

	public function testExtractValuesFromObject(): void
	{
		$o1 = new stdClass();
		$o1->something = 'someValue';
		$o2 = $this->getMockBuilder(stdClass::class)
			->addMethods(['getSomething'])
			->getMock();
		$o2->method('getSomething')
			->willReturn('otherValue');
		$this->assertEquals(['someValue', 'otherValue'], Arrays::extractObjectValues([$o1, $o2], 'something'));
	}

	public function testExtractValues(): void
	{
		$this->assertEquals(
			['value', 'something', null],
			Arrays::extractValues([['key' => 'value'], ['key' => 'something'], []], 'key')
		);
	}

	public function testFirstItem(): void
	{
		$item = new stdClass();
		$this->assertSame($item, Arrays::firstItem([$item]));
	}

	public function testFirstKey(): void
	{
		$this->assertEquals(0, Arrays::firstKey(['one']));
		$this->assertEquals('some-key', Arrays::firstKey(['some-key' => 'some-value', 2 => 'two']));
	}

	public function testLastItem(): void
	{
		$this->assertEquals(1, Arrays::lastItem([2, 1]));
	}

	public function testLastKey(): void
	{
		$this->assertEquals(2, Arrays::lastKey([1 => 1, 2 => 2]));
		$iterable = new ArrayIterator(['one', 'two']);
		$this->assertEquals(1, Arrays::lastKey($iterable));
	}

	public function testFilter(): void
	{
		$this->assertEquals(
			['here'],
			Arrays::filter(['one', 'here', 'two'], fn(string $item): bool => $item === 'here')
		);
		$this->assertEquals(
			[1 => 'here'],
			Arrays::filter(['one', 'here', 'two'], fn(string $item): bool => $item === 'here', true)
		);
	}

	public function testFlattenKeys(): void
	{
		$this->assertEquals(
			['path.to.value' => 123, 'next.path' => 'some-value'],
			Arrays::flattenKeys(['path' => ['to' => ['value' => 123]], 'next' => ['path' => 'some-value']])
		);
	}

	public function testFromArrayHash(): void
	{
		$a = ['one' => [], 'two' => ['x' => []]];
		$b = [1 => 'one'];
		foreach ([$a, $b] as $array) {
			$this->assertEquals($array, Arrays::fromArrayHash(ArrayHash::from($array)));
		}
	}

	public function testHasNestedKey(): void
	{
		$array = [
			'parts' => [
				'0' => [
					'material' => null,
					'format' => 1,
				],
			],
			'single' => '',
		];
		$this->assertEquals(true, Arrays::hasNestedKey($array, 'single'));
		$this->assertEquals(true, Arrays::hasNestedKey($array, 'parts', '0'));
		$this->assertEquals(true, Arrays::hasNestedKey($array, ...['parts', '0', 'material']));
		$this->assertEquals(true, Arrays::hasNestedKey($array, 'parts', '0', 'format'));
		$this->assertEquals(false, Arrays::hasNestedKey($array, 'parts', '1', 'format'));
		$this->assertEquals(false, Arrays::hasNestedKey($array, '42'));
		$this->assertEquals(true, Arrays::hasNestedKey($array));
	}

	public function testGetNestedValue(): void
	{
		$array = [
			'1' => [
				'2' => 42,
			],
		];
		$this->assertEquals(['2' => 42], Arrays::getNestedValue($array, '1'));
		$this->assertEquals(42, Arrays::getNestedValue($array, '1', '2'));
		$this->assertEquals(null, Arrays::getNestedValue($array, '3'));
		$this->assertEquals(null, Arrays::getNestedValue([], 'x'));
		$this->assertEquals($array, Arrays::getNestedValue($array));
	}

	public function testHasSameValues(): void
	{
		$this->assertEquals(true, Arrays::hasSameValues([], []));
		$this->assertEquals(false, Arrays::hasSameValues([], ['one']));
		$this->assertEquals(true, Arrays::hasSameValues(['one'], [1 => 'one']));
		$this->assertEquals(true, Arrays::hasSameValues(['one', 'two'], ['two', 'one']));
	}

	public function testImplode(): void
	{
		$this->assertEquals('one,two,three', Arrays::implode(['one', 'two', 'three']));
		$this->assertEquals('one|two-three', Arrays::implode(['one', 'two', 'three'], '|', '-'));
	}

	public function testIndexByValue(): void
	{
		$array = [
			'name' => 'name',
			[
				'id' => 1,
				'array' => [
					[
						'id' => 2,
					],
					[
						'id' => 3,
					],
				],
			],
			[
				'id' => 2,
			],
			'index' => [
				'id' => 1,
			],
		];
		$this->assertEquals(
			[
				'name' => 'name',
				1 => [
					'id' => 1,
					'array' => [
						2 => [
							'id' => 2,
						],
						3 => [
							'id' => 3,
						],
					],
				],
				2 => [
					'id' => 2,
				],
				'index' => [
					'id' => 1,
				],
			],
			Arrays::indexByValue($array, 'id')
		);
	}

	public function testIsArrayOfArrays(): void
	{
		$this->assertTrue(Arrays::isArrayOfArrays([[]]));
		$this->assertTrue(Arrays::isArrayOfArrays(['hello', []]));
		$this->assertFalse(Arrays::isArrayOfArrays([]));
		$this->assertFalse(Arrays::isArrayOfArrays('hello'));
	}

	public function testIsEmpty(): void
	{
		$this->assertTrue(Arrays::isEmpty([]));
		$this->assertFalse(Arrays::isEmpty(['test']));
	}

	public function testIterableKeys(): void
	{
		$this->assertEquals(['one', 2], Arrays::iterableKeys(['one' => 1, 2 => 'two']));
	}

	public function testJsonPath(): void
	{
		$data = [
			'a' => [
				'b' => ['c' => 'd'],
				'e' => ['c' => null],
			],
		];
		$this->assertEquals(['d', null], Arrays::jsonPath($data, '$.a[*].c.'));
	}

	public function testMapCollection(): void
	{
		$this->mapCollection([1]);
		$class = new stdClass();
		$class->a = 1;
		$this->mapCollection($class);
	}

	public function testMapIterable(): void
	{
		$class = new stdClass();
		$class->id = 1;
		$this->assertEquals(
			[0 => '1/0/0'],
			Arrays::mapIterable(
				[$class],
				function (
					stdClass $item,
					int $key,
					array $collection
				): string {
					$firstKey = array_key_first($collection);
					return $item->id . '/' . $firstKey . '/' . $key;
				}
			)
		);
	}

	public function testMapKeysFromValues(): void
	{
		$array = [1 => 'test', 2 => 'something'];
		$this->assertEquals(
			['test' => $array],
			Arrays::mapKeysFromValues(
				[$array],
				function ($value) {
					return [$value[1], $value];
				}
			)
		);
		$this->assertEquals(
			[],
			Arrays::mapKeysFromValues(
				[$array],
				function () {
					return null;
				}
			)
		);
	}

	public function testMapValues(): void
	{
		$this->assertEquals(
			['one', 'two'],
			Arrays::mapValues(
				[['one'], ['two']],
				function (array $array): string {
					return reset($array);
				}
			)
		);
	}

	public function testMapWithKeys(): void
	{
		$this->assertEquals(
			['key-0-extra' => 'translate(value-0)'],
			Arrays::mapWithKeys(
				[
					'key-0' => 'value-0',
				],
				function ($key, $value) {
					return ["$key-extra", "translate($value)"];
				}
			)
		);
		$this->assertEquals(
			[],
			Arrays::mapWithKeys(
				['1', '2'],
				function () {
					return null;
				}
			)
		);
		$this->expectException(InvalidStateException::class);
		$this->expectExceptionMessage("Unable to rewrite key 'c'! Check if returned keys are unique.");
		Arrays::mapWithKeys(
			[
				1,
				2,
			],
			function () {
				return ['c', 'c'];
			}
		);
	}

	public function testMergeAllRecursive(): void
	{
		$this->assertEquals(
			[
				'a' => 'b',
				'x' => [
					'x1' => 'x1',
					'x2' => 'x2',
				],
			],
			Arrays::mergeAllRecursive([], ['a' => 'a'], ['x' => ['x1' => 'x1']], ['x' => ['x2' => 'x2']], ['a' => 'b'])
		);
	}

	public function testNthItem(): void
	{
		$this->assertEquals(2, Arrays::nthItem([1, 2, 3], 1));
		$this->assertEquals(3, Arrays::nthItem([1, 2, 3], -1));
		$this->expectException(InvalidArgumentException::class);
		Arrays::nthItem([], 2);
	}

	public function testReplaceByCallbackWithKeys(): void
	{
		$this->assertEquals(
			[
				1 => 'one',
				2 => 'two',
			],
			Arrays::replaceByCallbackWithKeys(
				[
					'one' => 1,
					'two' => 2,
				],
				function ($k, $v) {
					return [$v, $k];
				}
			)
		);
		$this->assertEquals(
			[
				1 => 'one',
				'two' => 2,
				'nested' => [3 => 'three'],
			],
			Arrays::replaceByCallbackWithKeys(
				[
					'one' => 1,
					'two' => 2,
					'nested' => [3 => 'three'],
				],
				function ($k, $v) {
					return [$v, $k];
				},
				function ($k, $v) {
					return $v === 1;
				}
			)
		);
		$this->assertEquals(
			[
				'two' => 2,
				'nested' => [
					'one' => 1,
				],
			],
			Arrays::replaceByCallbackWithKeys(
				[
					'one' => 1,
					'two' => 2,
					'nested' => [
						'one' => 1,
					],
				],
				function ($k, $v) {
					if ($k === 'one') {
						return null;
					}
					return [$k, $v];
				}
			)
		);
		$called = false;
		Arrays::replaceByCallbackWithKeys(
			[
				1 => [
					2,
				],
				0 => '0',
				3 => [
					6 => [
						4 => 'hit',
					],
				],
			],
			function (): void {
			},
			function ($key, $value, $path) use (&$called): void {
				if ($key === 4) {
					$this->assertEquals([3, 6], $path);
					$called = true;
				}
			}
		);
		$this->assertTrue($called);
	}

	public function testReplaceByPrefix(): void
	{
		$this->assertEquals(
			['key' => 0],
			Arrays::replaceByPrefix(
				[
					'@prefix_key' => 0,
				],
				'@prefix_',
				function ($value) {
					return $value;
				}
			)
		);
	}

	public function testReplaceByPrefixWithKeys(): void
	{
		$data = [
			'nothing' => 0,
			'@prefix' => ['source' => 'x'],
			'nested' => [
				'nothing' => 0,
				'@prefix' => 'filename2',
			],
		];
		$this->assertEquals(
			[
				'nothing' => 0,
				'newPrefix' => null,
				'nested' => [
					'nothing' => 0,
					'newPrefix' => null,
				],
			],
			Arrays::replaceByPrefixWithKeys(
				$data,
				'@prefix',
				function () {
					return ['newPrefix', null];
				}
			)
		);
	}

	public function testSortedValues(): void
	{
		$this->assertEquals([1, 2, 3, 4], Arrays::sortedValues(['one' => 2, 4, 1, 3]));
	}

	public function testSplitBy(): void
	{
		$this->assertEquals(
			[
				0 => [1],
				1 => ['1'],
				2 => ['42'],
			],
			Arrays::splitBy(
				[
					1,
					'1',
					'42',
				],
				function ($v) {
					if ($v === '42') {
						return 2;
					}
					if (is_int($v)) {
						return 0;
					}
					if (is_string($v)) {
						return 1;
					}
				}
			)
		);
	}

	public function testUnionValues(): void
	{
		$this->assertEquals([1, 2, 3, 4], Arrays::unionUniqueValues(['a' => 1, 2], ['a' => 3, 4, 2]));
	}

	public function testReplaceByPath(): void
	{
		$this->assertEquals(
			['l1' => ['l2' => 'rewritten']],
			Arrays::replaceByPath(
				[
					'l1' => [
						'l2' => 'original',
					],
				],
				['l1', 'l2'],
				fn($value) => 'rewritten'
			)
		);
		$this->assertEquals(
			['l1' => ['l2' => ['original', 'rewritten']]],
			Arrays::replaceByPath(
				['l1' => ['l2' => ['original', 'original']]],
				['l1', 'l2', 1],
				fn($value) => 'rewritten'
			)
		);
	}

	/**
	 * @param mixed $input
	 */
	private function mapCollection($input): void
	{
		$this->assertEquals(
			[1],
			Arrays::mapCollection(
				$input,
				function ($a) {
					return $a;
				}
			)
		);
	}

}
