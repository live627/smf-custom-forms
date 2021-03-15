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

	/**
	 * @dataProvider checkboxProvider
	 */
	public function testCheckbox(string $type_vars, string $value, string $expected): void
	{
		$type = new CustomForm_check(
			[
				'id_field' => '',
				'title' => '',
				'text' => '',
				'type' => '',
				'type_vars' => $type_vars
			],
			$value
		);
		$type->setOptions();
		$this->assertTrue($type->validate());
		$this->assertEmpty($type->getError());
		$this->assertEquals($expected, $type->getValue());
	}

	/**
	 * @return string[][]
	 */
	public function checkboxProvider(): array
	{
		return [
			['default=on', '', 'yes'],
			['default=on', 'boop', 'yes'],
			['default=', '', 'no']
		];
	}

	/**
	 * @dataProvider selectProvider
	 */
	public function testselect(string $type_vars, string $value, string $expected): void
	{
		$type = new CustomForm_select(
			[
				'id_field' => '',
				'title' => '',
				'text' => '',
				'type' => '',
				'type_vars' => $type_vars
			],
			$value
		);
		$type->setOptions();
		$this->assertTrue($type->validate());
		$this->assertEmpty($type->getError());
		$this->assertEquals($expected, $type->getValue());
	}

	/**
	 * @return string[][]
	 */
	public function selectProvider(): array
	{
		return [
			['gold,silver,bronze,default=silver', '', 'silver'],
			['gold,silver,bronze,default=', 'silver', 'silver'],
			['gold,silver,bronze,default=silver', 'titanium', 'silver']
		];
	}

}
