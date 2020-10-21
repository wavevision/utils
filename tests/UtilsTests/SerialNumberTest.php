<?php declare(strict_types = 1);

namespace Wavevision\UtilsTests;

use PHPUnit\Framework\TestCase;
use Wavevision\Utils\SerialNumber;
use Wavevision\Utils\SerialNumberInvalidMask;
use function date;

/**
 * @covers \Wavevision\Utils\SerialNumber
 */
class SerialNumberTest extends TestCase
{

	public function testGenerate2(): void
	{
		$date = date('y');
		$this->assertEquals($date . '011', SerialNumber::generate('%YY%01%X%', 1));
		$this->assertEquals($date . '02', SerialNumber::generate('%YY%%XX%', 2));
		$this->assertEquals('13' . $date, SerialNumber::generate('%XX%%YY%', 13));
	}

	public function testGenerate4(): void
	{
		$date = date('Y');
		$this->assertEquals('a' . $date . '01001b', SerialNumber::generate('a%YYYY%01%XXX%b', 1));
	}

	public function testInvalid(): void
	{
		$this->assertInvalidMask('');
		$this->assertInvalidMask('%YY%');
		$this->assertInvalidMask('%XXXX%');
		$this->assertInvalidMask('%Y%%XXX%');
		$this->assertInvalidMask('%YYY%%XXX%');
		$this->assertInvalidMask('%YY%%YY%%XXX%');
		$this->assertInvalidMask('%YY%XXX%');
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
