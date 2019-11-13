<?php declare(strict_types = 1);

namespace Wavevision\UtilsTests\ExternalProgram;

use PHPUnit\Framework\TestCase;
use Wavevision\Utils\ExternalProgram\Failure;
use Wavevision\Utils\ExternalProgram\Result;
use Wavevision\Utils\ExternalProgram\Runner;

class RunnerTest extends TestCase
{

	public function testExecuteValidCommand(): void
	{
		$result = Runner::execute('echo hi');
		$this->assertInstanceOf(Result::class, $result);
		$this->assertEquals(['hi'], $result->getOutput());
		$this->assertEquals('echo hi', $result->getCommand());
	}

	public function testExecuteFailed(): void
	{
		$this->expectException(Failure::class);
		$this->expectExceptionMessage("External command '66' failed with return value");
		Runner::execute('66');
	}

}
