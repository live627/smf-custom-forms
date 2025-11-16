<?php

/**
 * @package   Custom Form mod
 * @version   4.1.1
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2014, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

declare(strict_types=1);

namespace CustomForm\Fields;

use CustomForm\FieldInterface;
use CustomForm\FieldTrait;

class Check implements FieldInterface
{
	use FieldTrait;

	public function setHtml(): void
	{
		global $txt;

		$true = $this->exists
			? $this->value
			: $this->default;
		$this->input_html = sprintf(
			'<input type="checkbox" name="%s[%d]"%s>',
			'CustomFormField',
			$this->field['id_field'],
			$true ? ' checked' : ''
		);
		$this->output_html = $txt[$this->getValue()];
	}

	public function validate(): bool
	{
		if (!$this->exists && $this->required)
			$this->err = ['customform_invalid_value', $this->field['text']];

		return $this->err == [];
	}

	public function getValue(): string
	{
		return (!$this->exists && $this->default) || $this->value ? 'yes' : 'no';
	}
}
