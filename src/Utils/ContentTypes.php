<?php declare (strict_types = 1);

namespace Wavevision\Utils;

use Nette\StaticClass;

class ContentTypes
{

	use StaticClass;

	public const CSS = 'text/css';

	public const CSS_EXTENSION = 'css';

	public const JPEG = 'image/jpeg';

	public const JPEG_EXTENSION = 'jpg';

	public const JS = 'application/javascript';

	public const JS_EXTENSION = 'js';

	public const PDF = 'application/pdf';

	public const PDF_EXTENSION = 'pdf';

	public const PNG = 'image/png';

	public const PNG_EXTENSION = 'png';

	public const SVG = 'image/svg+xml';

	public const SVG_EXTENSION = 'svg';

	public const ZIP = 'application/zip';

	public const ZIP_EXTENSION = 'zip';

	public const CONTENT_TYPE_FILE_EXTENSIONS = [
		self::CSS => self::CSS_EXTENSION,
		self::JPEG => self::JPEG_EXTENSION,
		self::JS => self::JS_EXTENSION,
		self::PDF => self::PDF_EXTENSION,
		self::PNG => self::PNG_EXTENSION,
		self::SVG => self::SVG_EXTENSION,
		self::ZIP => self::ZIP_EXTENSION,
	];

	public static function getExtension(string $contentType, bool $withDelimiter = false): string
	{
		$ext = self::CONTENT_TYPE_FILE_EXTENSIONS[$contentType];
		return $withDelimiter ? ".$ext" : $ext;
	}

	public static function getFilename(string $file, string $contentType): string
	{
		return $file . self::getExtension($contentType, true);
	}

}
