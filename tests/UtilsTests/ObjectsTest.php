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
