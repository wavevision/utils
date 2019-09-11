<?php declare (strict_types = 1);

namespace Wavevision\UtilsTests;

use PHPUnit\Framework\TestCase;
use Wavevision\Utils\Arrays;

class ArraysTest extends TestCase
{

	public function testBuildTree(): void
	{
		$this->assertEquals(['one' => ['two' => [3 => 'value']]], Arrays::buildTree(['one', 'two', 3], 'value'));
	}
}
