<?php

/*
	Custom Form Mod v1.7 SMF 2 Beta made by LHVWB and Garou.
	
	CustomForm.template.php - Handles the templates for the Custom Form Mod.
*/

// Generic template for showing the submit form page.
function form_template_submit_form()
{
	global $context, $txt, $settings, $scripturl;

	//	Starting text for the form and tables. Don't muck with this unless you need to change the style!!!  ;)
	echo '
	<form action="', $context['post_url'], '" method="post" accept-charset="', $context['character_set'], '">
		<table width="80%" border="0" cellspacing="0" cellpadding="0" class="tborder" align="center">
			<tr>
				<td>
					<table border="0" cellspacing="0" cellpadding="4" width="100%">
						<tr class="titlebg">
							<td colspan="3">', $context['settings_title'], '</td>
						</tr>';
	
	//	Here you can add rows to the beginning of the table, if you want to...
	/* 	Like this:
	echo '
					<tr class="windowbg2">
						New row.
					</tr>';
	*/
	
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
		//	Output the start of the row, as well as a spacer column.
		echo '
						<tr class="windowbg2">
							<td class="windowbg2"></td>';
		
		if ($field_data['type'] == 'infobox')
		echo '
						<td colspan="2" valign="top"><label for="', $field_name, '">', $field_data['text'], '</label></td>
						';
		else

		//	Show the display text for this field.
		echo '
							<td valign="top"><label for="', $field_name, '">', $field_data['text'], '</label></td>
							<td class="windowbg2" width="50%">';

		// Show a check box.
		if ($field_data['type'] == 'checkbox')
			echo '
								<input type="checkbox" name="', $field_name, '" id="', $field_name, '" ', (($field_data['value']) ? ' checked="checked"' : ''), ' value="1" class="check" />';

		// Show a selection box.
		elseif ($field_data['type'] == 'selectbox')
		{
			echo '
								<select name="', $field_name, '" id="', $field_name, '" >';
			foreach ($field_data['data'] as $option)
				if ($option != 'required')
					echo '
									<option value="', $option, '"', ($option == $field_data['value'] ? ' selected="selected"' : ''), '>', $option, '</option>';
				else
					echo '
									<option value=""></option>';

			echo '
								</select>';
		}
		// Show a radio box.
		elseif ($field_data['type'] == 'radiobox')
		{

			foreach ($field_data['data'] as $option)
	  				if ($option != 'required')
 
			echo '
                  
                        <input type="radio" name="', $field_name, '" value="', $option , '">', $option , '<br />';
	  				else
			echo '
                        <option value=""></option>';
	 }
		// Large Text box?
		elseif ($field_data['type'] == 'largetextbox')
		{
			echo '
								<textarea rows="10" cols="50%" name="', $field_name, '" id="', $field_name, '">', $field_data['value'], '</textarea>';
		}
		// Show a Info box.
		elseif ($field_data['type'] == 'infobox')
		{
			echo '
			';
		}
		// Int, Float or text box?
		else
			echo '
								<input type="text" size="50%" name="', $field_name, '" id="', $field_name, '" value="', $field_data['value'], '" />';
								
		//	Show the 'required' asterix if necessary.
		if(!empty($field_data['required']))
			echo '
								<span ', !empty($field_data['failed']) ? 'style="color:#FF0000;"' : '' ,'> *</span>';		
		
		//	End the input column and the entire row.
		echo '
							</td>
						</tr>';
	}

	//    Show the "Required Fields" text down the bottom, show it in red if there was a failed submit.
   echo '
               <tr class="windowbg2">
                  <td colspan="3" style="text-align:center;', !empty($context['failed_form_submit']) ? 'color:#FF0000;' : '', '">
                     * ', $txt['CustomForm_required'], '
                  </td>
               </tr>';

	//	Here you can add rows to the end of the form, if you want to...
	/* 	Like this:
	echo '
						<tr class="windowbg2">'
							New row.
						</tr>'
	*/

	// Display visual verification on the form      
   if ($context['visual_verification'])
   {
	  echo '
      <tr><td class="windowbg2" colspan="3" align="center" valign="middle"><b>', $txt['verification'], ':</b></td><tr>
      <tr><td class="windowbg2" colspan="3" align="center" valign="middle">', template_control_verification($context['visual_verification_id'], 'all'), '</td></tr>';
   }

	//	Output the save button, the end of the tables and the form.	
	echo '
						<tr>
							<td class="windowbg2" colspan="3" align="center" valign="middle"><input type="submit" value="', $txt['save'], '"', (!empty($context['save_disabled']) ? ' disabled="disabled"' : ''), ' /></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
	</form>';
}

