<?php declare(strict_types = 1);

namespace Wavevision\UtilsTests;

use PHPUnit\Framework\TestCase;
use Wavevision\Utils\File;
use Wavevision\Utils\InvalidState;

class FileTest extends TestCase
{

	public function testInvalidState(): void
	{
		$this->expectException(InvalidState::class);
		File::open('42', 'r');
	}

	public function testGetCsv(): void
	{
		$file = File::open(__DIR__ . '/file.txt', 'r');
		$this->assertEquals(['hello'], $file->getCsv());
		$this->assertEquals(null, $file->getCsv());
		$file->close();
	}

	public function testPutCsv(): void
	{
		$file = File::open(__DIR__ . '/out.csv', 'w');
		$this->assertEquals(6, $file->putCsv(['hello']));
	}

	public function testPutCsvException(): void
	{
		$this->expectException(InvalidState::class);
		$file = File::open(__DIR__ . '/file.txt', 'r');
		$file->putCsv([]);
	}

	public function testRewind(): void
	{
		$file = File::open(__DIR__ . '/file.txt', 'r');
		$this->assertEquals('hello', $file->getContents());
		$this->assertEquals('', $file->getContents());
		$file->rewind();
		$this->assertEquals('hello', $file->getContents());
	}

	public function testGetContentsFail(): void
	{
		$file = File::open(__DIR__ . '/file.txt', 'r');
		$file->close();
		$this->expectException(InvalidState::class);
		$file->getContents();
	}

	public function testWrite(): void
	{
		$file = File::open(__DIR__ . '/out.txt', 'w');
		$this->assertEquals(4, $file->write('test'));
	}

	public function testWriteFail(): void
	{
		$file = File::open(__DIR__ . '/out.txt', 'w');
		$file->close();
		$this->expectException(InvalidState::class);
		$file->write('asd');
	}

}
