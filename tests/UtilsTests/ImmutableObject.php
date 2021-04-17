<?php declare (strict_types = 1);

namespace Wavevision\UtilsTests;

use Wavevision\Utils;

class ImmutableObject
{

	use Utils\ImmutableObject;

	private string $prop;

	public function __construct(string $prop)
	{
		$this->prop = $prop;
	}

	public function getProp(): string
	{
		return $this->prop;
	}

	public function withProp(string $prop): self
	{
		return $this->withMutation('prop', $prop);
	}

}
