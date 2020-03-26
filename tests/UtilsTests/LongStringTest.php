<?php declare (strict_types = 1);

namespace Wavevision\UtilsTests;

use PHPUnit\Framework\TestCase;
use Wavevision\Utils\LongString;

class LongStringTest extends TestCase
{

	public function testLongString(): void
	{
		$longString = new LongString(
			'This is a very long long string,',
			'from a galaxy far far away.',
			'Anyway, I wanted to show you',
			'how easily we can solve long lines exceeding coding standard limit.',
			'Makes any sense?'
		);
		$this->assertSame((string)$longString, $longString->string());
		$this->assertStringContainsString("\n", $longString->string("\n"));
		$this->assertContains(
			'Thank you.',
			$longString
				->addStrings('Thank you.', 'Good bye!')
				->getStrings()
		);
	}

	public function testGlue(): void
	{
		$this->assertSame(
			'.',
			(new LongString(''))
				->setGlue('.')
				->getGlue()
		);
	}

}
