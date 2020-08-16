<?php

interface CustomForm
{
	/*
	 * Constructs the field.
	 *
	 * @param array $field The field as returned by {@link total_getCustomForm()}.
	 * @param string $value Field value.
	 * @param bool $exists Whether the value exists/is not empty.
	 * @access public
	 * @return void
	 */
	public function __construct($field, $value, $exists);

	/*
	 * Sets the input so the user can enter a value.
	 * Sets the output.
	 *
	 * @access public
	 * @return void
	 */
	public function setHtml();
	function validate();
}

abstract class CustomFormBase implements CustomForm
{
	public $input_html;
	public $output_html;
	protected $field;
	protected $value;
	protected $err = false;
	protected $exists = false;
	protected $type_vars = array();
	protected $value = '';
	protected $size = 0;
	protected $default = '';
	protected $required = false;

	public function __construct($field, $value, $exists)
	{
		$this->field = $field;
		$this->value = $value;
		$this->exists = $exists;
	}

	/*
	 * Gets the error generatedd by the validation method.
	 *
	 * @access public
	 * @return mixed The error string or false for no error.
	 */
	function getError()
	{
		return $this->err;
	}

	/*
	 * Gets the value. This method may be overridden if a specific field type must be sanitized.
	 *
	 * @access public
	 * @return string
	 */
	function getValue()
	{
		return $this->value;
	}

	/**
	 * Returns the input so the user can enter a value.
	 *
	 * @access public
	 * @return mixed
	 */
	public function getInputHtml()
	{
		return $this->input_html;
	}

	/**
	 * Returns the output. It's the field's value formatted acccording to its criteria.
	 *
	 * @access public
	 * @return mixed
	 */
	public function getOutputHtml()
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

class CustomForm_check extends CustomFormBase
{
	function setHtml()
	{
		global $txt;
		$true = (!$this->exists && $this->default) || $this->value;
		$this->input_html = '<input type="checkbox" name="CustomFormField[' . $this->field['id_field'] . ']"' . ($true ? ' checked' : '') . '>';
		$this->output_html = $true ? $txt['yes'] : $txt['no'];
	}
	function validate()
	{
		// Nothing needed here, really. It's just a get out of jail free card. "This card may be kept until needed, or sold."
	}
	function getValue()
	{
		return (!$this->exists && $this->default) || $this->value;
	}
}

class CustomForm_select extends CustomFormBase
{
	function setHtml()
	{
		$this->input_html = '<select name="CustomFormField[' . $this->field['id_field'] . ']" style="width: 90%;">';
		foreach ($this->type_vars as $v)
		{
			$true = (!$this->exists && $this->default == $v) || $this->value == $v;
			$this->input_html .= '<option' . ($true ? ' selected="selected"' : '') . '>' . $v . '</option>';
			if ($true)
				$this->output_html = $v;
		}

		$this->input_html .= '</select>';
	}
	function validate()
	{
		$found = false;
		$opts = array_flip($this->type_vars);
		if (isset($this->value, $opts[$this->value]))
			$found = true;

		if (!$found && $this->required)
			$this->err = array('pf_invalid_value', $this->field['name']);
	}
	function getValue()
	{
		$value = $this->default;
		$opts = array_flip($this->type_vars);
		if (isset($this->value, $opts[$this->value]))
			$value = $this->value;

		return $value;
	}
}

class CustomForm_radio extends CustomForm_select
{
	function setHtml()
	{
		$this->input_html = '<fieldset>';
		foreach ($this->type_vars as $v)
		{
			$true = (!$this->exists && $this->default == $v) || $this->value == $v;
			$this->input_html .= '<label><input type="radio" name="CustomFormField[' . $this->field['id_field'] . ']"' . ($true ? ' checked="checked"' : '') . '> ' . $v . '</label><br>';
			if ($true)
				$this->output_html = $v;
		}
		$this->input_html .= '</fieldset>';
	}
}

class CustomForm_text extends CustomFormBase
{
	function setHtml()
	{
		$this->output_html = $this->value;
		$this->input_html = '<input type="text" name="CustomFormField[' . $this->field['id_field'] . ']" style="width: 90%;" value="' . $this->value . '">';
	}
	function getValue()
	{
		global $smcFunc;
		if (!empty($this->size))
			$this->value = $smcFunc['substr']($this->value, 0, $this->size);

		if (!in_array('parse_bbc', $this->type_vars))
			$this->value = '[nobbc]' . $this->value . '[/nobbc]';

		return $this->value;
	}
	function validate()
	{
		if ($this->exists && $this->required)
			$this->err = array('pf_invalid_value', $this->field['name']);

		//~ $class_name = 'CustomFormFieldMask_' . $this->field['mask'];
		//~ if (!class_exists($class_name))
			//~ fatal_error('Mask "' . $this->field['mask'] . '" not found for field "' . $this->field['name'] . '" at ID #' . $this->field['id_field'] . '.', false);

		//~ $mask = new $class_name($this->value, $this->field);
		//~ $mask->validate();
		//~ if (false !== ($err = $mask->getError()))
			//~ $this->err = $err;
	}
}

class CustomForm_textarea extends CustomForm_text
{
	function setHtml()
	{
		$this->output_html = $this->value;
		@list ($rows, $cols) = @explode(',', $this->default);
		$this->input_html = '<textarea name="CustomFormField[' . $this->field['id_field'] . ']" ' . (!empty($rows) ? 'rows="' . $rows . '"' : '') . ' ' . (!empty($cols) ? 'cols="' . $cols . '"' : '') . '>' . $this->value . '</textarea>';
	}
}

interface CustomFormFieldMask
{
	function __construct($value, $field);
	function validate();
}

abstract class CustomFormFieldMaskBase implements CustomFormFieldMask
{
	protected $value;
	protected $field;
	protected $err;
	function __construct($value, $field)
	{
		$this->value = $value;
		$this->field = $field;
		$this->err = false;
	}

	function getError()
	{
		return $this->err;
	}
}

class CustomFormFieldMask_email extends CustomFormFieldMaskBase
{
	function validate()
	{
		if (!preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', $this->value))
			$this->err = array('pf_invalid_value', $this->field['name']);
	}
}

class CustomFormFieldMask_regex extends CustomFormFieldMaskBase
{
	function validate()
	{
		if (!preg_match($this->field['regex'], $this->value))
			if (!empty($this->field['err']))
				$this->err = $this->field['err'];
			else
				$this->err = array('pf_invalid_value', $this->field['name']);
	}
}

class CustomFormFieldMask_number extends CustomFormFieldMaskBase
{
	function validate()
	{
		if (!preg_match('/^\s*([0-9]+)\s*$/', $this->value))
			$this->err = array('pf_invalid_value', $this->field['name']);
	}
}

class CustomFormFieldMask_float extends CustomFormFieldMaskBase
{
	function validate()
	{
		if (!preg_match('/^\s*([0-9]+(\.[0-9]+)?)\s*$/', $this->value))
			$this->err = array('pf_invalid_value', $this->field['name']);
	}
}

class CustomFormFieldMask_nohtml extends CustomFormFieldMaskBase
{
	function validate()
	{
		if (strip_tags($this->value) != $this->value)
			$this->err = array('pf_invalid_value', $this->field['name']);
	}
}

?>