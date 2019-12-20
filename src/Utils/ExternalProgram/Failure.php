<?php declare(strict_types = 1);

namespace Wavevision\Utils\ExternalProgram;

use Exception;

class Failure extends Exception
{

	private Result $result;

	public function getResult(): Result
	{
		return $this->result;
	}

	/**
	 * @return static
	 */
	public function setResult(Result $result)
	{
		$this->result = $result;
		return $this;
	}

}
