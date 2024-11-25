<?php

/**
 * @package   Custom Form mod
 * @version   4.1.0
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2014, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

declare(strict_types=1);

namespace CustomForm\Fields\Masks;

use CustomForm\MaskInterface;
use CustomForm\MaskTrait;

class Regex implements MaskInterface
{
	use MaskTrait;

	public function validate(): bool
	{
		if (!preg_match($this->field['regex'], $this->value))
			if (!empty($this->field['err']))
				$this->err = $this->field['err'];
			else
				$this->err = ['customform_invalid_value', $this->field['text']];

		return $this->err == false;
	}
}
