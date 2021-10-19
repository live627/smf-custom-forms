<?php

declare(strict_types=1);

/**
 * @package   Ultimate Menu mod
 * @version   2.0.0
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2014, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */
 
function template_main_above()
{
	global $context, $scripturl;

	echo '
	<style>
		li.padding
		{
			padding: 0.4em 0.7em !important;
		}
		li.padding a
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
	</div>
		<ul class="reset">';
}

function template_main(): void
{
	global $context, $scripturl;

	foreach ($context['forms'] as [$id_form, $title, $description])
	{
		$col = empty($col) ? 2 : '';
		$color_class = 'windowbg' . $col;

		echo '
			<li class="padding ', $color_class, '">
				<a href="' . $scripturl . '?action=form;n=' . $id_form . '">' . $title . '</a>';

		if (!empty($description))
			echo '
				<div class="d">', $description, '</div>';

		echo '
			</li>';
	}
}

function template_main_below(): void
{
	echo '
		</ul>';
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
	</style>
		<form action="', $scripturl, '?action=form" method="post" accept-charset="', $context['character_set'], '" enctype="multipart/form-data">
			<div class="cat_bar">
				<h3 class="catbg">
					', $context['page_title'], '
				</h3>
			</div>
			<span class="upperframe"><span></span></span>
			<div class="roundframe">';
}

