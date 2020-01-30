<?php declare (strict_types = 1);

namespace Wavevision\UtilsTests\Tokenizer;

use org\bovigo\vfs\vfsStream as fs;
use PHPUnit\Framework\TestCase;
use Wavevision\Utils\Tokenizer\Tokenizer;

class TokenizerTest extends TestCase
{

	public function testClass(): void
	{
		$class = (new Tokenizer())->getStructureNameFromFile($this->getFile('<?php class Two {}'), [T_CLASS]);
		$this->assertEquals('Two', $class->getName());
	}

	public function testInterface(): void
	{
		$interface = (new Tokenizer())->getStructureNameFromFile(
			$this->getFile('<?php interface Two {}'),
			[T_INTERFACE]
		);
		$this->assertEquals('Two', $interface->getName());
	}

	public function testTrait(): void
	{
		$content = '<?php trait Two { function f1(){ Two:class; } function f2(){}}';
		$class = (new Tokenizer())->getStructureNameFromFile($this->getFile($content), [T_CLASS]);
		$this->assertNull($class);
		$trait = (new Tokenizer())->getStructureNameFromFile($this->getFile($content), [T_TRAIT]);
		$this->assertEquals('Two', $trait->getName());
	}

	public function testClassWithNamespace(): void
	{
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

	public function testGetStructureNameFromFileInvalid(): void
	{
		$this->assertNoStructure('<?php function (){};');
		$this->assertNoStructure('<?php X::getByType(Application::class)->run();');
		$this->assertNoStructure('<?php X::getByType(Application::class )->run();');
	}

	private function assertNoStructure(string $php): void
	{
		$this->assertEquals(
			null,
			(new Tokenizer())->getStructureNameFromFile($this->getFile($php), [T_CLASS])
		);
	}

	private function getFile(string $content): string
	{
		fs::setup('r');
		$file = fs::url('r/class.php');
		file_put_contents($file, $content);
		return $file;
	}

}
