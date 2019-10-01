<?php declare (strict_types = 1);

namespace Wavevision\UtilsTests;

use org\bovigo\vfs\vfsStream as fs;
use PHPUnit\Framework\TestCase;
use Wavevision\Utils\Tokenizer;

/**
 * @covers \Wavevision\Utils\Tokenizer
 */
class TokenizerTest extends TestCase
{

	public function testGetClassNameFromFile(): void
	{
		$this->assertEquals('Two', (new Tokenizer())->getClassNameFromFile($this->getFile('<?php class Two {}')));
	}

	public function testGetInterfaceNameFromFile(): void
	{
		$this->assertEquals(null, (new Tokenizer())->getClassNameFromFile($this->getFile('<?php interface Two {}')));
	}

	public function testGetStructureNameFromFile(): void
	{
		$this->assertEquals(
			['Two', T_INTERFACE],
			(new Tokenizer())->getStructureNameFromFile(
				$this->getFile('<?php interface Two {}'),
				[T_INTERFACE, T_CLASS]
			)
		);
	}

	public function testGetClassNameFromFileWithNamespace(): void
	{
		$this->assertEquals(
			'One\Two',
			(new Tokenizer())->getClassNameFromFile(
				$this->getFile(
					'<?php 
			namespace One;
			class Two {}
			'
				)
			)
		);
	}

	public function testGetClassNameFrom(): void
	{
		$this->assertEquals(null, (new Tokenizer())->getClassNameFromFile($this->getFile('<?php ')));
	}

	private function getFile(string $content): string
	{
		fs::setup('r');
		$file = fs::url('r/class.php');
		file_put_contents($file, $content);
		return $file;
	}
}
