<?php

/**
 * @package   Custom Form mod
 * @version   4.1.1
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2014, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

declare(strict_types=1);

namespace CustomForm;

trait MaskTrait
{
	protected string $value;

	protected array $field;

	protected bool|array $err;

	public function __construct(string $value, array $field)
	{
		$this->value = $value;
		$this->field = $field;
		$this->err = false;
	}

	public function getError(): bool|array
	{
		return $this->err;
	}
}