function template_errors_above(): void
{
	global $context, $txt;

	if (!empty($context['post_errors']))
	{
		echo '
					<div class="errorbox" id="errors">
						<strong>', $txt['customform_error_title'], '</strong>
						<ul>';

		foreach ($context['post_errors'] as $error)
			if (!empty($error))
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
					<input name="submit" value="', $txt['customform_submit'], '" class="button_submit" type="submit" />
				</div>
			</div>
			<span class="lowerframe"><span></span></span>
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
		if ($field_data['type'] == 'infobox')
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

//	Function to call the correct function for showing the submit form page.
function template_submit_form()
{
	global $context;

	call_user_func('form_' . $context['template_function']);
}

//	 Template Function to show the Custom Form Mod Admin Settings section.
function template_customform_FormSettings()
{
	global $context, $txt, $settings, $scripturl;

	//	Show the main settings for this form.
	//	Note: The next part of the template is based of the template_show_settings() function from 'Admin.template.php'.
	//	Its similar except that it is static and it has a wysiwyg editor in it.
	echo '
	<form action="', $context['post_url'], '" method="post" accept-charset="', $context['character_set'], '">
		<table width="80%" border="0" cellspacing="0" cellpadding="0" class="tborder" align="center">
			<tr>
				<td>
					<table border="0" cellspacing="0" cellpadding="4" width="100%">
						<tr class="titlebg">
							<td colspan="3">', $context['settings_title'], '</td>
						</tr>
						<tr>
							<td class="windowbg2" valign="top" width="16"><a name="setting_form_title" href="', $scripturl, '?action=helpadmin;help=customform_form_title" onclick="return reqWin(this.href);" class="help"><img src="', $settings['images_url'], '/helptopics.gif" alt="', $txt['help'], '" border="0" align="top" /></a></td>
							<td class="windowbg2" valign="top"><label for="board_id">', $txt['title'], '</label></td>
							<td class="windowbg2" width="50%">
								<input type="text" name="form_title" id="form_title" value="', $context['custom_form_settings']['form_title'], '" />
							</td>
						</tr>
						<tr>
							<td class="windowbg2" valign="top" width="16"><a name="setting_form_board_id" href="', $scripturl, '?action=helpadmin;help=customform_board_id" onclick="return reqWin(this.href);" class="help"><img src="', $settings['images_url'], '/helptopics.gif" alt="', $txt['help'], '" border="0" align="top" /></a></td>
							<td class="windowbg2" valign="top"><label for="board_id">', $txt['customform_board_id'], '</label></td>
							<td class="windowbg2" width="50%">
								<select name="form_board_id">';
	foreach ($context['categories'] AS $category)
	{
		echo '
								<optgroup label="', $category['name'], '">';
		foreach ($category['boards'] as $board)
			echo '
								<option value="', $board['id'], '"', (!empty($context['custom_form_settings']['form_board_id']) && $context['custom_form_settings']['form_board_id'] == $board['id']) ? ' selected="selected"' : '', '>', $board['child_level'] > 0 ? str_repeat(
					'==',
					$board['child_level'] - 1
				) . '=&gt;' : '', $board['name'], '</option>';
		echo '
								</optgroup>';
	}

	echo '
								</select>
							</td>
						</tr>
						<tr>
					 <td class="windowbg2" valign="top" width="16"><a name="setting_form_icon" href="', $scripturl, '?action=helpadmin;help=customform_icon" onclick="return reqWin(this.href);" class="help"><img src="', $settings['images_url'], '/helptopics.gif" alt="', $txt['help'], '" border="0" align="top" /></a></td>
					 <td class="windowbg2" valign="top"><label for="icon">', $txt['message_icon'], '</label></td>
					 <td class="windowbg2" width="50%">
										   <select name="icon" id="icon" onchange="showimage()">';

	// Loop through each message icon allowed, adding it to the drop down list.
	foreach ($context['icons'] as $icon)
		echo '
							  <option value="', $icon['value'], '"', $icon['value'] == $context['custom_form_settings']['icon'] ? ' selected="selected"' : '', '>', $icon['name'], '</option>';

	echo '
						   </select>
						   <img src="', $context['icon_url'], '" name="icons" hspace="15" alt="" />
					 </td>
				  </tr>
						<tr>
							<td class="windowbg2" valign="top" width="16"><a name="setting_template_function" href="', $scripturl, '?action=helpadmin;help=customform_template_function" onclick="return reqWin(this.href);" class="help"><img src="', $settings['images_url'], '/helptopics.gif" alt="', $txt['help'], '" border="0" align="top" /></a></td>
							<td class="windowbg2" valign="top"><label for="template_function">', $txt['customform_template_function'], '</label></td>
							<td class="windowbg2" width="50%">
								<input type="text" name="template_function" id="template_function" value="', $context['custom_form_settings']['template_function'], '" />
							</td>
						</tr>
						<tr>
							<td class="windowbg2" valign="top" width="16"><a name="setting_permissions" href="', $scripturl, '?action=helpadmin;help=customform_permissions" onclick="return reqWin(this.href);" class="help"><img src="', $settings['images_url'], '/helptopics.gif" alt="', $txt['help'], '" border="0" align="top" /></a></td>
							<td class="windowbg2" valign="top"><label for="permissions">', $txt['edit_permissions'], '</label></td>
							<td class="windowbg2" width="50%">
								', theme_inline_permissions($context['custom_form_settings']['permissions']), '
							</td>
						</tr>
						<tr>
							<td class="windowbg2" valign="top" width="16"><a name="setting_subject" href="', $scripturl, '?action=helpadmin;help=customform_subject" onclick="return reqWin(this.href);" class="help"><img src="', $settings['images_url'], '/helptopics.gif" alt="', $txt['help'], '" border="0" align="top" /></a></td>
							<td class="windowbg2" valign="top"><label for="subject">', $txt['subject'], '</label></td>
							<td class="windowbg2" width="50%">
								<input type="text" name="subject" id="subject" value="', $context['custom_form_settings']['subject'], '" />
							</td>
						</tr>
						<tr>
							<td class="windowbg2" valign="top" width="16"><a name="setting_exit" href="', $scripturl, '?action=helpadmin;help=customform_submit_exit" onclick="return reqWin(this.href);" class="help"><img src="', $settings['images_url'], '/helptopics.gif" alt="', $txt['help'], '" border="0" align="top" /></a></td>
							<td class="windowbg2" valign="top"><label for="Form_exit">', $txt['customform_exit'], '</label></td>
							<td class="windowbg2" width="50%">
								<input type="text" name="form_exit" id="form_exit" value="', $context['custom_form_settings']['form_exit'], '" />
							</td>
						</tr>
						<tr>
							<td class="windowbg2" valign="top" width="16"><a name="setting_output" href="', $scripturl, '?action=helpadmin;help=customform_output" onclick="return reqWin(this.href);" class="help"><img src="', $settings['images_url'], '/helptopics.gif" alt="', $txt['help'], '" border="0" align="top" /></a></td>
							<td class="windowbg2 valign="top"><label for="output"><u><strong>', $txt['customform_output'], ':</strong></u></label></td>
							<td class="windowbg2" width="50%"></td>
						</tr>
						<tr>
							<td class="windowbg2" style="hieght:12px;" colspan="3"></td>
						</tr>
						<tr>
							<td class="windowbg2" colspan="3">
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
							</td>
						<tr>
							<td class="windowbg2" colspan="3" align="center" valign="middle"><input type="submit" value="', $txt['save'], '" /></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
	</form>

	<br /><br />';

	//	Finally show the list of fields.
	template_show_list('customfield_list');
}

//	Simple fucntion to connect two templates for the General Settings area.
function template_customform_GeneralSettings()
{
	//	Show the confiq_vars.
	template_show_settings();

	//	Put in a spacer to make it look better.
	echo '
	<br />';

	//	Show the list.
	template_show_list();

}

//	Template function for the thank you page.
function template_ThankYou()
{
	global $context, $modSettings, $txt, $settings, $scripturl;

	//	Show the Starting part of the template.
	echo '
	<table style="width:100%;">
		<tr>
			<td style="width:20%;"></td>
			<td class="tborder" style="margin-top: 1ex;width:50%;">
				<div class="titlebg" style="padding: 4px;">', (isset($modSettings['customform_view_title']) && ($modSettings['customform_view_title'] != '')) ? $modSettings['customform_view_title'] : $txt['customform_tabheader'], '</div>
				<div style="padding: 2ex;" class="windowbg2">

					<table style="width:100%;background-color:#000000;" align="center">
						<tr class="windowbg">
							<td style="padding:4px;" colspan="3" align="center">
							<b>	', $txt['customform_thankyou'], '</b>
							</td>
						</tr>

						<tr class="titlebg">
							<td style="padding:4px;" style="width:45%;" align="center">
							<a href="' . $scripturl . '">	', $txt['customform_forum'], '</a>
							</td>
							<td style="padding:4px;" style="width:45%;" align="center">
							<a href="' . $scripturl . '?action=form;">	', $txt['customform_list'], '</a>
							</td>
						</tr>';
	//	Finsh off the template.
	echo '
					</table>
				</div>
			</td>
			<td style="width:20%;"></td>
		<tr>
	</table>
	';
}