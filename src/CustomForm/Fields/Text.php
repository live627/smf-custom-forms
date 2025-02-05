<?php

/**
 * @package   Custom Form mod
 * @version   3.0.0
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2014, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

declare(strict_types=1);

namespace CustomForm\Fields;

use CustomForm\FieldInterface;
use CustomForm\FieldTrait;

class Text implements FieldInterface
{
	use FieldTrait;

	public function setHtml(): void
	{
		$this->output_html = $this->value;
		$this->input_html = sprintf(
			'<input type="text" name="%s[%d]" value="%s">',
			'CustomFormField',
			$this->field['id_field'],
			$this->value,
		);
	}

	public function getValue(): string
	{
		global $smcFunc;

		if (!empty($this->size)) {
			$this->value = $smcFunc['substr']($this->value, 0, $this->size);
		}

		//if (!in_array('parse_bbc', $this->type_vars))
		//	$this->value = '[nobbc]' . $this->value . '[/nobbc]';

		return $this->value;
	}

	public function validate(): bool
	{
		if (!$this->exists && $this->required) {
			$this->err = ['customform_invalid_value', $this->field['text']];
		}

		//~ $class_name = 'CustomFormFieldMask_' . $this->field['mask'];
		//~ if (!class_exists($class_name))
		//~ fatal_error('Mask "' . $this->field['mask'] . '" not found for field "' . $this->field['name'] . '" at ID #' . $this->field['id'] . '.', false);

		//~ $mask = new $class_name($this->value, $this->field);
		//~ $mask->validate(): bool;
		//~ if (false !== ($err = $mask->getError()))
		//~ $this->err = $err;

		return $this->err == [];
	}
}
