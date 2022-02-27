<?php

//	This function shows the custom forms and submits them.
function CustomForm()
{
	global $smcFunc, $context, $txt, $scripturl, $sourcedir, $user_info, $modSettings;

	// Generate a visual verification code to make sure the user is no bot.
	$context['require_verification'] =
		$user_info['is_guest'] || !$user_info['is_mod'] && !$user_info['is_admin'] && !empty($modSettings['posts_require_captcha']) && ($user_info['posts'] < $modSettings['posts_require_captcha']);
	if ($context['require_verification'])
	{
		require_once($sourcedir . '/Subs-Editor.php');
		$verificationOptions = array(
			'id' => 'customform',
		);
		$context['visual_verification'] = create_control_verification($verificationOptions);
	}

	//	Are we looking for the thank you page.
	if (isset($_REQUEST['thankyou']))
	{
		$context['sub_template'] = 'ThankYou';
		loadTemplate('CustomForm');
		loadLanguage('CustomForm');
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
			{
				$row['type2'] = strtr($row['type'], [
					'largetextbox' =>'textarea',
					'textbox' =>'text',
					'checkbox' =>'check',
					'selectbox' =>'select',
					'float' =>'text',
					'int' =>'text',
					'radiobox' =>'radio',
					'infobox' =>'info'
				]);
				$data[] = $row;
			}

			//	Free the db request.
			$smcFunc['db_free_result']($request);

			//	Do we have fields attached to this form? If not then redirect the user to the form view page.
			if (empty($data))
				redirectExit("action=form;");

			require_once($sourcedir . '/Class-CustomForm.php');

			//	Do we need to submit this form?
			if (isset($_POST['n']))
			{
				$vars = array();
				$replace = array();
				$post_errors = array();
				if ('' != ($sc_error = checkSession('post', '', false)))
					$post_errors[] = array(['error_' . $sc_error, '']);
				if ($context['require_verification'])
				{
					require_once($sourcedir . '/Subs-Editor.php');
					$verificationOptions = array(
						'id' => 'customform',
					);
					if (true !== ($verification_errors = create_control_verification($verificationOptions, true)))
						foreach ($verification_errors as $verification_error)
							$post_errors[] = ['error_' . $verification_error, ''];
				}

				//	Check for valid post data from the forms fields.
				foreach ($data as $field)
				{
					$class_name = 'CustomForm_' . $field['type2'];
					if (!class_exists($class_name))
						fatal_error('Param "' . $field['type2'] . '" not found for field "' . $field['text'] . '" at ID #' . $field['id_field'] . '.', false);

					$type = new $class_name($field, $_POST['CustomFormField'][$field['id_field']] ?? '');
					$type->setOptions();
					if (!$type->validate())
					{
						$post_errors['id_field_' . $field['id_field']] = $type->getError();
						continue;
					}

					//	Add this fields value to the list of variables for the output post.
					$vars[$field['title']] = $type->getValue();
				}

				//	Do we have completly valid field data?
				if ($post_errors === [])
				{
					require_once($sourcedir . '/Subs-Post.php');
					$msgOptions = array(
						'id' => 0,
						'subject' => customform_replace_vars($subject, $vars),
						'icon' => $icon,
						'body' => customform_replace_vars($output, $vars),
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
				$context['post_errors'] = $post_errors;
			}

			//	Otherwise we shall show the submit form page.
			$context['fields'] = array();

			//	Okay, lets format the field data.
			foreach ($data as $field)
			{
				$value = isset($_POST['CustomFormField'][$field['id_field']]) ? $_POST['CustomFormField'][$field['id_field']] : '';
				$class_name = 'CustomForm_' . $field['type2'];
				if (!class_exists($class_name))
					fatal_error('Param "' . $field['type2'] . '" not found for field "' . $field['text'] . '" at ID #' . $field['id_field'] . '.', false);

				$type = new $class_name($field, $value, !empty($value));
				$type->setOptions();
				$type->setHtml();

				$context['fields'][$field['title']] = array(
					'text' => $field['text'],
					'type' => $field['type'],
					'html' => $type->getInputHtml(),
					'required' => $type->isRequired(),
					'failed' => isset($post_errors['id_field_' . $field['id_field']]),
				);
			}

			//	Do we have fields data? If not then redirect the user to the form view page.
			if (empty($context['fields']))
				redirectExit("action=form;");

			//	Load the language files.
			loadLanguage('CustomForm+Post+Errors');

			//	Setup and load the necessary template related stuff.
			$context['page_title'] = $form_title;
			$context['form_id'] = $form_id;
			$context['failed_form_submit'] = !empty($post_errors);
			$template_function = 'template_' . $form_data['template_function'];
			$context['template_function'] = function_exists('form_' . $template_function) ? $template_function : 'template_submit_form';
			$context['sub_template'] = 'submit_form';
			loadTemplate('CustomForm');
			$context['template_layers'][] = function_exists($template_function . '_above') && function_exists($template_function . '_below') ? $context['template_function'] : 'form';
			if (!empty($post_errors))
				$context['template_layers'][] = 'errors';
		}
		//	If not then fall to the default view form page, with the list of forms.
		else
			listCustomForm();
}

function listCustomForm()
{
	global $context, $modSettings, $scripturl, $smcFunc, $sourcedir, $txt;

	if (!allowedTo('customform_view_perms'))
		redirectExit();

	loadLanguage('CustomForm');
	loadTemplate('CustomForm');

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
	$context['forms'] = array();
	while (list ($id_form, $title) = $smcFunc['db_fetch_row']($request))
		if (allowedTo('custom_forms_' . $id_form) && in_array($id_form, $cf))
			$context['forms'][] = [$id_form, $title, ''];
	$smcFunc['db_free_result']($request);

	$context['template_layers'][] = 'main';
	$context['page_title'] = !empty($modSettings['customform_view_title'])
		? $modSettings['customform_view_title']
		: $txt['customform_tabheader'];
	$context['customform_view_text'] = $modSettings['customform_view_text'] ?? '';
}