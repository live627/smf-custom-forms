<?php
// Version: 1.0: Text.php
namespace CustomForms\Services\Fields;

if (!defined('SMF')) {
	die('Hacking attempt...');
}

/**
 * @package CustomForms
 * @since 1.0
 */
class Text extends Base
{
	function setHtml()
	{
		$this->output_html = $this->value;
		$this->input_html = '<input type="text" name="customform[' . $this->field['id_field'] . ']" ' . ($this->field['size'] != 0 ? 'maxsize="' . $this->field['size'] . '"' : '') . ' style="width: 90%;" size="' . ($this->field['size'] == 0 || $this->field['size'] >= 50 ? 50 : ($this->field['size'] > 30 ? 30 : ($this->field['size'] > 10 ? 20 : 10))) . '" value="' . $this->value . '">';
	}

	function validate()
	{
		if (!empty($this->field['length'])) {
			$value = westr::substr($this->value, 0, $this->field['length']);
		}

		$class_name = 'FieldMask_' . $this->field['mask'];
		if (!class_exists($class_name)) {
			fatal_error('Mask "' . $this->field['mask'] . '" not found for field "' . $this->field['name'] . '" at ID #' . $this->field['id_field'] . '.', false);
		}

		$mask = new $class_name($this->value, $this->field);
		$mask->validate();
		if (false !== ($err = $mask->getError())) {
			$this->err = $err;
		}
	}
}
