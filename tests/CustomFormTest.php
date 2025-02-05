<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class CustomFormTest extends TestCase
{
	public function testCustomFormBaseConstructor(): void
	{
		$field = [
			'id_field' => 1,
			'type_vars' => 'size=10,required',
			'text' => 'Test Field',
		];
		$value = 'Sample Value';

		$form = new CustomForm\Fields\Info($field, $value);

		$this->assertEquals($value, $form->getValue());
		$this->assertTrue($form->isRequired());
	}

	public function testCustomFormInfoSetHtml(): void
	{
		$field = [
			'id_field' => 1,
			'type_vars' => '',
			'text' => 'Test Info',
		];
		$value = 'Information';

		$form = new CustomForm\Fields\Info($field, $value);
		$form->setHtml();

		$this->assertEquals('Information', $form->getInputHtml());
	}

	public function testCustomFormCheckValidation(): void
	{
		$field = [
			'id_field' => 2,
			'type_vars' => 'required',
			'text' => 'Test Checkbox',
		];
		$value = ''; // Simulate an unchecked box

		$form = new CustomForm\Fields\Check($field, $value);

		// Mock the $txt array
		$txt = ['yes' => 'Yes', 'no' => 'No'];
		$GLOBALS['txt'] = $txt;

		$form->setHtml();
		$this->assertFalse($form->validate());
		$this->assertNotEmpty($form->getError());
	}

	/**
	 * @dataProvider checkboxProvider
	 */
	public function testCheckbox(string $type_vars, string $value, string $expected): void
	{
		$type = new CustomForm\Fields\Check(
			[
				'id_field' => '',
				'title' => '',
				'text' => '',
				'type' => '',
				'type_vars' => $type_vars,
			],
			$value,
		);
		$this->assertTrue($type->validate());
		$this->assertEmpty($type->getError());
		$this->assertEquals($expected, $type->getValue());
	}

	public function checkboxProvider(): array
	{
		return [
			['default=on', '', 'yes'],
			['default=on', 'boop', 'yes'],
			['default=', '', 'no'],
		];
	}

	/**
	 * @dataProvider selectProvider
	 */
	public function testselect(string $type_vars, string $value, string $expected): void
	{
		$type = new CustomForm\Fields\Select(
			[
				'id_field' => '',
				'title' => '',
				'text' => '',
				'type' => '',
				'type_vars' => $type_vars,
			],
			$value,
		);
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
			['gold,silver,bronze,default=silver', 'titanium', 'silver'],
		];
	}

	public function testCustomFormSelectSetHtml(): void
	{
		$field = [
			'id_field' => 3,
			'type_vars' => 'Option1,Option2,Option3',
			'text' => 'Test Select',
		];
		$value = 'Option2';

		$form = new CustomForm\Fields\Select($field, $value);
		$form->setHtml();

		$this->assertStringContainsString('<select', $form->getInputHtml());
		$this->assertEquals('Option2', $form->getOutputHtml());
	}

	public function testCustomFormRadioSetHtml(): void
	{
		$field = [
			'id_field' => 4,
			'type_vars' => 'Yes,No,Maybe',
			'text' => 'Test Radio',
		];
		$value = 'Yes';

		$form = new CustomForm\Fields\Radio($field, $value);
		$form->setHtml();

		$this->assertStringContainsString('<input type="radio"', $form->getInputHtml());
		$this->assertEquals('Yes', $form->getOutputHtml());
	}

	public function testCustomFormTextSetHtml(): void
	{
		$field = [
			'id_field' => 5,
			'type_vars' => 'size=20,default=Sample Text',
			'text' => 'Test Text',
		];
		$value = '';

		$form = new CustomForm\Fields\Text($field, $value);
		$form->setHtml();

		$this->assertStringContainsString('<input type="text"', $form->getInputHtml());
		$this->assertEquals('', $form->getOutputHtml());
	}

	public function testCustomFormFieldMaskEmail(): void
	{
		$field = [
			'id_field' => 6,
			'text' => 'Test Email',
		];
		$value = 'invalid-email';

		$mask = new CustomForm\Fields\Masks\Email($value, $field);

		$this->assertFalse($mask->validate());
		$this->assertNotEmpty($mask->getError());
	}

	public function testCustomFormFieldMaskRegex(): void
	{
		$field = [
			'id_field' => 7,
			'text' => 'Test Regex',
			'regex' => '/^[A-Za-z]+$/',
		];
		$value = 'Invalid123';

		$mask = new CustomForm\Fields\Masks\Regex($value, $field);

		$this->assertFalse($mask->validate());
		$this->assertNotEmpty($mask->getError());
	}

	public function testCustomFormFieldMaskNumber(): void
	{
		$field = [
			'id_field' => 8,
			'text' => 'Test Number',
		];
		$value = '12345';

		$mask = new CustomForm\Fields\Masks\Number($value, $field);

		$this->assertTrue($mask->validate());
		$this->assertEmpty($mask->getError());
	}
}
