<?php declare (strict_types = 1);

namespace Wavevision\UtilsTests;

use org\bovigo\vfs\vfsStream as fs;
use PHPUnit\Framework\TestCase;
use Wavevision\Utils\Tokenizer\Tokenizer;

class TokenizerTest extends TestCase
{

	public function testGetStructureNameFromFile(): void
	{
		$class = (new Tokenizer())->getStructureNameFromFile($this->getFile('<?php class Two {}'), [T_CLASS]);
		$this->assertEquals('Two', $class->getName());
		$interface = (new Tokenizer())->getStructureNameFromFile(
			$this->getFile('<?php interface Two {}'),
			[T_INTERFACE]
		);
		$this->assertEquals('Two', $interface->getName());
		$this->assertEquals(
			null,
			(new Tokenizer())->getStructureNameFromFile($this->getFile('<?php '), [T_CLASS])
		);
		$namespace = (new Tokenizer())->getStructureNameFromFile(
			$this->getFile(
				'<?php 
			namespace One;
			class Two {}
			'
			),
			[T_CLASS]
		);
		$this->assertEquals('One', $namespace->getNamespace());
		$this->assertEquals('One\Two', $namespace->getFullyQualifiedName());
		$this->assertEquals(T_CLASS, $namespace->getToken());
	}

	private function getFile(string $content): string
	{
		fs::setup('r');
		$file = fs::url('r/class.php');
		file_put_contents($file, $content);
		return $file;
	}

}
