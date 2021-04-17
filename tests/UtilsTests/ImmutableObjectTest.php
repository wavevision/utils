<?php declare (strict_types = 1);

namespace Wavevision\UtilsTests;

use PHPUnit\Framework\TestCase;

class ImmutableObjectTest extends TestCase
{

	public function testWithMutation(): void
	{
		$object = new ImmutableObject('default');
		$this->assertSame('default', $object->getProp());
		$this->assertNotSame($object, $object->withProp('changed'));
	}

}
