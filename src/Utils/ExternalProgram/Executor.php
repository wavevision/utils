<?php declare(strict_types = 1);

namespace Wavevision\Utils\ExternalProgram;

use Nette\StaticClass;
use function exec;
use function sprintf;

class Executor
{

	use StaticClass;

	public static function executeUnchecked(string $command): Result
	{
		exec($command . ' 2>&1', $output, $returnValue);
		return new Result($command, $output, $returnValue);
	}

	public static function execute(string $command): Result
	{
		$result = self::executeUnchecked($command);
		if ($result->isSuccess()) {
			return $result;
		}
		throw (new Failure(
			sprintf(
				"Command '%s' failed with return value '%s' and output '%s'.",
				$result->getCommand(),
				$result->getReturnValue(),
				$result->getOutputAsString()
			),
			$result->getReturnValue()
		))->setResult($result);
	}

}
