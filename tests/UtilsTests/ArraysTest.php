<?php declare (strict_types = 1);

namespace Wavevision\UtilsTests;

use Nette\InvalidArgumentException;
use Nette\Utils\ArrayHash;
use PHPUnit\Framework\TestCase;
use Wavevision\Utils\Arrays;

/**
 * @covers \Wavevision\Utils\Arrays
 */
class ArraysTest extends TestCase
{

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

	public function testExtractObjectIds(): void
	{
		$o1 = new \stdClass();
		$o1->id = 1;
		$o2 = new \stdClass();
		$o2->id = 'some-id';
		$o3 = new \stdClass();
		$this->assertEquals([1, 'some-id', null], Arrays::extractObjectIds([$o1, $o2, $o3]));
	}

	public function testExtractValuesFromObject(): void
	{
		$object = new \stdClass();
		$object->something = 'some-value';
		$this->assertEquals(['some-value'], Arrays::extractObjectValues([$object], 'something'));
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
		$item = new \stdClass();
		$this->assertSame($item, Arrays::firstItem([$item]));
	}

	public function testFirstKey(): void
	{
		$this->assertEquals(0, Arrays::firstKey(['one']));
		$this->assertEquals('some-key', Arrays::firstKey(['some-key' => 'some-value', 2 => 'two']));
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
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Argument "keyParts" should have at least one element.');
		Arrays::hasNestedKey([]);
	}

	public function testHasSameValues(): void
	{
		$this->assertEquals(true, Arrays::hasSameValues([], []));
		$this->assertEquals(false, Arrays::hasSameValues([], ['one']));
		$this->assertEquals(true, Arrays::hasSameValues(['one'], [1 => 'one']));
		$this->assertEquals(true, Arrays::hasSameValues(['one', 'two'], ['two', 'one']));
	}
}
