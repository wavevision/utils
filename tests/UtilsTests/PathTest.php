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
		$this->assertEquals('/path/to/somewhere', Path::join('//path', '/to', null, '\\\\somewhere/'));
		$this->assertEquals('http://url.tld/path/to/asset', Path::join('http://url.tld', 'path', 'to', '/asset'));
		$this->assertEquals('r/path', Path::join('r', 'path'));
	}

	public function testInstanceJoin(): void
	{
		$root = Path::create('/r');
		$nested = $root->path('a', 'b');
		$this->assertSame('/r/a/b', (string)$nested);
		$this->assertSame('/r/a/b/f.txt', $nested->path('f.txt')->string());
		$this->assertSame('b/a', Path::create()->path('b', 'a')->string());
	}

}
