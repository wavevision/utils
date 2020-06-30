<?php declare (strict_types = 1);

namespace Wavevision\UtilsTests\DOM;

use Nette\Utils\Html;
use Nette\Utils\Json;
use PHPUnit\Framework\TestCase;
use Wavevision\Utils\DOM\DataAttribute;

class DataAttributeTest extends TestCase
{

	public function testJsonSerialize(): void
	{
		$this->assertEquals('{"data-test":""}', Json::encode($this->createDataAttribute()));
	}

	public function testAsArray(): void
	{
		$this->assertEquals(['data-test' => ''], $this->createDataAttribute()->asArray());
	}

	public function testAsString(): void
	{
		$this->assertEquals('data-test=""', $this->createDataAttribute()->asString());
		$this->assertEquals('data-prefix-test=""', (string)$this->createDataAttribute('prefix'));
	}

	public function testAsTuple(): void
	{
		$this->assertEquals(['data-test', ''], $this->createDataAttribute()->asTuple());
	}

	public function testAssign(): void
	{
		$attribute = $this->createDataAttribute();
		$this->assertSame('', $attribute->get($attribute->assign(Html::el())));
	}

	public function testGet(): void
	{
		$this->assertNull($this->createDataAttribute()->get(Html::el()));
	}

	public function testHas(): void
	{
		$this->assertFalse($this->createDataAttribute()->has(Html::el()));
	}

	public function testName(): void
	{
		$this->assertEquals('data-prefix-test', $this->createDataAttribute('prefix')->name());
	}

	public function testRemove(): void
	{
		$attribute = $this->createDataAttribute();
		$element = Html::el();
		$this->assertEquals('', $attribute->get($attribute->assign($element)));
		$this->assertNull($attribute->get($attribute->remove($element)));
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
