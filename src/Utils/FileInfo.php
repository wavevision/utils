<?php declare(strict_types = 1);

namespace Wavevision\Utils;

use finfo;
use Nette\IOException;
use Nette\SmartObject;
use Nette\Utils\DateTime;

class FileInfo
{

	use SmartObject;

	private string $pathName;

	private string $baseName;

	private string $extension;

	private string $contentType;

	private int $size;

	private DateTime $mtime;

	public function __construct(string $pathName)
	{
		if (!is_file($pathName)) {
			throw new IOException(sprintf("File '%s' not found.", $pathName));
		}
		$this->pathName = $pathName;
		$this->baseName = basename($pathName);
		$this->extension = pathinfo($pathName, PATHINFO_EXTENSION);
		$this->contentType = (string)(new finfo(FILEINFO_MIME_TYPE))->file($pathName);
		$this->size = (int)filesize($pathName);
		$this->mtime = (new DateTime())->setTimestamp((int)filemtime($pathName));
	}

	public function getPathName(): string
	{
		return $this->pathName;
	}

	public function getBaseName(): string
	{
		return $this->baseName;
	}

	public function getExtension(): string
	{
		return $this->extension;
	}

	public function getContentType(): string
	{
		return $this->contentType;
	}

	public function getSize(): int
	{
		return $this->size;
	}

	public function getMtime(): DateTime
	{
		return $this->mtime;
	}

}