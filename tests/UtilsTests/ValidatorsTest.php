<?php declare (strict_types = 1);

namespace Wavevision\UtilsTests;

use PHPUnit\Framework\TestCase;
use Wavevision\Utils\Validators;

/**
 * @covers \Wavevision\Utils\Validators
 */
class ValidatorsTest extends TestCase
{

	public function testIsCzechBusinessNumber(): void
	{
		$this->assertTrue(Validators::isCzechBusinessNumber('21111120'));
		$this->assertTrue(Validators::isCzechBusinessNumber('12111121'));
		$this->assertTrue(Validators::isCzechBusinessNumber('21111111'));
		$this->assertFalse(Validators::isCzechBusinessNumber('123'));
		$this->assertTrue(Validators::isCzechBusinessNumber('87356538'));
		$this->assertTrue(Validators::isCzechBusinessNumber('cz87356538'));
	}

	public function testIsCzechPersonalNumber(): void
	{
		$this->assertTrue(Validators::isCzechPersonalNumber('057101/0308'));
		$this->assertTrue(Validators::isCzechPersonalNumber('053001/0305'));
		$this->assertFalse(Validators::isCzechPersonalNumber('123'));
		$this->assertFalse(Validators::isCzechPersonalNumber('950101123'));
		$this->assertFalse(Validators::isCzechPersonalNumber('910203/3795'));
		$this->assertTrue(Validators::isCzechPersonalNumber('511002/001'));
		$this->assertTrue(Validators::isCzechPersonalNumber('7401040020'));
		$this->assertTrue(Validators::isCzechPersonalNumber('015406/6033'));
		$this->assertTrue(Validators::isCzechPersonalNumber('9609296830'));
		$this->assertTrue(Validators::isCzechPersonalNumber('571230/0308'));
		$this->assertTrue(Validators::isCzechPersonalNumber('1004263359'));
	}

	public function testIsCzechPhoneNumber(): void
	{
		$this->assertFalse(Validators::isCzechPhoneNumber('+1800666984'));
		$this->assertTrue(Validators::isCzechPhoneNumber('+420721177900'));
		$this->assertTrue(Validators::isCzechPhoneNumber('+421900886478'));
	}

	public function testIsRgbColor(): void
	{
		$this->assertFalse(Validators::isRgbColor('abc'));
		$this->assertTrue(Validators::isRgbColor('255,255,255'));
	}

}
