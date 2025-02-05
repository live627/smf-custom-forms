<?php

declare(strict_types=1);

use CustomForm\Field;
use PHPUnit\Framework\TestCase;
use SMF\Db\DatabaseApi as Db;

// Mocking the dependencies
class MockDb
{
	public static $db;

	public static function setUp()
	{
		self::$db = new class {
			public function query($queryString, $params)
			{
				return [
					[
						'id_field' => 1,
						'title' => 'Test Title',
						'text' => 'Test Text',
						'type' => 'textbox',
						'type_vars' => '',
						'id_form' => 1,
					],
				];
			}

			public function fetch_assoc($result)
			{
				global $current_item;

				return $result[$current_item++] ?? null;
			}

			public function free_result($result)
			{
				global $current_item;

				$current_item = 0;
			}
		};
	}
}

class FieldTest extends TestCase
{
	protected function setUp(): void
	{
		MockDb::setUp();
		Db::$db = MockDb::$db;
	}

	protected function tearDown(): void
	{
		global $current_item;

		$current_item = 0;
	}

	public function testFieldConstructor()
	{
		$field = new Field(
			id: 1,
			title: 'Test Title',
			text: 'Test Text',
			type: 'textbox',
			type_vars: '',
			form_id: 1,
		);

		$this->assertEquals(1, $field->id);
		$this->assertEquals('Test Title', $field->title);
		$this->assertEquals('Test Text', $field->text);
		$this->assertEquals('textbox', $field->type);
		$this->assertEquals('', $field->type_vars);
		$this->assertEquals(1, $field->form_id);
		$this->assertInstanceOf(CustomForm\Fields\Text::class, $field->obj);
	}

	public function testLoadForForm()
	{
		$fields = Field::loadForForm(1);

		$this->assertCount(1, $fields);
		$this->assertInstanceOf(Field::class, $fields[0]);
		$this->assertEquals(1, $fields[0]->id);
		$this->assertEquals('Test Title', $fields[0]->title);
		$this->assertEquals('Test Text', $fields[0]->text);
		$this->assertEquals('textbox', $fields[0]->type);
		$this->assertEquals('', $fields[0]->type_vars);
		$this->assertEquals(1, $fields[0]->form_id);
	}
}