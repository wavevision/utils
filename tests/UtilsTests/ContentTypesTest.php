<?php declare (strict_types = 1);

namespace Wavevision\UtilsTests;

use PHPUnit\Framework\TestCase;
use Wavevision\Utils\ContentTypes;

class ContentTypesTest extends TestCase
{

	public function testGetExtension(): void
	{
		$this->assertEquals('js', ContentTypes::getExtension(ContentTypes::JS));
		$this->assertEquals('.js', ContentTypes::getExtension(ContentTypes::JS, true));
	}

	public function testGetFilename(): void
	{
		$this->assertEquals('style.css', ContentTypes::getFilename('style', ContentTypes::CSS));
	}

}
