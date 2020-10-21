<?php declare (strict_types = 1);

namespace Wavevision\UtilsTests;

use Nette\IOException;
use PHPUnit\Framework\TestCase;
use Wavevision\Utils\Server;

/**
 * @covers \Wavevision\Utils\Server
 */
class ServerTest extends TestCase
{

	public function testGetIniThrowsException(): void
	{
		$this->expectException(IOException::class);
		Server::getIni('undefined');
	}

	public function testGetIniReturnsKeyValue(): void
	{
		$this->assertIsString(Server::getIni('default_charset'));
	}

	public function testGetMaxUploadSizeReturnsCustomizedValue(): void
	{
		$this->assertEquals(1048576, Server::getMaxUploadSize(1, 'M'));
	}

	public function testGetMaxUploadSizeReturnsDefault(): void
	{
		$this->assertIsInt(Server::getMaxUploadSize());
	}

	public function testIsCLI(): void
	{
		$this->assertIsBool(Server::isCLI());
	}

}
