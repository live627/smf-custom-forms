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

interface FieldInterface
{
	/*
	 * Constructs the field.
	 *
	 * @param array $field The field as returned by {@link total_getCustomForm()}.
	 * @param string $value Field value.
	 */
	public function __construct(array $field, string $value);

	/*
	 * Sets the input so the user can enter a value.
	 * Sets the output.
	 */
	public function setHtml();

	/*
	 * Gets the value. This method should handle if a specific field type must be sanitized.
	 *
	 * @return string
	 */
	public function getValue(): string;

	public function validate(): bool;

	public function isRequired(): bool;
}
