<?php declare(strict_types = 1);

namespace Wavevision\Utils\Tokenizer;

use Nette\SmartObject;

class TokenizeResult
{

	use SmartObject;

	/**
	 * @var int
	 */
	private $token;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string|null
	 */
	private $namespace;

	public function __construct(int $token, string $name, ?string $namespace)
	{
		$this->token = $token;
		$this->name = $name;
		$this->namespace = $namespace;
	}

	public function getToken(): int
	{
		return $this->token;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getNamespace(): ?string
	{
		return $this->namespace;
	}

	public function getFullyQualifiedName(): string
	{
		return $this->getNamespace() === null ? $this->getName() : $this->getNamespace() . '\\' . $this->getName();
	}

}