//	Function to call the correct function for showing the submit form page.
function template_submit_form()
{
	global $context;
	
	//	Well, we can try to get a user defined form template, but don't hold your hopes too high! ;D
	if (isset($context['template_function'])
	&& function_exists('form_template_' . $context['template_function']))
		call_user_func('form_template_' . $context['template_function']);
	//	Call the default template for the submit form page if we have to...
	else
		form_template_submit_form();
}

//	The main template function for viewing the form action, which shows a list of forms.
function template_FormList()
{
	global $context, $modSettings, $txt, $scripturl;
	
	//	Show the Starting part of the template.
	echo '
	<table style="width:100%;">
		<tr>
			<td style="width:20%;"></td>
			<td class="tborder" style="margin-top: 1ex;width:50%;">
				<div class="titlebg" style="padding: 4px;">' , (isset($modSettings['CustomForm_view_title']) && ($modSettings['CustomForm_view_title'] != '')) ? $modSettings['CustomForm_view_title'] : $txt['CustomForm_tabheader'] , '</div>
				<div style="padding: 2ex;" class="windowbg2">
				' , (isset($modSettings['CustomForm_view_text']) && ($modSettings['CustomForm_view_text'] != '')) ? $modSettings['CustomForm_view_text'] . '<br /><br />' : '','
					<table style="width:100%;background-color:#000000;" align="center">
						<tr class="titlebg" >
							<td style="width:45%;" >' , $txt['title'] , '</td>
							<td style="width:45%;" >' , $txt['board'] , '</td>
							<td style="width:10%;text-align:center;" >' , $txt['view'] , '</td>
						</tr>';
	
	//	If we can then show the list of forms.
	if(!empty($context['custom_forms_list']))
	{
		//	Show the list of forms.
		foreach($context['custom_forms_list'] as $form)
		{
			echo '
						<tr class="windowbg">
							<td style="padding:4px;" >
								<a href="' , $scripturl , '?action=form;n=' , $form['id'] ,'">' , $form['title'] , '</a>
							</td>
							<td style="padding:4px;" >
								<a href="' , $scripturl , '?board=' , $form['id_board'] ,'">' , $form['board'] , '</a>
							</td>
							<td style="padding:4px;text-align:center;" >
								<a href="' , $scripturl , '?action=form;n=' , $form['id'] ,'">' , $txt['view'] , '</a>
							</td>
						</tr>';
		}
	}
	//	Otherwise show a message saying that there are no forms present.
	else
	{
		echo '
						<tr class="windowbg">
							<td style="padding:4px;" colspan="3">
								' , $txt['CustomForm_list_noelements'] , '
							</td>
						</tr>';
	}
	
	
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

//	 Template Function to show the Custom Form Mod Admin Settings section.
function template_CustomForm_FormSettings()
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
							<td class="windowbg2" valign="top" width="16"><a name="setting_form_title" href="', $scripturl, '?action=helpadmin;help=CustomForm_form_title" onclick="return reqWin(this.href);" class="help"><img src="', $settings['images_url'], '/helptopics.gif" alt="', $txt['help'], '" border="0" align="top" /></a></td>
							<td class="windowbg2" valign="top"><label for="board_id">', $txt['title'] , '</label></td>
							<td class="windowbg2" width="50%">
								<input type="text" name="form_title" id="form_title" value="', $context['custom_form_settings']['form_title'], '" />
							</td>
						</tr>
						<tr>
							<td class="windowbg2" valign="top" width="16"><a name="setting_form_board_id" href="', $scripturl, '?action=helpadmin;help=CustomForm_board_id" onclick="return reqWin(this.href);" class="help"><img src="', $settings['images_url'], '/helptopics.gif" alt="', $txt['help'], '" border="0" align="top" /></a></td>
							<td class="windowbg2" valign="top"><label for="board_id">', $txt['CustomForm_board_id'] , '</label></td>
							<td class="windowbg2" width="50%">
								<select name="form_board_id">';
	foreach ($context['categories'] AS $category)
	{
		echo '
								<optgroup label="', $category['name'], '">';
		foreach ($category['boards'] as $board)
			echo '
								<option value="', $board['id'], '"', (!empty($context['custom_form_settings']['form_board_id']) && $context['custom_form_settings']['form_board_id'] == $board['id']) ? ' selected="selected"' : '', '>', $board['child_level'] > 0 ? str_repeat('==', $board['child_level']-1) . '=&gt;' : '', $board['name'], '</option>';
		echo '
								</optgroup>';
	}

	echo '
								</select>
							</td>
						</tr>
						<tr>
                     <td class="windowbg2" valign="top" width="16"><a name="setting_form_icon" href="', $scripturl, '?action=helpadmin;help=CustomForm_icon" onclick="return reqWin(this.href);" class="help"><img src="', $settings['images_url'], '/helptopics.gif" alt="', $txt['help'], '" border="0" align="top" /></a></td>
                     <td class="windowbg2" valign="top"><label for="icon">', $txt['message_icon'] , '</label></td>
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
							<td class="windowbg2" valign="top" width="16"><a name="setting_template_function" href="', $scripturl, '?action=helpadmin;help=CustomForm_template_function" onclick="return reqWin(this.href);" class="help"><img src="', $settings['images_url'], '/helptopics.gif" alt="', $txt['help'], '" border="0" align="top" /></a></td>
							<td class="windowbg2" valign="top"><label for="template_function">', $txt['CustomForm_template_function'] , '</label></td>
							<td class="windowbg2" width="50%">
								<input type="text" name="template_function" id="template_function" value="', $context['custom_form_settings']['template_function'], '" />
							</td>
						</tr>
						<tr>
							<td class="windowbg2" valign="top" width="16"><a name="setting_permissions" href="', $scripturl, '?action=helpadmin;help=CustomForm_permissions" onclick="return reqWin(this.href);" class="help"><img src="', $settings['images_url'], '/helptopics.gif" alt="', $txt['help'], '" border="0" align="top" /></a></td>
							<td class="windowbg2" valign="top"><label for="permissions">', $txt['edit_permissions'] , '</label></td>
							<td class="windowbg2" width="50%">
								' , theme_inline_permissions($context['custom_form_settings']['permissions']) , '
							</td>
						</tr>
						<tr>
							<td class="windowbg2" valign="top" width="16"><a name="setting_subject" href="', $scripturl, '?action=helpadmin;help=CustomForm_subject" onclick="return reqWin(this.href);" class="help"><img src="', $settings['images_url'], '/helptopics.gif" alt="', $txt['help'], '" border="0" align="top" /></a></td>
							<td class="windowbg2" valign="top"><label for="subject">', $txt['subject'] , '</label></td>
							<td class="windowbg2" width="50%">
								<input type="text" name="subject" id="subject" value="', $context['custom_form_settings']['subject'], '" />
							</td>
						</tr>
						<tr>
							<td class="windowbg2" valign="top" width="16"><a name="setting_exit" href="', $scripturl, '?action=helpadmin;help=CustomForm_submit_exit" onclick="return reqWin(this.href);" class="help"><img src="', $settings['images_url'], '/helptopics.gif" alt="', $txt['help'], '" border="0" align="top" /></a></td>
							<td class="windowbg2" valign="top"><label for="Form_exit">', $txt['CustomForm_exit'] , '</label></td>
							<td class="windowbg2" width="50%">
								<input type="text" name="form_exit" id="form_exit" value="', $context['custom_form_settings']['form_exit'], '" />
							</td>
						</tr>
						<tr>
							<td class="windowbg2" valign="top" width="16"><a name="setting_output" href="', $scripturl, '?action=helpadmin;help=CustomForm_output" onclick="return reqWin(this.href);" class="help"><img src="', $settings['images_url'], '/helptopics.gif" alt="', $txt['help'], '" border="0" align="top" /></a></td>
							<td class="windowbg2 valign="top"><label for="output"><u><strong>', $txt['CustomForm_output'] , ':</strong></u></label></td>
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
											', template_control_richedit('output', 'smileyBox_message', 'bbcBox_message'), '
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
function template_CustomForm_GeneralSettings()
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
				<div class="titlebg" style="padding: 4px;">' , (isset($modSettings['CustomForm_view_title']) && ($modSettings['CustomForm_view_title'] != '')) ? $modSettings['CustomForm_view_title'] : $txt['CustomForm_tabheader'] , '</div>
				<div style="padding: 2ex;" class="windowbg2">

					<table style="width:100%;background-color:#000000;" align="center">
						<tr class="windowbg">
							<td style="padding:4px;" colspan="3" align="center">
							<b>	' , $txt['CustomForm_thankyou'] , '</b>
							</td>
						</tr>

						<tr class="titlebg">
							<td style="padding:4px;" style="width:45%;" align="center">
							<a href="'.$scripturl.'">	' , $txt['CustomForm_forum'] , '</a>
							</td>
							<td style="padding:4px;" style="width:45%;" align="center">
							<a href="'.$scripturl.'?action=form;">	' , $txt['CustomForm_list'] , '</a>
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

// Begin: Custom Forms Template Centered
 
function form_template_center()
{
	global $context, $txt, $settings, $scripturl;

	//	Starting text for the form and tables. Don't muck with this unless you need to change the style!!!  ;)
	echo '
	<form action="', $context['post_url'], '" method="post" accept-charset="', $context['character_set'], '">
		<table width="80%" border="0" cellspacing="0" cellpadding="0" class="tborder" align="center">
			<tr>
				<td>
					<table border="0" cellspacing="0" cellpadding="4" width="100%">
						<tr class="titlebg">
							<td colspan="3">', $context['settings_title'], '</td>
						</tr>';

   // End of information in the top section.
	
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
		//	Output the start of the row, as well as a spacer column.
		echo '
						<tr class="windowbg2">
							<td class="windowbg2"></td>';
		
		//	Show the display text for this field.
		echo '
							<tr class="windowbg2"><td style="text-align:center" valign="top"><label for="', $field_name, '">', $field_data['text'], '</label></td></tr>
							<td class="windowbg2" style="text-align:center" width="100%">';

		// Show a check box.
		if ($field_data['type'] == 'checkbox')
			echo '
								<input type="checkbox" name="', $field_name, '" id="', $field_name, '" ', (($field_data['value']) ? ' checked="checked"' : ''), ' value="1" class="check" />';

		// Show a selection box.
		elseif ($field_data['type'] == 'selectbox')
		{
			echo '
								<select name="', $field_name, '" id="', $field_name, '" >';
			foreach ($field_data['data'] as $option)
				if ($option != 'required')
					echo '
									<option value="', $option, '"', ($option == $field_data['value'] ? ' selected="selected"' : ''), '>', $option, '</option>';
				else
					echo '
									<option value=""></option>';

			echo '
								</select>';
		}
		// Show a radio box.
		elseif ($field_data['type'] == 'radiobox')
		{

			foreach ($field_data['data'] as $option)
	  				if ($option != 'required')
 
			echo '
                  
                        <input type="radio" name="', $field_name, '" value="', $option , '">', $option , '<br />';
	  				else
			echo '
                        <option value=""></option>';
	 }
		// Large Text box?
		elseif ($field_data['type'] == 'largetextbox')
		{
			echo '
								<textarea rows="10" cols="100%" name="', $field_name, '" id="', $field_name, '">', $field_data['value'], '</textarea>';
		}
		// Show a Info box.
		elseif ($field_data['type'] == 'infobox')
		{
			echo '
			';
		}
		// Int, Float or text box?
		else
			echo '
								<input type="text" size="100%" name="', $field_name, '" id="', $field_name, '" value="', $field_data['value'], '" />';
								
		//	Show the 'required' asterix if necessary.
		if(!empty($field_data['required']))
			echo '
								<span ', !empty($field_data['failed']) ? 'style="color:#FF0000;"' : '' ,'> *</span>';		
		
		//	End the input column and the entire row.
		echo '
							</td>
						</tr>';
	}

	//    Show the "Required Fields" text down the bottom, show it in red if there was a failed submit.
   echo '
               <tr class="windowbg2">
                  <td colspan="3" style="text-align:center;', !empty($context['failed_form_submit']) ? 'color:#FF0000;' : '', '">
                     * ', $txt['CustomForm_required'], '
                  </td>
               </tr>';

	// Display visual verification on the form      
   if ($context['visual_verification'])
   {
	  echo '
      <tr><td class="windowbg2" colspan="3" align="center" valign="middle"><b>', $txt['verification'], ':</b></td><tr>
      <tr><td class="windowbg2" colspan="3" align="center" valign="middle">', template_control_verification($context['visual_verification_id'], 'all'), '</td></tr>';
   }               

   //  Here you can add information before the submit button.
   /*echo '
	 <tr>
	 		 <td colspan="3"  class="windowbg2">
			 <b><span style="color:red">Example of something before the submit button.</span></b>
			 </td>
   </tr>';*/   

	
	//	Output the save button, the end of the tables and the form.	
	echo '
						<tr>
							<td class="windowbg2" colspan="3" align="center" valign="middle"><input type="submit" value="', $txt['save'], '"', (!empty($context['save_disabled']) ? ' disabled="disabled"' : ''), ' /></td>
						</tr>';
						
  echo '
					
					</table>
				</td>
			</tr>
		</table>
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
	</form>';
}
// End: Custom Forms Template Centered

