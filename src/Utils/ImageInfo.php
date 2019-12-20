<?php declare(strict_types = 1);

namespace Wavevision\Utils;

use Nette\InvalidStateException;

class ImageInfo
{

	private int $width;

	private int $height;

	private string $contentType;

	public static function create(string $pathname): self
	{
		$info = getimagesize($pathname);
		if ($info === false) {
			throw new InvalidStateException("Filepath '$pathname' is not valid image.");
		}
		return new self($info[0], $info[1], $info['mime']);
	}

	public function getContentType(): string
	{
		return $this->contentType;
	}

	public function getWidth(): int
	{
		return $this->width;
	}

	public function getHeight(): int
	{
		return $this->height;
	}

	private function __construct(int $width, int $height, string $contentType)
	{
		$this->width = $width;
		$this->height = $height;
		$this->contentType = $contentType;
	}

}
