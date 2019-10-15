<?php declare (strict_types = 1);

namespace Wavevision\UtilsTests;

use PHPUnit\Framework\TestCase;
use Wavevision\Utils\Path;

/**
 * @covers \Wavevision\Utils\Path
 */
class PathTest extends TestCase
{

	public function testJoin(): void
	{
		$this->assertEquals('/path/to/somewhere', Path::join('//path', '/to', '\\\\somewhere/'));
	}
}
