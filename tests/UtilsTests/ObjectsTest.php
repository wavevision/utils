<?php declare (strict_types = 1);

namespace Wavevision\UtilsTests;

use PHPUnit\Framework\TestCase;
use Wavevision\Utils\Objects;

/**
 * @covers \Wavevision\Utils\Objects
 */
class ObjectsTest extends TestCase
{

	public function testGetIfNotNull(): void
	{
		$mock = $this->getMockBuilder(\stdClass::class)
			->addMethods(['getYoMama'])
			->getMock();
		$mock->method('getYoMama')
			->willReturn('mama');
		$this->assertEquals('mama', Objects::getIfNotNull($mock, 'yoMama'));
		$this->assertEquals(null, Objects::getIfNotNull(null, 'yoMama'));
	}

	public function testHasGetter(): void
	{
		$this->assertTrue(
			Objects::hasGetter(
				$this->getMockBuilder(\stdClass::class)
					->addMethods(['getSomething'])
					->getMock(),
				'something'
			)
		);
		$this->assertFalse(Objects::hasGetter(new \stdClass(), 'something'));
	}

	public function testHasSetter(): void
	{
		$this->assertTrue(
			Objects::hasSetter(
				$this->getMockBuilder(\stdClass::class)
					->addMethods(['setSomething'])
					->getMock(),
				'something'
			)
		);
		$this->assertFalse(Objects::hasSetter(new \stdClass(), 'something'));
	}

	public function testSet(): void
	{
		$mock = $this->getMockBuilder(\stdClass::class)
			->addMethods(['setYoMama'])
			->getMock();
		$mock->method('setYoMama')
			->willReturnSelf();
		$this->assertSame($mock, Objects::set($mock, 'yoMama', null));
	}
}
