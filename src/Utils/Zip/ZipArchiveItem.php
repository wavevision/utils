<?php declare (strict_types = 1);

namespace Wavevision\Utils\Zip;

use Nette\SmartObject;
use function basename;

final class ZipArchiveItem
{

	use SmartObject;

	private string $name;

	private string $path;

	public function __construct(string $path, ?string $name = null)
	{
		$this->name = $name ?? basename($path);
		$this->path = $path;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getPath(): string
	{
		return $this->path;
	}

}
