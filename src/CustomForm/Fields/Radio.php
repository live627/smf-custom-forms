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

use CustomForm\FieldTrait;

class Radio extends Select
{
	use FieldTrait;

	public function setHtml(): void
	{
		$this->input_html = '<fieldset>';

		foreach ($this->type_vars as $v) {
			$this->input_html .= sprintf(
				'<label><input type="radio" name="%s[%d]" value="%4$s"%s> %s</label><br>',
				'CustomFormField',
				$this->field['id_field'],
				(!$this->exists && $this->default == $v) || $this->value == $v
					? ' checked="checked"'
					: '',
				$v,
			);

			if ((!$this->exists && $this->default == $v) || $this->value == $v) {
				$this->output_html = $v;
			}
		}

		$this->input_html .= '</fieldset>';
	}
}
