<?php

/**
 * @package   Custom Form mod
 * @version   3.0.0
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2014, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

declare(strict_types=1);

namespace CustomForm;

interface MaskInterface
{
	public function __construct(string $value, array $field)
	public function validate(): bool;
	public function getError(): bool|array;
}
