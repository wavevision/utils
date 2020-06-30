<?php declare (strict_types = 1);

namespace Wavevision\Utils\Zip;

use Nette\FileNotFoundException;
use Nette\SmartObject;
use Wavevision\Utils\FileInfo;
use Wavevision\Utils\Path;
use ZipArchive as Zip;

class ZipArchive
{

	use SmartObject;

	/**
	 * @var ZipArchiveFile[]
	 */
	private array $files;

	private string $path;

	private Zip $zip;

	public function __construct(string $path, ZipArchiveFile ...$files)
	{
		$this->files = $files;
		$this->path = $path;
		$this->zip = new Zip();
	}

	public function addFile(ZipArchiveFile $file): self
	{
		$this->files[] = $file;
		return $this;
	}

	public function close(): self
	{
		$this->zip->close();
		return $this;
	}

	public function compress(): self
	{
		foreach ($this->files as $file) {
			$this->zip->addFile($this->getFilePath($file), $file->getName());
		}
		return $this->close();
	}

	public function extract(?string $dir = null): self
	{
		$this->zip->extractTo($dir ?? $this->getExtractDir());
		return $this;
	}

	public function getName(): string
	{
		return basename($this->getPath());
	}

	public function getPath(): string
	{
		return $this->path;
	}

	public function read(): self
	{
		$this->zip->open($this->getPath());
		return $this;
	}

	public function write(): self
	{
		$flag = is_file($this->getPath()) ? Zip::OVERWRITE : Zip::CREATE;
		$this->zip->open($this->getPath(), $flag);
		return $this;
	}

	private function getExtractDir(): string
	{
		$fileInfo = new FileInfo($this->getPath());
		return Path::join($fileInfo->getDirName(), $fileInfo->getBaseName(true));
	}

	private function getFilePath(ZipArchiveFile $file): string
	{
		$path = $file->getPath();
		if (!file_exists($path)) {
			throw new FileNotFoundException("Zip archive file '$path' not found.");
		}
		return $path;
	}

}
