<?php declare (strict_types = 1);

namespace Wavevision\UtilsTests;

use ArrayIterator;
use Iterator;
use org\bovigo\vfs\vfsStream as fs;
use PHPUnit\Framework\TestCase;
use SplFileInfo;
use Wavevision\Utils\Finder;
use function file_put_contents;
use function time;
use function touch;

/**
 * @covers \Wavevision\Utils\Finder
 */
class FinderTest extends TestCase
{

	public function testGetIterator(): void
	{
		$finder = $this->getFinder();
		$this->assertInstanceOf(Iterator::class, $finder->getIterator());
		$finder->setSort(
			function (): int {
				return 0;
			}
		);
		$this->assertInstanceOf(ArrayIterator::class, $finder->getIterator());
	}

	public function testSortByMTime(): void
	{
		$files = [];
		/** @var SplFileInfo $file */
		foreach ($this->getFinder()->sortByMTime() as $file) {
			$files[] = $file->getFilename();
		}
		/** @var SplFileInfo $file */
		foreach ($this->getFinder()->sortByMTime(Finder::ORDER_ASC) as $file) {
			$files[] = $file->getFilename();
		}
		$this->assertEquals(['f3.txt', 'f2.txt', 'f1.txt', 'f1.txt', 'f2.txt', 'f3.txt'], $files);
	}

	public function testSortByName(): void
	{
		$files = [];
		/** @var SplFileInfo $file */
		foreach ($this->getFinder(['some-file', 'anotherFile', 'čeština'])->sortByName() as $file) {
			$files[] = $file->getFilename();
		}
		/** @var SplFileInfo $file */
		foreach ($this->getFinder(['ahoj', 'Test', 'ČekDis'])->sortByName(
			Finder::ORDER_DESC,
			Finder::CASE_SENSITIVE
		) as $file) {
			$files[] = $file->getFilename();
		}
		$this->assertEquals(
			['anotherFile.txt', 'čeština.txt', 'some-file.txt', 'ahoj.txt', 'Test.txt', 'ČekDis.txt'],
			$files
		);
	}

	/**
	 * @param array<string> $files
	 */
	private function getFinder(array $files = ['f1', 'f2', 'f3']): Finder
	{
		return Finder::find('*.txt')->from($this->getDir($files));
	}

	/**
	 * @param array<string> $files
	 */
	private function getDir(array $files): string
	{
		$dir = fs::setup('r');
		$time = time();
		foreach ($files as $name) {
			$file = fs::url("r/$name.txt");
			file_put_contents($file, '');
			touch($file, $time);
			$time++;
		}
		return $dir->url();
	}

}
