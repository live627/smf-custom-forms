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

class Textarea extends Text
{
	public function setHtml(): void
	{
		$this->output_html = $this->value;
		@[$rows, $cols] = @explode(',', $this->default);
		$this->input_html = sprintf(
			'<textarea name="%s[%d]" %s%s>%s</textarea>',
			'CustomFormField',
			$this->field['id_field'],
			!empty($rows) ? 'rows="' . $rows . '"' : '',
			!empty($cols) ? 'cols="' . $cols . '"' : '',
			$this->value
		);
	}
}
