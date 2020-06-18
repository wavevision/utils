<?php declare (strict_types = 1);

namespace Wavevision\UtilsTests;

use Nette\InvalidArgumentException;
use Nette\IOException;
use PHPUnit\Framework\TestCase;
use Wavevision\Utils\Encoding;
use Wavevision\Utils\Strings;

/**
 * @covers \Wavevision\Utils\Strings
 */
class StringsTest extends TestCase
{

	public function testAutoUtf(): void
	{
		$this->assertEquals('a^ř@#$', Strings::autoUtf('a^ř@#$'));
		$this->assertEquals(
			'ářžíé',
			Strings::autoUtf(Strings::convertEncoding('ářžíé', Encoding::UTF, Encoding::LATIN))
		);
		$this->assertEquals(
			'ářžíé',
			Strings::autoUtf(Strings::convertEncoding('ářžíé', Encoding::UTF, Encoding::WINDOWS_1250))
		);
	}

	public function testCamelCaseToDashCase(): void
	{
		$this->assertEquals('some-string', Strings::camelCaseToDashCase('someString'));
	}

	public function testCamelCaseToSnakeCase(): void
	{
		$this->assertEquals('some_other_string', Strings::camelCaseToSnakeCase('someOtherString'));
	}

	public function testContains(): void
	{
		$this->assertTrue(Strings::contains('aBc', 'B'));
		$this->assertFalse(Strings::contains('aBc', 'b'));
		$this->assertTrue(Strings::contains('aBc', 'b', false));
	}

	public function testConvertBytes(): void
	{
		$this->assertEquals(1024, Strings::convertBytes('1k'));
		$this->assertEquals(1048576, Strings::convertBytes('1MB'));
		$this->assertEquals(1073741824, Strings::convertBytes('1GB'));
		$this->assertEquals(1, Strings::convertBytes('1.2'));
		$this->expectException(InvalidArgumentException::class);
		Strings::convertBytes('abc');
	}

	public function testConvertEncoding(): void
	{
		$this->expectException(IOException::class);
		Strings::convertEncoding('€', Encoding::UTF, Encoding::LATIN);
	}

	public function testDashCaseToCamelCase(): void
	{
		$this->assertEquals('one', Strings::dashCaseToCamelCase('one'));
		$this->assertEquals('oneTwo', Strings::dashCaseToCamelCase('one-two'));
	}

	public function testGetClassName(): void
	{
		$this->assertEquals('Strings', Strings::getClassName(Strings::class));
		$this->assertEquals('strings', Strings::getClassName(Strings::class, true));
	}

	public function testGetNamespace(): void
	{
		$this->assertEquals('Wavevision\Utils', Strings::getNamespace(Strings::class));
	}

	public function testRemoveAccentedChars(): void
	{
		$this->assertEquals('cabcde', Strings::removeAccentedChars('čábčďę'));
	}

	public function testRemoveBlankLines(): void
	{
		$this->assertEquals("tested\ntext", Strings::removeBlankLines("tested\n \ntext"));
	}

	public function testRemoveBOM(): void
	{
		$this->assertEquals('test', Strings::removeBOM("\xEF\xBB\xBFtest"));
	}

	public function testRemoveEmoji(): void
	{
		$this->assertEquals('text', Strings::removeEmoji('😀🏯text🗺🎵'));
	}

	public function testRemoveWhitespace(): void
	{
		$this->assertEquals('abcdef', Strings::removeWhitespace('abc   d e   f'));
	}

	public function testUtf2Win(): void
	{
		$this->expectException(IOException::class);
		Strings::utf2win("\x80");
	}

	public function testWin2Utf(): void
	{
		$this->assertEquals(Strings::utf2win('aei'), Strings::win2utf('aei'));
	}

}
