<?php declare (strict_types = 1);

namespace Wavevision\UtilsTests\ZipArchive;

use Nette\FileNotFoundException;
use PHPUnit\Framework\TestCase;
use Wavevision\Utils\Zip\ZipArchive;
use Wavevision\Utils\Zip\ZipArchiveItem;

class ZipArchiveTest extends TestCase
{

	public function testZipArchive(): void
	{
		$path = __DIR__ . '/output.zip';
		$zip = new ZipArchive(
			$path,
			new ZipArchiveItem(__DIR__ . '/input/dir'),
			new ZipArchiveItem(__DIR__ . '/input/file.txt')
		);
		$this->assertEquals('output.zip', $zip->getName());
		$zip->write()->compress();
		$this->assertFileExists($path);
		$zip->read()->extract();
		$this->assertDirectoryExists(__DIR__ . '/output');
		$zip->addItem(new ZipArchiveItem(''));
		$this->expectExceptionObject(new FileNotFoundException("Zip archive item '' not found."));
		$zip->write()->compress();
	}

}
