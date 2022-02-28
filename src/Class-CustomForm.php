<?php

/**
 * @package   Custom Form mod
 * @version   2.2.1
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2014, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

declare(strict_types=1);

interface CustomForm
{
	/*
	 * Constructs the field.
	 *
	 * @param array $field The field as returned by {@link total_getCustomForm()}.
	 * @param string $value Field value.
	 *
	 * @access public
	 */
	public function __construct(array $field, string $value);

	/*
	 * Sets the input so the user can enter a value.
	 * Sets the output.
	 *
	 * @access public
	 */
	public function setHtml();

	/*
	 * Gets the value. This method should handle if a specific field type must be sanitized.
	 *
	 * @access public
	 * @return string
	 */
	public function getValue(): string;
	public function validate(): bool;
	public function isRequired(): bool;
}

abstract class CustomFormBase implements CustomForm
{
	public string $input_html;
	public string $output_html;
	protected array $field;
	protected array $err = [];
	protected bool $exists = false;
	protected array $type_vars = array();
	protected string $value = '';
	protected int $size = 0;
	protected string $default = '';
	protected bool $required = false;

	public function __construct(array $field, string $value)
	{
		$this->field = $field;
		$this->value = $value;
		$this->exists = !empty($value);
	}

	public function isRequired(): bool
	{
		return $this->required;
	}

	/*
	 * Gets the error generated by the validation method.
	 *
	 * @access public
	 * @return array
	 */
	public function getError(): array
	{
		return $this->err;
	}

	/**
	 * Returns the input so the user can enter a value.
	 *
	 * @access public
	 * @return string
	 */
	public function getInputHtml(): string
	{
		return $this->input_html;
	}

	/**
	 * Returns the output. It's the field's value formatted acccording to its criteria.
	 *
	 * @access public
	 * @return string
	 */
	public function getOutputHtml(): string
	{
		return $this->output_html;
	}

	/**
	 * @access public
	 */
	public function setOptions()
	{
		$temp = !empty($this->field['type_vars'])
			? array_map('trim', explode(',', $this->field['type_vars']))
			: array();

		//	Go through all of the type_vars to format them correctly.
		if (!empty($temp))
			foreach ($temp as $var)
			{
				if (substr($var, 0, 5) == 'size=')
					$this->size = intval(substr($var, 5));
				elseif (substr($var, 0, 8) == 'default=')
					$this->default = substr($var, 8);
				elseif ($var == 'required')
					$this->required = true;
				elseif ($var != '')
					$this->type_vars[] = $var;
			}
	}
}

class CustomForm_info extends CustomFormBase
{
	public function setHtml()
	{
		$this->input_html = $this->getValue();
		$this->output_html = '';
	}
	public function validate(): bool
	{
		return true;
	}
	public function getValue(): string
	{
		return $this->value;
	}
}

class CustomForm_check extends CustomFormBase
{
	public function setHtml()
	{
		global $txt;
		$true = (!$this->exists && $this->default) || $this->value;
		$this->input_html = sprintf(
			'<input type="checkbox" text="%s[%d]"%s>',
			'CustomFormField',
			$this->field['id_field'],
			$true ? ' checked' : ''
		);
		$this->output_html = $txt[$this->getValue()];
	}
	public function validate(): bool
	{
		// Nothing needed here, really. It's just a get out of jail
		// free card. "This card may be kept until needed, or sold."
		return true;
	}
	public function getValue(): string
	{
		return (!$this->exists && $this->default) || $this->value ? 'yes' : 'no';
	}
}

