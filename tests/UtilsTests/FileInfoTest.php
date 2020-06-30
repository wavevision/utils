<?php declare(strict_types = 1);

namespace Wavevision\UtilsTests;

use Nette\IOException;
use Nette\Utils\DateTime;
use PHPUnit\Framework\TestCase;
use Wavevision\Utils\FileInfo;

class FileInfoTest extends TestCase
{

	public function testFileInfo(): void
	{
		$file = __DIR__ . '/file.txt';
		$fileInfo = new FileInfo($file);
		$this->assertEquals($file, $fileInfo->getPathName());
		$this->assertEquals('file.txt', $fileInfo->getBaseName());
		$this->assertEquals('file', $fileInfo->getBaseName(true));
		$this->assertEquals(__DIR__, $fileInfo->getDirName());
		$this->assertEquals('txt', $fileInfo->getExtension());
		$this->assertEquals('.txt', $fileInfo->getExtension(true));
		$this->assertEquals(5, $fileInfo->getSize());
		$this->assertEquals('text/plain', $fileInfo->getContentType());
		$this->assertInstanceOf(DateTime::class, $fileInfo->getMtime());
	}

	public function testNotFound(): void
	{
		$this->expectException(IOException::class);
		$this->expectExceptionMessage("File '42' not found.");
		new FileInfo('42');
	}

}
