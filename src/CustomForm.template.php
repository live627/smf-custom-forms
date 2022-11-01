<?php

declare(strict_types=1);

/**
 * @package   Ultimate Menu mod
 * @version   2.2.4
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2014, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */
 
function template_forms_above()
{
	global $context, $scripturl;

	echo '
			<style>
				ul.reset, ul.reset li
				{
					padding: 0;
					margin: 0;
					list-style: none;
				}
				.reset li:not(:last-child)
				{
					padding-bottom: 0.5em;
				}
				.reset a
				{
					font-size: 1.1em;
				}
				li.padding div.d
				{
					font-style: italic;
					opacity: 0.4;
					padding: 0.4em 0.7em;
				}
			</style>
			<div class="cat_bar">
				<h3 class="catbg">
					', $context['page_title'], '
				</h3>
			</div>';

	if (!empty($context['page_message']))
		echo '
			<div class="information noup">'
				, $context['page_message'], '
			</div>';

		echo '
			<div class="windowbg noup">
		<ul class="reset">';
}

function template_forms(): void
{
	global $context, $scripturl;

	foreach ($context['forms'] as [$id_form, $title, $description])
	{
		echo '
			<li>
				<a href="' . $scripturl . '?action=form;n=' . $id_form . '">' . $title . '</a>';

		if ($description != '')
			echo '
				<div class="d">', $description, '</div>';

		echo '
			</li>';
	}
}

function template_forms_below(): void
{
	echo '
		</ul>
			</div>';
}

function template_form_above(): void
{
	global $context, $scripturl;

	echo '
	<style>
		.roundframe
		{
			display: grid;
			grid-template-columns: repeat(2, 1fr);
		}
		.roundframe :not(.tc)
		{
			grid-column: span 2;
		}
		.main_section, .lower_padding
		{
			padding-bottom: 0.5em;
		}
	</style>
		<form action="', $scripturl, '?action=form" method="post" accept-charset="', $context['character_set'], '" enctype="multipart/form-data">
			<div class="cat_bar">
				<h3 class="catbg">
					', $context['page_title'], '
				</h3>
			</div>
			<div class="roundframe noup">';
}

function template_errors_above(): void
{
	global $context, $txt;

	if ($context['post_errors'] != [])
	{
		echo '
					<div class="errorbox" id="errors">
						<strong>', $txt['customform_error_title'], '</strong>
						<ul>';

		foreach ($context['post_errors'] as $error)
			echo '
							<li>', sprintf($txt[$error[0]], $error[1]), '</li>';

		echo '
						</ul>
					</div>';
	}
}

function template_errors_below(): void
{
}

function template_form_below(): void
{
	global $context, $txt;

	echo '
				<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
				<input name="n" value="', $context['form_id'], '" type="hidden" />
				<div class="righttext padding">
					<input name="submit" value="', $txt['customform_submit'], '" class="button" type="submit" />
				</div>
			</div>
		</form>';
}

/*
	Custom Form Mod v1.7 SMF 2 Beta made by LHVWB and Garou.
	
	CustomForm.template.php - Handles the templates for the Custom Form Mod.
*/