class CustomForm_select extends CustomFormBase
{
	public function setHtml()
	{
		$this->input_html = sprintf(
			'<select name="%s[%d]" style="width: 90%%;">',
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
			$this->err = array('customform_invalid_value', $this->field['text']);

		return $found;
	}
	public function getValue(): string
	{
		return isset(array_flip($this->type_vars)[$this->value])
			? $this->value
			: $this->default;
	}
}

class CustomForm_radio extends CustomForm_select
{
	public function setHtml()
	{
		$this->input_html = '<fieldset>';
		foreach ($this->type_vars as $v)
		{
			$this->input_html .= sprintf(
				'<label><input type="radio" name="%s[%d]"%s> %s</label><br>',
				'CustomFormField',
				$this->field['id_field'],
				(!$this->exists && $this->default == $v) || $this->value == $v
					? ' checked="checked"'
					: '',
				$v
			);
			if ((!$this->exists && $this->default == $v) || $this->value == $v)
				$this->output_html = $v;
		}
		$this->input_html .= '</fieldset>';
	}
}

class CustomForm_text extends CustomFormBase
{
	public function setHtml()
	{
		$this->output_html = $this->value;
		$this->input_html = sprintf(
			'<input type="text" name="%s[%d]" style="width: 90%%;" value="%s">',
			'CustomFormField',
			$this->field['id_field'],
			$this->value
		);
	}
	public function getValue(): string
	{
		global $smcFunc;
		if (!empty($this->size))
			$this->value = $smcFunc['substr']($this->value, 0, $this->size);

		//if (!in_array('parse_bbc', $this->type_vars))
		//	$this->value = '[nobbc]' . $this->value . '[/nobbc]';

		return $this->value;
	}
	public function validate(): bool
	{
		if (!$this->exists && $this->required)
			$this->err = array('customform_invalid_value', $this->field['text']);

		//~ $class_name = 'CustomFormFieldMask_' . $this->field['mask'];
		//~ if (!class_exists($class_name))
			//~ fatal_error('Mask "' . $this->field['mask'] . '" not found for field "' . $this->field['name'] . '" at ID #' . $this->field['id_field'] . '.', false);

		//~ $mask = new $class_name($this->value, $this->field);
		//~ $mask->validate(): bool;
		//~ if (false !== ($err = $mask->getError()))
			//~ $this->err = $err;

		return empty($this->err);
	}
}

class CustomForm_textarea extends CustomForm_text
{
	public function setHtml()
	{
		$this->output_html = $this->value;
		@list ($rows, $cols) = @explode(',', $this->default);
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

interface CustomFormFieldMask
{
	public function __construct($value, $field);
	public function validate(): bool;
}

abstract class CustomFormFieldMaskBase implements CustomFormFieldMask
{
	protected $value;
	protected $field;
	protected $err;
	public function __construct($value, $field)
	{
		$this->value = $value;
		$this->field = $field;
		$this->err = false;
	}

	public function getError()
	{
		return $this->err;
	}
}

class CustomFormFieldMask_email extends CustomFormFieldMaskBase
{
	public function validate(): bool
	{
		if (!preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', $this->value))
			$this->err = array('customform_invalid_value', $this->field['text']);
	}
}

class CustomFormFieldMask_regex extends CustomFormFieldMaskBase
{
	public function validate(): bool
	{
		if (!preg_match($this->field['regex'], $this->value))
			if (!empty($this->field['err']))
				$this->err = $this->field['err'];
			else
				$this->err = array('customform_invalid_value', $this->field['text']);
	}
}

class CustomFormFieldMask_number extends CustomFormFieldMaskBase
{
	public function validate(): bool
	{
		if (!preg_match('/^\s*([0-9]+)\s*$/', $this->value))
			$this->err = array('customform_invalid_value', $this->field['text']);
	}
}

class CustomFormFieldMask_float extends CustomFormFieldMaskBase
{
	public function validate(): bool
	{
		if (!preg_match('/^\s*([0-9]+(\.[0-9]+)?)\s*$/', $this->value))
			$this->err = array('customform_invalid_value', $this->field['text']);
	}
}

class CustomFormFieldMask_nohtml extends CustomFormFieldMaskBase
{
	public function validate(): bool
	{
		if (strip_tags($this->value) != $this->value)
			$this->err = array('customform_invalid_value', $this->field['text']);
	}
}

?>
