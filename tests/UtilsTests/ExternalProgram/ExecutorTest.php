<?php declare(strict_types = 1);

namespace Wavevision\UtilsTests\ExternalProgram;

use PHPUnit\Framework\TestCase;
use Wavevision\Utils\ExternalProgram\Executor;
use Wavevision\Utils\ExternalProgram\Failure;
use Wavevision\Utils\ExternalProgram\Result;
use function sprintf;

class ExecutorTest extends TestCase
{

	public function testExecuteUnchecked(): void
	{
		$this->assertFalse(Executor::executeUnchecked('66')->isSuccess());
	}

	public function testExecuteValidCommand(): void
	{
		$result = Executor::execute('echo hi');
		$this->assertInstanceOf(Result::class, $result);
		$this->assertEquals(['hi'], $result->getOutput());
		$this->assertEquals('echo hi', $result->getCommand());
	}

	public function testExecuteFailedCommand(): void
	{
		$command = __DIR__ . '/error.sh';
		try {
			Executor::execute($command);
		} catch (Failure $failure) {
			$this->assertEquals(
				"Command '$command' failed with return value '1' and output 'not cool\nerror output'.",
				$failure->getMessage()
			);
			$result = $failure->getResult();
			$this->assertEquals(1, $result->getReturnValue());
			return;
		}
		$this->fail(sprintf("'%s' exception was expected.", Failure::class));
	}

}
