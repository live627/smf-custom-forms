<?php

declare(strict_types=1);

/**
 * Edit this file instead of `CustomForm.template.php` to implement your custom templates.
 *
 * For more details, see: 
 * https://github.com/live627/smf-custom-forms/blob/master/docs/custom-template-function.md
 *
 * @package   Custom Form mod
 * @version   4.1.1
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2014, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

/**
 * Renders the opening section of a form.
 * 
 * This function generates the `<form>` opening tag and includes the form title wrapped in a styled container.
 * It dynamically sets attributes like `action`, `accept-charset`, and `enctype` based on the global context.
 *
 * @global array $context Contains contextual data, including:
 *  - `character_set`: Specifies the character encoding for the form.
 *  - `page_title`: The title displayed in the form header.
 * @global string $scripturl The base URL for the form action.
 */
function template_form_my_custom_template_above(): void
{
	global $context, $scripturl;

	echo '
		<form action="', $scripturl, '?action=form" method="post" accept-charset="', $context['character_set'], '" enctype="multipart/form-data">
			<div class="cat_bar">
				<h3 class="catbg">
					', $context['page_title'], '
				</h3>
			</div>
			<div class="roundframe noup">';
}

/**
 * Renders the closing section of a form.
 * 
 * This function outputs hidden input fields for session validation and form identification,
 * along with a submit button to finalize the form submission.
 *
 * @global array $context Contains:
 *  - `session_var`: Name of the session variable for security.
 *  - `session_id`: Current session identifier.
 *  - `form_id`: Unique identifier for the current form.
 * @global array $txt Contains text strings, including the submit button label.
 */
function template_form_my_custom_template_below(): void
{
	global $context, $txt;

	echo '
				<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
				<input name="n" value="', $context['form_id'], '" type="hidden" />
				<div class="righttext breaker">
					<input name="submit" value="', $txt['customform_submit'], '" class="button" type="submit" />
				</div>
			</div>
		</form>';
}

/**
 * Dynamically generates form fields based on the configuration provided in `$context['fields']`.
 * 
 * This function iterates over the `$context['fields']` array, rendering each field based on its `type`.
 * For required fields, a `*` is displayed. Optionally, a visual verification section is included.
 * 
 * Field Configuration Format:
 * - `text`: The label for the field.
 * - `type`: The input type (e.g., `text`, `checkbox`, `info`).
 * - `html`: Pre-generated HTML for the field's input element.
 * - `required`: Boolean indicating if the field is mandatory.
 * - `failed`: Boolean indicating if the field validation failed.
 * - `value`: Pre-filled value for the field (if applicable).
 *
 * @global array $context Contains:
 *  - `fields`: Array of field definitions.
 *  - `failed_form_submit`: Indicates if the overall form submission failed.
 *  - `require_verification`: Boolean to determine if CAPTCHA or similar validation is needed.
 * @global array $txt Contains text strings for labels and messages.
 */
function template_form_my_custom_template(): void
{
	global $context, $txt;

	// Loop through and render each field
	foreach ($context['fields'] as $field_name => $field_data)
	{
		if ($field_data['type'] === 'info')
		{
			// Render informational text without input
			echo '
				<span class="breaker">', $field_data['text'], '</span>';
		}
		else
		{
			// Render input fields with labels
			echo '
				<label for="', $field_name, '"', $field_data['failed'] ? ' class="error"' : '', '>';

			if ($field_data['required'])
				echo '* '; // Mark as required

			echo $field_data['text'], '</label>
				', $field_data['html'];
		}
	}

	// Show "Required Fields" notice
	echo '
				<span class="breaker centertext', $context['failed_form_submit'] ? ' error' : '', '">', $txt['customform_required'], '</span>';

	// Include visual verification if required
	if ($context['require_verification'])
	{
		echo '
				<fieldset class="breaker">
					<legend>', $txt['verification'], '</legend>
					<span class="centertext">', template_control_verification('customform', 'all'), '</span>
				</fieldset>';
	}
}
