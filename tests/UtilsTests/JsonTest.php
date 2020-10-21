<?php declare (strict_types = 1);

namespace Wavevision\UtilsTests;

use PHPUnit\Framework\TestCase;
use Wavevision\Utils\Json;

/**
 * @covers \Wavevision\Utils\Json
 */
class JsonTest extends TestCase
{

	public function testEncodePretty(): void
	{
		$this->assertIsString(Json::encodePretty([1 => 'one']));
		$this->assertIsString(Json::encodePretty(null, Json::INDENT_JS));
	}

}
