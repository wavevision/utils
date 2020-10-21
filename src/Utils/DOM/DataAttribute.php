<?php declare (strict_types = 1);

namespace Wavevision\Utils\DOM;

use JsonSerializable;
use Nette\SmartObject;
use Nette\Utils\Html;
use Wavevision\Utils\Strings;
use function sprintf;

final class DataAttribute implements JsonSerializable
{

	use SmartObject;

	private string $currentName;

	private string $currentValue;

	public function __construct(string $name, ?string $prefix = null)
	{
		$this->currentName = Strings::camelCaseToDashCase($prefix ? "data-$prefix-$name" : "data-$name");
		$this->currentValue = '';
	}

	/**
	 * @return array<string, string>
	 */
	public function jsonSerialize(): array
	{
		return $this->asArray();
	}

	/**
	 * @param mixed $value
	 * @return array<string, string>
	 */
	public function asArray($value = null): array
	{
		return [$this->currentName => $this->value($value)];
	}

	/**
	 * @param mixed $value
	 * @return array<string>
	 */
	public function asTuple($value = null): array
	{
		return [$this->currentName, $this->value($value)];
	}

	/**
	 * @param Html<mixed> $element
	 * @param mixed $value
	 * @return Html<mixed>
	 */
	public function assign(Html $element, $value = null): Html
	{
		$element->setAttribute($this->currentName, $this->value($value));
		return $element;
	}

	/**
	 * @param mixed $value
	 */
	public function asString($value = null): string
	{
		return sprintf('%s="%s"', $this->currentName, $this->value($value));
	}

	/**
	 * @param Html<mixed> $element
	 */
	public function get(Html $element): ?string
	{
		return $element->getAttribute($this->currentName);
	}

	/**
	 * @param Html<mixed> $element
	 */
	public function has(Html $element): bool
	{
		return $this->get($element) !== null;
	}

	public function name(): string
	{
		return $this->currentName;
	}

	/**
	 * @param Html<mixed> $element
	 * @return Html<mixed>
	 */
	public function remove(Html $element): Html
	{
		$element->removeAttribute($this->currentName);
		return $element;
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

	public function __toString(): string
	{
		return $this->asString();
	}

}
