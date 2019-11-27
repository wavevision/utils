<?php declare(strict_types = 1);

namespace Wavevision\UtilsTests;

use phpmock\phpunit\PHPMock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Wavevision\Utils\SerialNumber;
use Wavevision\Utils\SerialNumberInvalidMask;
use Wavevision\Utils\Strings;

/**
 * @covers \Wavevision\Utils\SerialNumber
 */
class SerialNumberTest extends TestCase
{

	use PHPMock;

	public function testGenerate2(): void
	{
		$this->mockDate('89', 'y');
		$this->assertEquals('89011', SerialNumber::generate('%YY%01%X%', 1));
		$this->assertEquals('8902', SerialNumber::generate('%YY%%XX%', 2));
		$this->assertEquals('1389', SerialNumber::generate('%XX%%YY%', 13));
	}

	public function testGenerate4(): void
	{
		$this->mockDate('1989', 'Y');
		$this->assertEquals('a198901001b', SerialNumber::generate('a%YYYY%01%XXX%b', 1));
	}

	public function testInvalid(): void
	{
		$this->mockDate('89', 'y');
		$this->assertInvalidMask('');
		$this->assertInvalidMask('%YY%');
		$this->assertInvalidMask('%XXXX%');
		$this->assertInvalidMask('%Y%%XXX%');
		$this->assertInvalidMask('%YYY%%XXX%');
		$this->assertInvalidMask('%YY%%YY%%XXX%');
		$this->assertInvalidMask('%YY%XXX%');
	}

	private function mockDate(string $date, string $format): void
	{
		$this->mockFunction('date')->expects($this->any())->with($format)->willReturn($date);
	}

	private function mockFunction(string $function): MockObject
	{
		return $this->getFunctionMock(Strings::getNamespace(SerialNumber::class), $function);
	}

	private function assertInvalidMask(string $pattern): void
	{
		$e = null;
		try {
			SerialNumber::generate($pattern, 1);
		} catch (SerialNumberInvalidMask $e) {
		}
		$this->assertInstanceOf(SerialNumberInvalidMask::class, $e, 'Expected invalid mask, but mask is valid.');
	}

}
