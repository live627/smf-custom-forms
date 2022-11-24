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

class Select implements FieldInterface
{
	use FieldTrait;

	public function setHtml(): void
	{
		$this->input_html = sprintf(
			'<select name="%s[%d]">',
			'CustomFormField',
			$this->field['id_field']
		);

		foreach ($this->type_vars as $v)
		{
			$this->input_html .= sprintf(
				'<option%s> %s</option>',
				(!$this->exists && $this->default == $v) || $this->value == $v
					? ' checked="checked"'
					: '',
				$v
			);

			if ((!$this->exists && $this->default == $v) || $this->value == $v)
				$this->output_html = $v;
		}

		$this->input_html .= '</select>';
	}

	public function validate(): bool
	{
		$found = isset(array_flip($this->type_vars)[$this->value]) || !empty($this->default);

		if (!$found && $this->required)
			$this->err = ['customform_invalid_value', $this->field['text']];

		return $this->err == [];
	}

	public function getValue(): string
	{
		return isset(array_flip($this->type_vars)[$this->value])
			? $this->value
			: $this->default;
	}
}
