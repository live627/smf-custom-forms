<?php

declare(strict_types=1);

use CustomForm\FieldFactory;
use PHPUnit\Framework\TestCase;

class CustomFormTest extends TestCase
{
	public function testCustomFormBaseConstructor(): void
	{
		$field = FieldFactory::create(
			id: 1,
			text: 'Test Field',
			type_vars: 'size=10,required',
		);
		$value = 'Sample Value';

		$form = new CustomForm\Fields\Info($field, $value);

		$this->assertEquals($value, $form->getValue());
		$this->assertTrue($form->isRequired());
	}

	public function testCustomFormInfoSetHtml(): void
	{
		$field = FieldFactory::create(
			id: 1,
			text: 'Test Info',
		);
		$value = 'Information';

		$form = new CustomForm\Fields\Info($field, $value);
		$form->setHtml();

		$this->assertEquals('Information', $form->getInputHtml());
	}

	public function testCustomFormCheckValidation(): void
	{
		$field = FieldFactory::create(
			id: 2,
			text: 'Test Checkbox',
			type_vars: 'required',
		);
		$value = ''; // Simulate an unchecked box

		$form = new CustomForm\Fields\Check($field, $value);
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
			FieldFactory::create(
				type_vars: $type_vars,
			),
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
	public function testSelect(string $type_vars, string $value, string $expected): void
	{
		$type = new CustomForm\Fields\Select(
			FieldFactory::create(
				type_vars: $type_vars,
			),
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

	/**
	 * @dataProvider formProvider
	 */
	public function testCustomFormSetHtml($field, $expectedHtml, $expectedOutput): void
	{
		$field->obj->setHtml();

		$this->assertStringContainsString($expectedHtml, $field->obj->getInputHtml());
		$this->assertEquals($expectedOutput, $field->obj->getOutputHtml());
	}

	public function formProvider(): array
	{
		return [
			'selectbox' => [
				FieldFactory::create(id: 3, text: 'Test Select', type: 'select', type_vars: 'Option1,Option2,Option3,default=Option2'),
				'<select',
				'Option2',
			],
			'radio' => [
				FieldFactory::create(id: 4, text: 'Test Radio', type: 'radio', type_vars: 'Yes,No,Maybe,default=Maybe'),
				'<input type="radio"',
				'Maybe',
			],
			'checkbox' => [
				FieldFactory::create(id: 4, text: 'Test Radio', type: 'check', type_vars: 'default=on'),
				'<input type="checkbox"',
				'{{ yes }}',
			],
			'textbox' => [
				FieldFactory::create(id: 5, text: 'Test Text', type: 'text', type_vars: 'size=4,nobbc,default=Sample Text'),
				'<input type="text"',
				'[nobbc]Samp[/nobbc]',
			],
		];
	}

	/*
	 * @dataProvider maskProvider
	 */
	//~ public function testCustomFormFieldMask($field, $value, $expectedValid, $expectedError): void
	//~ {
		//~ $mask = new CustomForm\Fields\Masks\Email($value, $field);

		//~ $this->assertEquals($expectedValid, $mask->validate());
		//~ $this->assertEquals($expectedError, $mask->getError());
	//~ }

	//~ public function maskProvider(): array
	//~ {
		//~ return [
			//~ [
				//~ FieldFactory::create(id: 6, text: 'Test Email'),
				//~ 'invalid-email',
				//~ false,
				//~ 'Some error message'
			//~ ],
			//~ [
				//~ FieldFactory::create(id: 7, text: 'Test Regex', regex: '/^[A-Za-z]+$/'),
				//~ 'Invalid123',
				//~ false,
				//~ 'Some error message'
			//~ ],
			//~ [
				//~ FieldFactory::create(id: 8, text: 'Test Number'),
				//~ '12345',
				//~ true,
				//~ ''
			//~ ]
		//~ ];
	//~ }
}