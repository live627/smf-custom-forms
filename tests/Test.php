<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class Test extends TestCase
{
	public function testFindClasses(): void
	{
		$classes = iterator_to_array(customform_list_classes());
		$this->assertCount(5, $classes);
		$this->assertContains('check', $classes);
		$this->assertContains('select', $classes);
		$this->assertContains('radio', $classes);
		$this->assertContains('text', $classes);
		$this->assertContains('textarea', $classes);
	}
}