// Begin: Custom Forms Template Left
 
function form_template_left()
{
	global $context, $txt, $settings, $scripturl;

	//	Starting text for the form and tables. Don't muck with this unless you need to change the style!!!  ;)
	echo '
	<form action="', $context['post_url'], '" method="post" accept-charset="', $context['character_set'], '">
		<table width="80%" border="0" cellspacing="0" cellpadding="0" class="tborder" align="center">
			<tr>
				<td>
					<table border="0" cellspacing="0" cellpadding="4" width="100%">
						<tr class="titlebg">
							<td colspan="3">', $context['settings_title'], '</td>
						</tr>';
	
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
		//	Output the start of the row, as well as a spacer column.
		echo '
						<tr class="windowbg2">
							<td class="windowbg2"></td>';

		if ($field_data['type'] == 'infobox')
		echo '
						<td colspan="2" style="text-align:center" valign="top"><label for="', $field_name, '">', $field_data['text'], '</label></td>
						';
		else

		//	Show the display text for this field.
		echo '
							<td style="text-align:right" class="windowbg2" width="50%">';

		//	Show the 'required' asterix on the left side if necessary.
		if(!empty($field_data['required']))
			echo '
								<span style="text-align:center" ', !empty($field_data['failed']) ? 'style="color:#FF0000;"' : '' ,'> *</span>';

		// Show a check box.
		if ($field_data['type'] == 'checkbox')
			echo '
								<input type="checkbox" name="', $field_name, '" id="', $field_name, '" ', (($field_data['value']) ? ' checked="checked"' : ''), ' value="1" class="check" />';

		// Show a selection box.
		elseif ($field_data['type'] == 'selectbox')
		{
			echo '
								<select name="', $field_name, '" id="', $field_name, '" >';
			foreach ($field_data['data'] as $option)
				if ($option != 'required')
					echo '
									<option value="', $option, '"', ($option == $field_data['value'] ? ' selected="selected"' : ''), '>', $option, '</option>';
				else
					echo '
									<option value=""></option>';

			echo '
								</select>';
		}
		// Show a radio box.
		elseif ($field_data['type'] == 'radiobox')
		{

			foreach ($field_data['data'] as $option)
	  				if ($option != 'required')
 
			echo '
                  
                        <input type="radio" name="', $field_name, '" value="', $option , '">', $option , '<br />';
	  				else
			echo '
                        <option style="text-align:right" value=""></option>';
	 }
		// Large Text box?
		elseif ($field_data['type'] == 'largetextbox')
		{
			echo '
								<textarea rows="10" cols="48%" name="', $field_name, '" id="', $field_name, '">', $field_data['value'], '</textarea>';
		}
		// Show a Info box.
		elseif ($field_data['type'] == 'infobox')
		{
			echo '
			';
		}
		// Int, Float or text box?
		else
			echo '
								<input type="text" size="50%" name="', $field_name, '" id="', $field_name, '" value="', $field_data['value'], '" />';

		//	End the input column and the entire row.
		
		if ($field_data['type'] == 'infobox')
		echo '
						';
		else

		//	Show the display text for this field.
		echo '
							<td valign="top"><label for="', $field_name, '">', $field_data['text'], '</label></td>
							</td>
						</tr>';
	}

	//    Show the "Required Fields" text down the bottom, show it in red if there was a failed submit.
   echo '
               <tr class="windowbg2">
                  <td colspan="3" style="text-align:center;', !empty($context['failed_form_submit']) ? 'color:#FF0000;' : '', '">
                     * ', $txt['CustomForm_required'], '
                  </td>
               </tr>';

	// Display visual verification on the form      
   if ($context['visual_verification'])
   {
	  echo '
      <tr><td class="windowbg2" colspan="3" align="center" valign="middle"><b>', $txt['verification'], ':</b></td><tr>
      <tr><td class="windowbg2" colspan="3" align="center" valign="middle">', template_control_verification($context['visual_verification_id'], 'all'), '</td></tr>';
   }               
	
	//	Output the save button, the end of the tables and the form.	
	echo '
						<tr>
							<td class="windowbg2" colspan="3" align="center" valign="middle"><input type="submit" value="', $txt['save'], '"', (!empty($context['save_disabled']) ? ' disabled="disabled"' : ''), ' /></td>
						</tr>';
						
  echo '
					
					</table>
				</td>
			</tr>
		</table>
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
	</form>';
}
// End: Custom Forms Template Left

