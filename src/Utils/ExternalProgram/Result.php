<?php declare(strict_types = 1);

namespace Wavevision\Utils\ExternalProgram;

use Nette\SmartObject;
use function implode;

class Result
{

	use SmartObject;

	private string $command;

	/**
	 * @var array<string>
	 */
	private array $output;

	private int $returnValue;

	/**
	 * @param array<string> $output
	 */
	public function __construct(string $command, array $output, int $returnValue)
	{
		$this->command = $command;
		$this->output = $output;
		$this->returnValue = $returnValue;
	}

	public function getCommand(): string
	{
		return $this->command;
	}

	/**
	 * @return array<string>
	 */
	public function getOutput(): array
	{
		return $this->output;
	}

	public function getOutputAsString(): string
	{
		return implode("\n", $this->output);
	}

	public function getReturnValue(): int
	{
		return $this->returnValue;
	}

	public function isSuccess(): bool
	{
		return $this->getReturnValue() === 0;
	}

}
