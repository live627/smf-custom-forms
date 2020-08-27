<?php

//	This function shows the custom forms and submits them.
function CustomForm()
{
	global $smcFunc, $context, $txt, $scripturl, $sourcedir, $user_info, $modSettings;

	global $txt, $context, $sourcedir, $modSettings, $board;


	// Generate a visual verification code to make sure the user is no bot.
	$context['require_verification'] =
		$user_info['is_guest'] || !$user_info['is_mod'] && !$user_info['is_admin'] && !empty($modSettings['posts_require_captcha']) && ($user_info['posts'] < $modSettings['posts_require_captcha']);
	if ($context['require_verification'])
	{
		require_once($sourcedir . '/Subs-Editor.php');
		$verificationOptions = array(
			'id' => 'register',
		);
		$context['visual_verification'] = create_control_verification($verificationOptions);
		$context['visual_verification_id'] = $verificationOptions['id'];
	}
	// Otherwise we have nothing to show.
	else
		$context['visual_verification'] = false;

	//	Are we looking for the thank you page.
	if (isset($_REQUEST['thankyou']))
	{
		$context['sub_template'] = 'ThankYou';
		loadTemplate('CustomForm');
	}
	else //	Do we have a valid form id?
		if (isset($_REQUEST['n']))
		{
			$form_id = intval($_REQUEST['n']);

			//	Wait a second... Are you even allowed to use this form?
			if (!allowedTo('custom_forms_' . $form_id))
				redirectExit("action=form");

			//	Get the data about the current form.
			$request = $smcFunc['db_query'](
				'',
				'
			SELECT title, output, subject, id_board, icon, form_exit, template_function
			FROM {db_prefix}cf_forms
			WHERE id_form = {int:id}',
				array(
					'id' => $form_id,
				)
			);

			//	Did we get some form data? If not then redirect the user to the form view page.
			if (!($form_data = $smcFunc['db_fetch_assoc']($request)))
				redirectExit("action=form;");

			$output = $form_data['output'];
			$exit = $form_data['form_exit'];
			$subject = $form_data['subject'];
			$icon = $form_data['icon'];
			$board = $form_data['id_board'];
			$form_title = $form_data['title'];

			//	Free the db request.
			$smcFunc['db_free_result']($request);

			//	Get a list of the current fields attached to this form.
			$request = $smcFunc['db_query'](
				'',
				'
			SELECT id_field, title, text, type, type_vars
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
			while ($row = $smcFunc['db_fetch_assoc']($request))
				$data[] = $row;

			//	Free the db request.
			$smcFunc['db_free_result']($request);

			//	Do we have fields attached to this form? If not then redirect the user to the form view page.
			if (empty($data))
				redirectExit("action=form;");

			$fail_submit = false;
			require_once($sourcedir . '/Class-CustomForm.php');

			//	Do we need to submit this form?
			if (isset($_GET['submit']))
			{
				$vars = array();
				$replace = array();
				$i = -1;

				//	Check for valid post data from the forms fields.
				foreach ($data as $field)
				{
					$i++;
					$value = isset($_POST['CustomFormField'][$field['id_field']]) ? $_POST['CustomFormField'][$field['id_field']] : '';
					$class_name = 'CustomForm_' . $field['type'];
					if (!class_exists($class_name))
						fatal_error('Param "' . $field['type'] . '" not found for field "' . $field['text'] . '" at ID #' . $field['id_field'] . '.', false);

					$type = new $class_name($field, $value, !empty($value));
					$type->setOptions();
					$type->validate();
					if (false !== ($err = $type->getError()))
					{
						$post_errors[] = $err;
						//	Do the 'fail form/field' stuff.
						$data[$i]['failed'] = true;
						$fail_submit = true;
						continue;
					}

					//	Add this fields value to the list of variables for the output post.
					$vars[] = '/\{' . $field['title'] . '\}/';
					$replace[] = str_replace('$', '\$', $value);

					//    {{ }} Syntax: Setup REGEX for removing entire {{ }} string or just stripping the outermost { }, depending upon the replacement value being blank or not
					if ($value == '')
					{
						$vars_blank[] = '/\{[^\{\}]*\{' . $field['title'] . '\}[^\{\}]*\}/';
						$vars_non_blank[] = '//';
					}
					else
					{
						$vars_blank[] = '//';
						$vars_non_blank[] = '/\{[^\{\}]*\{' . $field['title'] . '\}[^\{\}]*\}/';
					}
				}

				// Check whether the visual verification code was entered correctly.
				$context['require_verification'] =
					$user_info['is_guest'] || !$user_info['is_mod'] && !$user_info['is_admin'] && !empty($modSettings['posts_require_captcha']) && ($user_info['posts'] < $modSettings['posts_require_captcha']);
				if ($context['require_verification'])
				{
					require_once($sourcedir . '/Subs-Editor.php');
					$verificationOptions = array(
						'id' => 'register',
					);
					$context['visual_verification'] = create_control_verification($verificationOptions, true);

					if (is_array($context['visual_verification']))
					{
						loadLanguage('Errors');
						foreach ($context['visual_verification'] as $error)
							fatal_error($txt['error_' . $error], false);
					}
				}

				//	Do we have completly valid field data?
				if (!$fail_submit)
				{
					require_once($sourcedir . '/Subs-Post.php');

					//    {{ }} Syntax: Strip out everything in {{ }} if value is blank
					$output = preg_replace($vars_blank, '', $output);
					$subject = preg_replace($vars_blank, '', $subject);

					//    {{ }} Syntax: Remove outside brackets if value is not blank
					$output = preg_replace_callback(
						$vars_non_blank,
						function ($matches)
						{
							return substr($matches[0],1,-1);
						},
						$output
					);
					$subject = preg_replace_callback(
						$vars_non_blank,
						function ($matches)
						{
							return substr($matches[0],1,-1);
						},
						$subject
					);

					//	Replace all vars with their correct value, for both the message and the subject.
					$output = preg_replace($vars, $replace, $output);
					$subject = preg_replace($vars, $replace, $subject);

					// Collect all necessary parameters for the creation of the post.
					$msgOptions = array(
						'id' => 0,
						'subject' => $subject,
						'icon' => $icon,
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
					if ($exit == 'board' || $exit == '')
						redirectexit('board=' . $board . '.0');
					elseif ($exit == 'forum')
						redirectExit();
					elseif ($exit == 'form')
						redirectExit("action=form;");
					elseif ($exit == 'thanks')
						redirectExit("action=form;thankyou");
					else
						redirectexit("$exit");
				}
			}

			//	Otherwise we shall show the submit form page.
			$context['fields'] = array();

			//	Okay, lets format the field data.
			foreach ($data as $field)
			{
				$value = isset($_POST['CustomFormField'][$field['id_field']]) ? $_POST['CustomFormField'][$field['id_field']] : '';
				$class_name = 'CustomForm_' . $field['type'];
				if (!class_exists($class_name))
					fatal_error('Param "' . $field['type'] . '" not found for field "' . $field['text'] . '" at ID #' . $field['id_field'] . '.', false);

				$type = new $class_name($field, $value, !empty($value));
				$type->setOptions();

				$size = false;
				$type_vars = ($field['type_vars'] != '') ? explode(',', $field['type_vars']) : array();
				$vars = array();
				$required = false;

				//	Go through all of the type_vars to format them correctly.
				if (!empty($type_vars))
					foreach ($type_vars as $var)
					{
						//	Remove whitespace from vars, to avoid unwanted issues.
						$var = trim($var);
						//	Add them to the vars list, in the correct format for the template.
						if ($var != '')
							$vars[] = $var;
						//	Check to see if this field is required.
						if ($var == 'required')
							$required = true;
					}

				//	Make sure that we have valid options, if this is a selectbox.
				if (($field['type'] == 'selectbox' || $field['type'] == 'radiobox') && empty($vars))
					continue;

				$context['fields'][$field['title']] = array(
					'text' => $field['text'],
					'type' => $field['type'],
					'html' => $type->getInputHtml(),
					'required' => $required,
					'failed' => isset($field['failed']),
				);
			}

			//	Do we have fields data? If not then redirect the user to the form view page.
			if (empty($context['fields']))
				redirectExit("action=form;");

			//	Load the language files.
			loadLanguage('CustomForm+Post');

			//	Setup and load the necessary template related stuff.
			$context['settings_title'] =
				'<a href="' . $scripturl . '?action=form;">' . ((isset($modSettings['customform_view_title']) && ($modSettings['customform_view_title'] != '')) ? $modSettings['customform_view_title'] : $txt['customform_tabheader']) . '</a> : ' . $form_title;
			$context['failed_form_submit'] = $fail_submit;
			$context['template_function'] = $form_data['template_function'];
			$context['post_url'] = $scripturl . '?action=form;n=' . $form_id . ';submit;';
			$context['sub_template'] = 'submit_form';
			loadTemplate('CustomForm');
		}
		//	If not then fall to the default view form page, with the list of forms.
		else
			listCustomForm();

	//	Set the page title, just for lolz! :D
	$context['page_title'] = !empty($modSettings['customform_view_title'])
		? $modSettings['customform_view_title']
		: $txt['customform_tabheader'];
}

//	Fucntion to produce a list of custom forms.
function list_CustomForms()
{
	global $smcFunc;

	$request = $smcFunc['db_query']('', '
		SELECT id_form
		FROM {db_prefix}cf_fields
		WHERE title != \'\'
		AND text != \'\'
		AND type != \'\''
	);

	$cf = array();
	while (list ($id_form) = $smcFunc['db_fetch_row']($request))
		if (allowedTo('custom_forms_' . $id_form) && !in_array($id_form, $cf))
			$cf[] = $id_form;
	$smcFunc['db_free_result']($request);

	//	Get the data from the cf_forms table.
	$request = $smcFunc['db_query']('', '
		SELECT f.id_form, f.title, b.name, b.id_board
		FROM {db_prefix}cf_forms f, {db_prefix}boards b
		WHERE b.id_board = f.id_board
		AND b.redirect = \'\''
	);

	//~ //	Go through all of the forms and add them to the list.
	//~ $request = $smcFunc['db_query']('', '
		//~ SELECT id_form, title
		//~ FROM {db_prefix}cf_forms AS f
		//~ WHERE title != {string:empty_string}
		//~ WHERE NOT EXISTS
		//~ (
			//~ SELECT * FROM {db_prefix}cf_fields AS d
			//~ WHERE d.id_form = f.id_form
		//~ and title != \'\'
		//~ AND text != \'\'
		//~ AND type != \'\'
		//~ )',
		//~ array(
			//~ 'empty_string' => '',
		//~ )
	//~ );
	$forms = array();
	while (list ($id_form, $title) = $smcFunc['db_fetch_row']($request))
		if (allowedTo('custom_forms_' . $id_form) && in_array($id_form, $cf))
			$forms[] = array(
				'id' => $id_form,
				'title' => $title,
			);
	$smcFunc['db_free_result']($request);

	return $forms;
}

function listCustomForm()
{
	global $context, $modSettings, $scripturl, $smcFunc, $sourcedir, $txt;

	if (!allowedTo('customform_view_perms'))
		redirectExit();

	loadLanguage('CustomForm');
	$listOptions = array(
		'id' => 'menu_list',
		'title' => !empty($modSettings['customform_view_title'])
			? $modSettings['customform_view_title']
			: $txt['customform_tabheader'],
		'base_href' => $scripturl . '?action=form',
		'get_items' => array(
			'function' => 'list_CustomForms',
		),
		'no_items_label' => $txt['customform_list_noelements'],
		'columns' => array(
			'name' => array(
				'data' => array(
					'sprintf' => array(
						'format' => '<a href="' . strtr($scripturl, array('%' => '%%')) . '?action=form;n=%d">%s</a>',
						'params' => array(
							'id' => false,
							'title' => true,
						),
					),
					'style' => 'padding: 0.7em',
				),
			),
		),
	);
	if (!empty($modSettings['customform_view_text']))
		$listOptions['additional_rows'] = array(
			array(
				'position' => 'after_title',
				'value' => $modSettings['customform_view_text']
			),
		);

	require_once($sourcedir . '/Subs-List.php');
	createList($listOptions);

	$context['sub_template'] = 'show_list';
	$context['default_list'] = 'menu_list';