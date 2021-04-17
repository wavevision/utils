<?php declare (strict_types = 1);

namespace Wavevision\Utils;

use Nette\SmartObject;

trait ImmutableObject
{

	use SmartObject;

	/**
	 * @param mixed $value
	 * @return static
	 */
	final protected function withMutation(string $property, $value)
	{
		$object = clone $this;
		$object->$property = $value;
		return $object;
	}

}
