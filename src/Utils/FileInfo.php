<?php declare(strict_types = 1);

namespace Wavevision\Utils;

use finfo;
use Nette\IOException;
use Nette\SmartObject;
use Nette\Utils\DateTime;
use function basename;
use function dirname;
use function filemtime;
use function filesize;
use function is_file;
use function pathinfo;
use function sprintf;
use function str_replace;
use const FILEINFO_MIME_TYPE;
use const PATHINFO_EXTENSION;

class FileInfo
{

	use SmartObject;

	private string $pathName;

	private string $baseName;

	private string $dirName;

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
		$this->dirName = dirname($pathName);
		$this->extension = pathinfo($pathName, PATHINFO_EXTENSION);
		$this->contentType = (string)(new finfo(FILEINFO_MIME_TYPE))->file($pathName);
		$this->size = (int)filesize($pathName);
		$this->mtime = (new DateTime())->setTimestamp((int)filemtime($pathName));
	}

	public function getPathName(): string
	{
		return $this->pathName;
	}

	public function getBaseName(bool $withoutExtension = false): string
	{
		if ($withoutExtension) {
			return str_replace($this->getExtension(true), '', $this->baseName);
		}
		return $this->baseName;
	}

	public function getDirName(): string
	{
		return $this->dirName;
	}

	public function getExtension(bool $withDot = false): string
	{
		return $withDot ? '.' . $this->extension : $this->extension;
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
