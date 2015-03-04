<?php
	if (!defined('SMF'))
		die('Hacking attempt...');

//	This function shows the custom forms and submits them.
function CustomForm()
{
	global $smcFunc, $context, $txt, $scripturl, $sourcedir, $user_info, $modSettings;
	
	//	Do we have a valid form id?
	if(isset($_REQUEST['id'])
	&& intval($_REQUEST['id']))
	{
		$form_id = intval($_REQUEST['id']);
		
		//	Wait a second... Are you even allowed to use this form?
		if(!allowedTo('custom_forms_'.$form_id))
			redirectExit("action=form");
		
		//	Get the data about the current form.
		$request = $smcFunc['db_query']('','
			SELECT title, output, subject, id_board, template_function
			FROM {db_prefix}cf_forms
			WHERE id_form = {int:id}',
			array(
				'id' => $form_id,
			)
		);
		
		//	Did we get some form data? If not then redirect the user to the form view page.
		if(!($form_data = $smcFunc['db_fetch_assoc']($request)))
			redirectExit("action=form;");
			
		$output = $form_data['output'];
		$subject = $form_data['subject'];
		$board = $form_data['id_board'];
		$form_title = $form_data['title'];
		
		//	Free the db request.
		$smcFunc['db_free_result']($request);		

		//	Get a list of the current fields attached to this form.
		$request = $smcFunc['db_query']('','
			SELECT title, text, type, type_vars
			FROM {db_prefix}cf_fields
			WHERE id_form = {int:id}
			AND title != \'\'
			AND text != \'\'
			AND type != \'\'
			ORDER BY ID_FIELD',
			array(
				'id' => $form_id,
			)
		);
		
		$data = array();
		//	Get all of data from the db query.
		while($row = $smcFunc['db_fetch_assoc']($request))
			$data[] = $row;
		
		//	Free the db request.
		$smcFunc['db_free_result']($request);
		
		//	Do we have fields attached to this form? If not then redirect the user to the form view page.
		if(empty($data))
			redirectExit("action=form;");
			
		$fail_submit = false;
		
		//	Do we need to submit this form?
		if(isset($_GET['submit']))
		{
			$vars = array();
			$replace = array();
			$i = -1;
			
			//	Check for valid post data from the forms fields.
			foreach($data as $field)
			{
				$i++;
				$value = '';
				$size = '';
				$default = '';
				
				$temp = ($field['type_vars'] != '') ? explode(',', $field['type_vars']) : array();
				$type_vars = array();
				
				//	Remove whitespace from temp, to avoid unwanted issues.
				for($p=0; $p < count($temp); $p++)
					$temp[$p] = trim($temp[$p]);
				
				//	Go through all of the type_vars to format them correctly.
				if(!empty($temp))
					foreach($temp as $var)
					{
						//	Check for a size value.
						if(substr($var, 0, 5) == 'size=')
							$size = intval(substr($var, 5));
							
						//	Check for a default value
						if(substr($var, 0, 8) == 'default=')
							$default = substr($var, 8);
						
						//	Add them to the vars list.
						if($var != '')
							$type_vars[] = $var;
					}
				
				$required = in_array('required', $temp);
				
				//	Go through each of the possible types of fields.
				switch ($field['type'])
				{
					case 'checkbox':
						$value = isset($_REQUEST[$field['title']]) ? $_REQUEST[$field['title']] : false;
						//	Replace the normal true/false values if we have special type_var values.
						if(isset($type_vars[0]) && ($value))
							$value = $type_vars[0];
						elseif(isset($type_vars[1]) && !($value))
							$value = $type_vars[1];
						elseif($value)
							$value = $txt['yes'];
						else
							$value = $txt['no'];
					break;
					case 'selectbox':
						//	Skip this field, if there are no select values.
						if(empty($type_vars))
							continue 2;
						$value = isset($_REQUEST[$field['title']]) ? $_REQUEST[$field['title']] : '';
						//	Make sure that the selectbox value is in the array, otherwise stop those dodgy users from passing weird values. ;)
						if(!in_array($value, $type_vars))
							$value = '';
					break;
					case 'int':
						$value = isset($_REQUEST[$field['title']]) ? intval($_REQUEST[$field['title']]) : '';
						//	If value is empty then set it to the default.
						if(($value == '')
						&& !$required)
							$value = $default;
						//	Restrict the length of value if necessary.
						if(($size != ''))
							$value = substr($value, 0, $size);
					break;
					case 'float':
						$value = isset($_REQUEST[$field['title']]) ? floatval($_REQUEST[$field['title']]) : '';
						//	If value is empty then set it to the default.
						if(($value == '')
						&& !$required)
							$value = $default;
						//	Restrict the length of the float value if necessary.
						if(($size != ''))
							$value = rtrim(substr($value, 0, $size), '.');
					break;
					//	Do the formating for both large and normal textboxes. 
					default:
						$value = isset($_REQUEST[$field['title']]) ? $_REQUEST[$field['title']] : '';
						//	If value is empty then set it to the default.
						if(($value == '')
						&& !$required)
							$value = $default;
						//	Only bother with further formating if there is now some text. - This avoids huge errors with the parse_bbc() function returning all bbc. 
						if(!($value == ''))
						{
							//	Remove all bbc code if we don't need to parse it.
							if(!in_array('parse_bbc', $type_vars))
								$value = strip_tags(parse_bbc($value, false), '<br>');
							//	Restrict the length of value if necessary, can stuff up html, but hey...
							if(($size != ''))
								$value = substr($value, 0, $size);
						}
				}
				
				//	Do we have an invalid value? Is this field required?
				if(($required
				&& (($value == '') || ($value == 0))
				&& ($field['type'] != 'checkbox'))
				//	Failing for selectboxes is far more simple, If there is no valid value, it fails.
				|| (($field['type'] == 'selectbox') && ($value == '')))
				{
					//	Do the 'fail form/field' stuff.
					$data[$i]['failed'] = true;
					$fail_submit = true;
					continue;
				}
				
				//	Add this fields value to the list of variables for the output post.
				$vars[] = '/\{'.$field['title'].'\}/';
				$replace[] = $value;
				
				//	Also add this data back into the data array, just in case we can't actually submit the form.
				$data[$i]['value'] = $value;
				
				//	Do a small fix for the last line, if this is a checkbox.
				if($field['type'] == 'checkbox')
					$data[$i]['value'] = isset($_REQUEST[$field['title']]) ? $_REQUEST[$field['title']] : false;
				//	Do a small fix for the last line, if this is a largetextbox.
				if(($field['type'] == 'largetextbox'))
					$data[$i]['value'] = isset($_REQUEST[$field['title']]) ? $_REQUEST[$field['title']] : '';
			}
			
			//	Do we have completly valid field data?
			if(!$fail_submit)
			{
				require_once($sourcedir.'/Subs-Post.php');

				//	Replace all vars with their correct value, for both the message and the subject.
				$output = preg_replace($vars, $replace, $output);
				$subject = preg_replace($vars, $replace, $subject);
				
				// Collect all necessary parameters for the creation of the post.
				$msgOptions = array(
					'id' =>  0,
					'subject' => $subject,
					'body' => $output,
					'smileys_enabled' => true,
				);
				
				$topicOptions = array(
					'id' => 0,
					'board' => $board,
					'mark_as_read' => true,
				);
				
				$posterOptions = array(
					'id' => $user_info['id'],
				);
				
				//	Finally create the post!!! :D
				createPost($msgOptions, $topicOptions, $posterOptions);
				
				//	Redirect this user as well.
				redirectexit('board=' . $board . '.0');
			}
		}
		
		//	Otherwise we shall show the submit form page.
		$context['fields'] = array();
		
		//	Okay, lets format the field data.
		foreach($data as $field)
		{
			
			$size = false;
			$type_vars = ($field['type_vars'] != '') ? explode(',', $field['type_vars']) : array();
			$vars = array();
			$required = false;
			
			//	Go through all of the type_vars to format them correctly.
			if(!empty($type_vars))
				foreach($type_vars as $var)
				{
					//	Remove whitespace from vars, to avoid unwanted issues.
					$var = trim($var);
					//	Add them to the vars list, in the correct format for the template.
					if($var != '')
						$vars[] = $var;
					//	Check to see if this field is required.
					if($var == 'required')
						$required = true;
				}
			
			//	Make sure that we have valid options, if this is a selectbox.
			if(($field['type'] == 'selectbox')
			&& empty($vars))
				continue;
			
			//	Store any previous values for the template to look after.
			if(isset($field['value']))
				$modSettings[$field['title']] = $field['value'];
			
			//	Finally put the data for this field into the $context['field'] array for the 'submit form' template functions.
			$context['fields'][$field['title']] = array(
				'text' => $field['text'],
				'type' => $field['type'],
				'data' => $vars,
				'value' => isset($field['value']) ? $field['value'] : '',
				'required' => $required,
				'failed' => isset($field['failed']),
			);
		}
		
		//	Do we have fields data? If not then redirect the user to the form view page.
		if(empty($context['fields']))
			redirectExit("action=form;");
		
		//	Load the language files.
		loadLanguage('Modifications');
		
		//	Setup and load the necessary template related stuff.
		$context['settings_title'] = '<a href="'.$scripturl.'?action=form;">'.((isset($modSettings['CustomForm_view_title']) && ($modSettings['CustomForm_view_title'] != '')) ? $modSettings['CustomForm_view_title'] : $txt['CustomForm_tabheader']) . '</a> : ' . $form_title;
		$context['failed_form_submit'] = $fail_submit;
		$context['template_function'] = $form_data['template_function'];
		$context['post_url'] = $scripturl.'?action=form;id='.$form_id.';submit;';
		$context['sub_template'] = 'submit_form';
		loadTemplate('CustomForm');
	}
	//	If not then fall to the default view form page, with the list of forms.
	else
	{
		//	Wait a second... Are you even allowed to view the form list?
		if(!allowedTo('CustomForm_view_perms'))
			redirectExit();
		
		//	Declare the array of data which we need to pass to the template.
		$context['custom_forms_list'] = array();
		
		//	Firstly get a list of all the fields from the cf_fields table.
		$request = $smcFunc['db_query']('','
			SELECT id_form
			FROM {db_prefix}cf_fields
			WHERE title != \'\'
			AND text != \'\'
			AND type != \'\''
		);
		
		$forms = array();
		
		while($row = $smcFunc['db_fetch_assoc']($request))
			$forms[] = $row['id_form'];
		$smcFunc['db_free_result']($request);
		
		//	Get the data from the cf_forms table.
		$request = $smcFunc['db_query']('','
			SELECT f.id_form, f.title, b.name, b.id_board
			FROM {db_prefix}cf_forms f, {db_prefix}boards b
			WHERE b.id_board = f.id_board
			AND b.redirect = \'\''
		);
		
		//	Go through all of the forms and add them to the list.
		while($row = $smcFunc['db_fetch_assoc']($request))
		{
			//	Wait. Are you allowed to view/use this form?
			if(!allowedTo('custom_forms_'.$row['id_form']))
				continue;
			
			//	Did we get some fields from this form?
			if(!in_array($row['id_form'], $forms))
				continue;
				
			//	Add this forms data, for the template to show.
			$context['custom_forms_list'][] = array(
				'id' => $row['id_form'],
				'title' => $row['title'],
				'id_board' => $row['id_board'],
				'board' => $row['name'],
			);
		}
		
		//	Free the db request.
		$smcFunc['db_free_result']($request);
		
		//	Finally load the necessary template for this action.	
		$context['sub_template'] = 'FormList';
		loadTemplate('CustomForm');
	}
	
	//	Set the page title, just for lolz! :D
	$context['page_title'] = (isset($modSettings['CustomForm_view_title']) && ($modSettings['CustomForm_view_title'] != '')) ? $modSettings['CustomForm_view_title'] : $txt['CustomForm_tabheader'];
}
?>