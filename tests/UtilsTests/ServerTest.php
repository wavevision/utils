<?php declare (strict_types = 1);

namespace Wavevision\UtilsTests;

use Nette\IOException;
use phpmock\phpunit\PHPMock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Wavevision\Utils\Server;
use Wavevision\Utils\Strings;

/**
 * @covers \Wavevision\Utils\Server
 */
class ServerTest extends TestCase
{

	use PHPMock;

	public function testGetIniThrowsException(): void
	{
		$iniGet = $this->mockFunction('ini_get');
		$iniGet->expects($this->once())->willReturn(false);
		$this->expectException(IOException::class);
		Server::getIni('key');
	}

	public function testGetIniReturnsKeyValue(): void
	{
		$iniGet = $this->mockFunction('ini_get');
		$iniGet->expects($this->once())->willReturn('value');
		$this->assertEquals('value', Server::getIni('key'));
	}

	public function testGetMaxUploadSizeReturnsCustomizedValue(): void
	{
		$iniGet = $this->mockFunction('ini_get');
		$iniGet->expects($this->exactly(2))->willReturn('100M', '120M');
		$this->assertEquals(104857600, Server::getMaxUploadSize(110, 'M'));
	}

	public function testGetMaxUploadSizeReturnsDefault(): void
	{
		$iniGet = $this->mockFunction('ini_get');
		$iniGet->expects($this->exactly(2))->willReturn('120M');
		$this->assertEquals(125829120, Server::getMaxUploadSize());
	}

	public function testIsCLI(): void
	{
		$phpSapiName = $this->mockFunction('php_sapi_name');
		$phpSapiName->expects($this->once())->willReturn('cli');
		$this->assertTrue(Server::isCLI());
	}

	private function mockFunction(string $function): MockObject
	{
		return $this->getFunctionMock(Strings::getNamespace(Server::class), $function);
	}

}