// Generic template for showing the submit form page.
function form_template_submit_form()
{
	global $context, $txt, $settings, $scripturl;

	//	Documentation for the contents of each $field_data array, or entry into the $context['fields'] array.
	/*
	$field_name = The name of the field, straight from the value stored by the admin in the admin settings area;
	$field_data = array(
		'text' => This is the text which needs to be displayed next to the setting.,
		'type' => The type of input which the field is ,
		'value' => The value of the field, if this is not the first attempt at submitting the form,
		'data' => The list of options - only for the selection box type,
		'required' => A boolean value telling us wether or not its necessary to have a valid value for this field in order to submit the form,
		'failed' => A boolean value which tells us wether or not this field caused the form to fail to submit,
	 );
	*/

	// Now actually loop through all the fields.
	foreach ($context['fields'] as $field_name => $field_data)
	{
		if ($field_data['type'] == 'info')
			echo '
				<span class="lower_padding">', $field_data['text'], '</span>';
		else

			//	Show the display text for this field.
			echo '
				<span class="tc lower_padding', $field_data['failed'] ? ' error' : '', '"><label for="', $field_name, '"><b>', $field_data['text'], '</b></label></span>
				<span class="tc lower_padding">
					', $field_data['html'];

		//	Show the 'required' asterix if necessary.
		if (!empty($field_data['required']))
			echo '
					<span', !empty($field_data['failed']) ? ' class="error"' : '', '> *</span>';

		//	End the input column and the entire row.
		echo '
				</span>';
	}

	//    Show the "Required Fields" text down the bottom, show it in red if there was a failed submit.
	echo '
				<span class="lower_padding centertext', !empty($context['failed_form_submit']) ? ' error' : '', '">', $txt['customform_required'], '</span>';

	// Display visual verification on the form
	if ($context['require_verification'])
		echo '
				<span class="tc lower_padding"><b>', $txt['verification'], ':</b></span>
				<span class="tc lower_padding">', template_control_verification('customform','all'), '</span>';
}

function template_submit_form()
{
	global $context;

	call_user_func('form_' . $context['template_function']);
}

function template_callback_boards()
{
	global $context, $txt;

	echo '
						<dt>
							', $txt['customform_board'], '<br>
							<span class="smalltext">', $txt['customform_board_desc'], '</span>
						</dt>
						<dd>
							<select name="board_id" size="10" style="width: 75%;">';

	foreach ($context['categories'] as $category)
	{
		printf(
			'
								<optgroup label="%s">',
			$category['name']
		);

		foreach ($category['boards'] as $idx => $board)
			printf(
				'
									<option value="%s"%s>%s</option>',
				$idx,
				$board['selected'] ? ' selected' : '',
				str_repeat('&emsp;', $board['child_level'] * 2) . $board['name']
			);

	echo '
								</optgroup>';
	}

	echo '
							</select>
						</dd>';
}

function template_callback_output()
{
	echo '
									</dl>
								<table>
									<tr>
										<td class="windowbg2" style="width:50px;"></td>
										<td class="windowbg2" id="bbcBox_message">
										</td>
										<td class="windowbg2" style="width:50px;"></td>
									</tr>
									<tr>
										<td class="windowbg2" style="width:50px;"></td>
										<td class="windowbg2" id="smileyBox_message">
										</td>
										<td class="windowbg2" style="width:50px;"></td>
									</tr>
									<tr>
										<td class="windowbg2" style="width:50px;"></td>
										<td class="windowbg2">
											', template_control_richedit(
		'output',
		'smileyBox_message',
		'bbcBox_message'
	), '
										</td>
										<td class="windowbg2" style="width:50px;"></td>
									</tr>
								</table>
									<dl>';
}

function template_customform_GeneralSettings()
{
	template_show_settings();
	template_show_list();
}

function template_ThankYou()
{
	global $context, $modSettings, $scripturl, $txt;

	echo '
	<div class="cat_bar">
		<h3 class="catbg">
			', isset($modSettings['customform_view_title']) && $modSettings['customform_view_title'] != '' ? $modSettings['customform_view_title'] : $txt['customform_tabheader'], '
		</h3>
	</div>
			<div class="roundframe">
					<table style="width:100%;" align="center">
						<tr>
							<td style="padding:4px;" colspan="3" align="center">
							<b>', $txt['customform_thankyou'], '</b>
							</td>
						</tr>

						<tr>
							<td style="padding:4px;" style="width:45%;" align="center">
								<a class="button" href="' . $scripturl . '">
									', $txt['customform_forum'], '
								</a>
							</td>
							<td style="padding:4px;" style="width:45%;" align="center">
								<a class="button" href="' . $scripturl . '?action=form">
									', $txt['customform_list'], '
								</a>
							</td>
						</tr>
					</table>
			</div>
	';
}
