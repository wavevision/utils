<?php declare(strict_types = 1);

namespace Wavevision\UtilsTests;

use org\bovigo\vfs\vfsStream as fs;
use PHPStan\Testing\TestCase;
use Wavevision\Utils\Maintenance;

class MaintenanceTest extends TestCase
{

	public function test(): void
	{
		fs::setup('r');
		Maintenance::init(fs::url('r'));
		$this->assertFalse(Maintenance::isActive());
		Maintenance::enable();
		$this->assertTrue(Maintenance::isActive());
		Maintenance::disable();
		$this->assertFalse(Maintenance::isActive());
	}

}
