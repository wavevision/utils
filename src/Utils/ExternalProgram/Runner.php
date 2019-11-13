<?php declare(strict_types = 1);

namespace Wavevision\Utils\ExternalProgram;

class Runner
{

	public static function execute(string $command): Result
	{
		$returnValue = 0;
		exec(
			$command . ' 2>&1',
			$output,
			$returnValue
		);
		$result = new Result($command, $output, $returnValue);
		if ($result->isSuccess()) {
			return $result;
		}
		throw (new Failure(
			sprintf(
				"External command '%s' failed with return value '%s' and output '%s'",
				$command,
				$returnValue,
				implode("\n", $output)
			),
			$returnValue
		))->setResult($result);
	}

}
