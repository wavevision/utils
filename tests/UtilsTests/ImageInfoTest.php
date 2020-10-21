<?php declare(strict_types = 1);

namespace Wavevision\UtilsTests;

use Nette\InvalidStateException;
use PHPUnit\Framework\TestCase;
use Wavevision\Utils\ImageInfo;
use Wavevision\Utils\Path;
use function sprintf;

class ImageInfoTest extends TestCase
{

	public function testCreateInvalid(): void
	{
		$this->expectException(InvalidStateException::class);
		$this->expectExceptionMessage(sprintf("Filepath '%s' is not valid image.", __FILE__));
		ImageInfo::create(__FILE__);
	}

	public function testCreateValid(): void
	{
		$info = ImageInfo::create(Path::join(__DIR__, 'files', 'image.png'));
		$this->assertEquals(921, $info->getWidth());
		$this->assertEquals(589, $info->getHeight());
		$this->assertEquals('image/png', $info->getContentType());
	}

}
