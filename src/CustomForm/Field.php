<?php

declare(strict_types=1);

/**
 * @package   Ultimate Menu mod
 * @version   3.0.0
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2014, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace CustomForm;

use SMF\Db\DatabaseApi as Db;

class Field
{
	/*******************
	 * Public properties
	 *******************/

	/** @var FieldInterface|null The field object based on its type. */
	public ?FieldInterface $obj = null;

	/****************
	 * Public methods
	 ****************/

	/**
	 * Field constructor.
	 *
	 * @param int $id The field ID
	 * @param string $title The field title
	 * @param string $text The field text
	 * @param string $type The field type
	 * @param string $type_vars Additional type variables
	 * @param int $form_id
	 * @param string $value
	 */
	public function __construct(
		public readonly int $id,
		public readonly string $title,
		public readonly string $text,
		public readonly string $type,
		public readonly string $type_vars,
		public readonly int $form_id,
		string $value = '',
	) {
		$type = strtr(
			$this->type,
			[
				'largetextbox' => 'textarea',
				'textbox' => 'text',
				'checkbox' => 'check',
				'selectbox' => 'select',
				'float' => 'text',
				'int' => 'text',
				'radiobox' => 'radio',
				'infobox' => 'info',
			],
		);
		$class_name = 'CustomForm\\Fields\\' . ucfirst($type);

		if (class_exists($class_name)) {
			$this->obj = new $class_name($this, $value);
		}
	}

	/**
	 * Loads fields associated with a form.
	 *
	 * @param int $form_id The form ID
	 * @return Field[] An array of Field objects
	 */
	public static function loadForForm(int $form_id): array
	{
		$fields = [];
		$request = Db::$db->query(
			'',
			'
			SELECT id_field, title, text, type, type_vars, id_form
			FROM {db_prefix}cf_fields
			WHERE id_form = {int:id}
			AND title != \'\'
			AND text != \'\'
			AND type != \'\'
			ORDER BY id_field',
			[
				'id' => $form_id,
			],
		);

		while ($row = Db::$db->fetch_assoc($request)) {
			$fields[] = new self(
				(int) $row['id_field'],
				$row['title'],
				$row['text'],
				$row['type'],
				$row['type_vars'],
				(int) $row['id_form'],
				$_POST['CustomFormField'][$row['id_field']] ?? '',
			);
		}

		Db::$db->free_result($request);

		return $fields;
	}

	/**
	 * Loads multiple fields from the database.
	 *
	 * @return \Generator<Form> The loaded field instance or null if not found
	 */
	public static function fetchManyForAdmin(?array $ids): \Generator
	{
		$entries = DatabaseHelper::fetchBy(
			[
				'id_field, title, text, type, type_vars, id_form',
			],
			'{db_prefix}cf_fields AS f',
			$ids === null ? [] : ['ids' => $ids],
			[],
			[
				$ids === null ? '' : 'id_field IN ({array_int:ids})',
			],
		);

		foreach ($entries as $row) {
			yield new self(
				(int) $row['id_field'],
				$row['title'],
				$row['text'],
				$row['type'],
				$row['type_vars'],
				(int) $row['id_form'],
			);
		}
	}
}
