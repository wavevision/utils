<?php declare (strict_types = 1);

namespace Wavevision\UtilsTests\ZipArchive;

use Nette\FileNotFoundException;
use PHPUnit\Framework\TestCase;
use Wavevision\Utils\Zip\ZipArchive;
use Wavevision\Utils\Zip\ZipArchiveFile;

class ZipArchiveTest extends TestCase
{

	public function testZipArchive(): void
	{
		$path = __DIR__ . '/test.zip';
		$zip = new ZipArchive($path, new ZipArchiveFile(__DIR__ . '/../file.txt'));
		$this->assertEquals('test.zip', $zip->getName());
		$zip->write()->compress();
		$this->assertFileExists($path);
		$zip->read()->extract();
		$this->assertDirectoryExists(__DIR__ . '/test');
		$zip->addFile(new ZipArchiveFile(''));
		$this->expectExceptionObject(new FileNotFoundException("Zip archive file '' not found."));
		$zip->write()->compress();
	}

}
