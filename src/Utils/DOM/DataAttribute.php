<?php declare (strict_types = 1);

namespace Wavevision\Utils\DOM;

use Nette\SmartObject;

final class DataAttribute
{

	use SmartObject;

	private string $currentName;

	private string $currentValue;

	public function __construct(string $name, ?string $prefix = null)
	{
		$this->currentName = $prefix ? "data-$prefix-$name" : "data-$name";
		$this->currentValue = '';
	}

	public function __toString(): string
	{
		return $this->asString();
	}

	/**
	 * @param mixed $value
	 * @return array<string, string>
	 */
	public function asArray($value = null): array
	{
		$this->value($value);
		return [$this->currentName => $this->currentValue];
	}

	/**
	 * @param mixed $value
	 */
	public function asString($value = null): string
	{
		$this->value($value);
		return sprintf('%s="%s"', $this->currentName, $this->currentValue);
	}

	public function name(): string
	{
		return $this->currentName;
	}

	/**
	 * @param mixed $value
	 */
	public function value($value = null): string
	{
		if ($value !== null) {
			$this->currentValue = (string)$value;
		}
		return $this->currentValue;
	}

}
