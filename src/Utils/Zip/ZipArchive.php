<?php declare (strict_types = 1);

namespace Wavevision\Utils\Zip;

use Nette\FileNotFoundException;
use Nette\SmartObject;
use SplFileInfo;
use Wavevision\Utils\FileInfo;
use Wavevision\Utils\Finder;
use Wavevision\Utils\Path;
use ZipArchive as Zip;
use function basename;
use function file_exists;
use function is_dir;
use function is_file;

class ZipArchive
{

	use SmartObject;

	private const INITIAL_DEPTH = 1;

	/**
	 * @var ZipArchiveItem[]
	 */
	private array $items;

	private string $path;

	private Zip $zip;

	public function __construct(string $path, ZipArchiveItem ...$items)
	{
		$this->items = $items;
		$this->path = $path;
		$this->zip = new Zip();
	}

	public function addItem(ZipArchiveItem $item): self
	{
		$this->items[] = $item;
		return $this;
	}

	public function close(): self
	{
		$this->zip->close();
		return $this;
	}

	public function compress(): self
	{
		foreach ($this->items as $item) {
			$path = $this->getItemPath($item);
			if (is_dir($path)) {
				$this->addDir($item, self::INITIAL_DEPTH);
			} else {
				$this->zip->addFile($path, $item->getName());
			}
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

	/**
	 * @param string[] $parents
	 */
	private function addDir(ZipArchiveItem $item, int $depth, array $parents = []): void
	{
		$deep = $depth > self::INITIAL_DEPTH;
		$dir = $item->getName();
		$parents = [...$parents, $dir];
		$this->zip->addEmptyDir($deep ? Path::join(...$parents) : $dir);
		/** @var SplFileInfo $subItem */
		foreach (Finder::find('*')->in($item->getPath()) as $subItem) {
			$path = $subItem->getPathname();
			if ($subItem->isDir()) {
				$this->addDir(new ZipArchiveItem($path), $depth + 1, $parents);
			} else {
				$name = $subItem->getFilename();
				$this->zip->addFile($path, $deep ? Path::join(...[...$parents, $name]) : $name);
			}
		}
	}

	private function getExtractDir(): string
	{
		$fileInfo = new FileInfo($this->getPath());
		return Path::join($fileInfo->getDirName(), $fileInfo->getBaseName(true));
	}

	private function getItemPath(ZipArchiveItem $item): string
	{
		$path = $item->getPath();
		if (!file_exists($path)) {
			throw new FileNotFoundException("Zip archive item '$path' not found.");
		}
		return $path;
	}

}
