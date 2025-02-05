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

class Floating implements MaskInterface
{
	use MaskTrait;

	public function validate(): bool
	{
		if (!preg_match('/^\s*([0-9]+(\.[0-9]+)?)\s*$/', $this->value)) {
			$this->err = ['customform_invalid_value', $this->field['text']];
		}

		return $this->err == false;
	}
}
