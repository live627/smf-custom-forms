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

class Form
{
	/*******************
	 * Public properties
	 *******************/

	/**
	 * @var array The fields associated with the form
	 */
	public array $fields = [];

	/****************
	 * Public methods
	 ****************/

	/**
	 * Form constructor.
	 *
	 * @param int $id The form ID
	 * @param string $title The form title
	 * @param string $output The form output
	 * @param string $subject The form subject
	 * @param int $board_id The board ID associated with the form
	 * @param string $icon The form icon
	 * @param string $form_exit The form exit behavior
	 * @param string $template_function The template function used
	 * @param string $output_type The output type of the form
	 */
	public function __construct(
		public readonly int $id,
		public readonly string $title,
		public readonly string $output,
		public readonly string $subject,
		public readonly int $board_id,
		public readonly string $icon,
		public readonly string $form_exit,
		public readonly string $template_function,
		public readonly string $output_type,
		bool $load_fields = true,
	) {
		if ($load_fields) {
		$this->fields = Field::loadForForm($id);
		}
	}

	/**
	 * Loads a form from the database by its ID.
	 *
	 * @param int $form_id The ID of the form to load
	 * @return self|null The loaded form instance or null if not found
	 */
	public static function load(int $form_id, bool $load_fields = true): ?self
	{
		return iterator_to_array(self::fetchMany([$form_id], $load_fields))[0];
	}

	/**
	 * Loads a form from the database by its ID.
	 *
	 * @return \Generator The loaded form instance or null if not found
	 */
	public static function fetchMany(array $ids, bool $load_fields = true): \Generator
	{
		$entries = DatabaseHelper::fetchBy(
			['id_form', 'title', 'output', 'subject', 'id_board, icon', 'form_exit', 'template_function', 'output_type'],
			'{db_prefix}cf_forms AS f',
			[ 'ids' => $ids],
			[],
			['title != \'\' ', 'EXISTS ( SELECT 1 FROM {db_prefix}cf_fields AS d WHERE d.id_form = f.id_form AND title != \'\' AND text != \'\' AND type != \'\' ) ', 'id_form IN ({array_int:ids})'],
		);

		foreach ($entries as $form_data) {
		yield new self(
			(int) $form_data['id_form'],
			$form_data['title'],
			$form_data['output'],
			$form_data['subject'],
			(int) $form_data['id_board'],
			$form_data['icon'],
			$form_data['form_exit'],
			$form_data['template_function'],
			$form_data['output_type'],
			$load_fields,
		);
		}
	}

	/**
	 * Loads a form from the database by its ID.
	 *
	 * @return \Generator The loaded form instance or null if not found
	 */
	public static function fetchAll(bool $load_fields = true): \Generator
	{
		$entries = DatabaseHelper::fetchBy(
			['id_form', 'title', 'output', 'subject', 'id_board, icon', 'form_exit', 'template_function', 'output_type'],
			'{db_prefix}cf_forms AS f',
			[],
			[],
			['title != \'\' ', 'EXISTS ( SELECT 1 FROM {db_prefix}cf_fields AS d WHERE d.id_form = f.id_form AND title != \'\' AND text != \'\' AND type != \'\' )'],
		);

		foreach ($entries as $form_data) {
		yield new self(
			(int) $form_data['id_form'],
			$form_data['title'],
			$form_data['output'],
			$form_data['subject'],
			(int) $form_data['id_board'],
			$form_data['icon'],
			$form_data['form_exit'],
			$form_data['template_function'],
			$form_data['output_type'],
			$load_fields,
		);
		}
	}
}
