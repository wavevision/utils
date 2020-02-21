<?php declare (strict_types = 1);

namespace Wavevision\UtilsTests\DOM;

use Nette\Utils\Html;
use PHPUnit\Framework\TestCase;
use Wavevision\Utils\DOM\DataAttribute;

class DataAttributeTest extends TestCase
{

	public function testAsArray(): void
	{
		$this->assertEquals(['data-test' => ''], $this->createDataAttribute()->asArray());
	}

	public function testAsString(): void
	{
		$this->assertEquals('data-test=""', $this->createDataAttribute()->asString());
		$this->assertEquals('data-prefix-test=""', (string)$this->createDataAttribute('prefix'));
	}

	public function testAssign(): void
	{
		$element = Html::el();
		$this->createDataAttribute()->assign($element);
		$this->assertSame('', $element->getAttribute('data-test'));
	}

	public function testName(): void
	{
		$this->assertEquals('data-prefix-test', $this->createDataAttribute('prefix')->name());
	}

	public function testRemove(): void
	{
		$attribute = $this->createDataAttribute();
		$element = Html::el();
		$this->assertTrue($attribute->assign($element)->getAttribute($attribute->name()) === '');
		$this->assertNull($attribute->remove($element)->getAttribute($attribute->name()));
	}

	public function testValue(): void
	{
		$this->assertEquals('something', $this->createDataAttribute()->value('something'));
	}

	private function createDataAttribute(?string $prefix = null): DataAttribute
	{
		return new DataAttribute('test', $prefix);
	}

}