/* Example: How to edit the custom forms template.
For a preview of how you can use custom templates enter "example" in the Custom Template Function field of a form.

Copy the entire Custom Forms Template Example from beginning to end.

Every where you see "Example of Something..." you can alter however you want. You can even use HTML tags to give it a little more style.
It is recommended that you have a decent knowledge of HTML, XML, and PHP before you do anything too drastic.

Re-name "function form_template_example_form()" to "function form_your_name()"

Note that your name should be something short, all lower case, and do not use special characters like # & * @ [ etc.

Paste your edited form before the ?> at the end of the CustomForm.template.php

Enter your_name in the Custom Template Function field of the form you want to use your template in. 

View your form.*/

// Begin: Custom Forms Template Example
 
function form_template_example()
{
	global $context, $txt, $settings, $scripturl;

	//	Starting text for the form and tables. Don't muck with this unless you need to change the style!!!  ;)
	echo '
	<form action="', $context['post_url'], '" method="post" accept-charset="', $context['character_set'], '">
		<table width="80%" border="0" cellspacing="0" cellpadding="0" class="tborder" align="center">
			<tr>
				<td>
					<table border="0" cellspacing="0" cellpadding="4" width="100%">
						<tr class="titlebg">
							<td colspan="3">', $context['settings_title'], '</td>
						</tr>';
	
   //   Here you can add information at the top of your form, if you want to...
   echo '
	 <tr class="windowbg2">
	 <b><span style="color:red">Example of something above the form title area.</span></b>
	 </tr>
	 <tr>
	 		 <td colspan="3"  class="windowbg2">
			 <b><span style="color:red">Example of something at the top of the form.</span></b>
			 </td>
	 </tr>';
   // End of information in the top section.
	
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
		//	Output the start of the row, as well as a spacer column.
		echo '
						<tr class="windowbg2">
							<td class="windowbg2"></td>';
		
		if ($field_data['type'] == 'infobox')
		echo '
						<td colspan="2" valign="top"><label for="', $field_name, '">', $field_data['text'], '</label></td>
						';
		else

		//	Show the display text for this field.
		echo '
							<td valign="top"><label for="', $field_name, '">', $field_data['text'], '</label></td>
							<td class="windowbg2" width="50%">';

		// Show a check box.
		if ($field_data['type'] == 'checkbox')
			echo '
								<input type="checkbox" name="', $field_name, '" id="', $field_name, '" ', (($field_data['value']) ? ' checked="checked"' : ''), ' value="1" class="check" />';

		// Show a selection box.
		elseif ($field_data['type'] == 'selectbox')
		{
			echo '
								<select name="', $field_name, '" id="', $field_name, '" >';
			foreach ($field_data['data'] as $option)
				if ($option != 'required')
					echo '
									<option value="', $option, '"', ($option == $field_data['value'] ? ' selected="selected"' : ''), '>', $option, '</option>';
				else
					echo '
									<option value=""></option>';

			echo '
								</select>';
		}
		// Show a radio box.
		elseif ($field_data['type'] == 'radiobox')
		{

			foreach ($field_data['data'] as $option)
	  				if ($option != 'required')
 
			echo '
                  
                        <input type="radio" name="', $field_name, '" value="', $option , '">', $option , '<br />';
	  				else
			echo '
                        <option value=""></option>';
	 }
		// Large Text box?
		elseif ($field_data['type'] == 'largetextbox')
		{
			echo '
								<textarea rows="10" cols="50%" name="', $field_name, '" id="', $field_name, '">', $field_data['value'], '</textarea>';
		}
		// Show a Info box.
		elseif ($field_data['type'] == 'infobox')
		{
			echo '
			';
		}
		// Int, Float or text box?
		else
			echo '
								<input type="text" size="50%" name="', $field_name, '" id="', $field_name, '" value="', $field_data['value'], '" />';
								
		//	Show the 'required' asterix if necessary.
		if(!empty($field_data['required']))
			echo '
								<span ', !empty($field_data['failed']) ? 'style="color:#FF0000;"' : '' ,'> *</span>';		
		
		//	End the input column and the entire row.
		echo '
							</td>
						</tr>';
	}

	//    Show the "Required Fields" text down the bottom, show it in red if there was a failed submit.
   echo '
               <tr class="windowbg2">
                  <td colspan="3" style="text-align:center;', !empty($context['failed_form_submit']) ? 'color:#FF0000;' : '', '">
                     * ', $txt['CustomForm_required'], '
                  </td>
               </tr>';

	// Display visual verification on the form      
   if ($context['visual_verification'])
   {
	  echo '
      <tr><td class="windowbg2" colspan="3" align="center" valign="middle"><b>', $txt['verification'], ':</b></td><tr>
      <tr><td class="windowbg2" colspan="3" align="center" valign="middle">', template_control_verification($context['visual_verification_id'], 'all'), '</td></tr>';
   }               

   //  Here you can add information before the submit button.
   echo '
	 <tr>
	 		 <td colspan="3"  class="windowbg2">
			 <b><span style="color:red">Example of something before the submit button.</span></b>
			 </td>
   </tr>';   

	
	//	Output the save button, the end of the tables and the form.	
	echo '
						<tr>
							<td class="windowbg2" colspan="3" align="center" valign="middle"><input type="submit" value="', $txt['save'], '"', (!empty($context['save_disabled']) ? ' disabled="disabled"' : ''), ' /></td>
						</tr>';
						
  //   Here you can add information below the submit button.
  echo '
	<tr>
			<td colspan="3"  class="windowbg2">
			<b><span style="color:red">Example of something after the submit button.</span></b>
			</td>
	</tr>
							
					</table>
				</td>
			</tr>
		</table>
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
	</form>';
}
// End: Custom Forms Template Example
?>
