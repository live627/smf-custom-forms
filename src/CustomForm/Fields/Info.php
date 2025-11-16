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

class Info implements FieldInterface
{
	use FieldTrait;

	public function setHtml(): void
	{
		$this->input_html = $this->getValue();
	}

	public function validate(): bool
	{
		// Nothing needed here, really. It's just a get out of jail
		// free card. "This card may be kept until needed, or sold."
		return true;
	}

	public function getValue(): string
	{
		return $this->value;
	}
}
