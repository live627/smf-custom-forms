<?php

declare(strict_types=1);

namespace CustomForm;

class FieldFactory
{
	/**
	 * Create a Field instance based on provided parameters.
	 *
	 * @param int|null $id The field ID
	 * @param string|null $title The field title
	 * @param string|null $text The field text
	 * @param string|null $type The field type
	 * @param string|null $type_vars Additional type variables
	 * @param int|null $form_id The form ID
	 *
	 * @return Field The created Field instance
	 */
	public static function create(
		?int $id = null,
		?string $title = null,
		?string $text = null,
		?string $type = null,
		?string $type_vars = null,
		?int $form_id = null,
	): Field {
		return new Field($id ?? 0, $title ?? '', $text ?? '', $type ?? '', $type_vars ?? '', $form_id ?? 0);
	}
}