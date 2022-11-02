<?php

declare(strict_types=1);

/**
 * @package   Ultimate Menu mod
 * @version   3.0.0
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2014, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

function CustomForm()
{
	global $smcFunc, $context, $txt, $scripturl, $user_info, $modSettings;

	// Generate a visual verification code to make sure the user is no bot.
	$context['require_verification'] = require_verification();
	if ($context['require_verification'])
	{
		require_once __DIR__ . '/Subs-Editor.php';
		$verificationOptions = array(
			'id' => 'customform',
		);
		$context['visual_verification'] = create_control_verification($verificationOptions);
	}

	//	Are we looking for the thank you page.
	if (isset($_REQUEST['thankyou']))
	{
		$context['page_title'] = !empty($modSettings['customform_view_title'])
			? $modSettings['customform_view_title']
			: $txt['customform_tabheader'];
		$context['linktree'][] = [
			'url' => $scripturl . '?action=form',
			'name' => $context['page_title'],
		];
		$context['robot_no_index'] = true;
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
				redirectexit('action=form');

			//	Get the data about the current form.
			$request = $smcFunc['db_query']('', '
				SELECT title, output, subject, id_board, icon, form_exit, template_function
				FROM {db_prefix}cf_forms AS f
				WHERE title != \'\'
					AND EXISTS
					(
						SELECT * FROM {db_prefix}cf_fields AS d
						WHERE d.id_form = f.id_form
							AND title != \'\'
							AND text != \'\'
							AND type != \'\'
					)
					AND id_form = {int:id}',
				array(
					'id' => $form_id,
				)
			);

			//	Did we get some form data? If not then redirect the user to the form view page.
			if (!($form_data = $smcFunc['db_fetch_assoc']($request)))
				redirectexit('action=form;');

			$output = $form_data['output'];
			$exit = $form_data['form_exit'];
			$subject = $form_data['subject'];
			$icon = $form_data['icon'];
			$board = $form_data['id_board'];
			$form_title = $form_data['title'];

			//	Free the db request.
			$smcFunc['db_free_result']($request);

			//	Get a list of the current fields attached to this form.
			$request = $smcFunc['db_query']('', '
				SELECT id_field, title, text, type, type_vars
				FROM {db_prefix}cf_fields
				WHERE id_form = {int:id}
				AND title != \'\'
				AND text != \'\'
				AND type != \'\'
				ORDER BY id_field',
				array(
					'id' => $form_id,
				)
			);

			$vars = array();
			$post_errors = array();
			$context['fields'] = array();
			require_once __DIR__ . '/Class-CustomForm.php';
			while ($row = $smcFunc['db_fetch_assoc']($request))
			{
				$type = strtr($row['type'], [
					'largetextbox' =>'textarea',
					'textbox' =>'text',
					'checkbox' =>'check',
					'selectbox' =>'select',
					'float' =>'text',
					'int' =>'text',
					'radiobox' =>'radio',
					'infobox' =>'info'
				]);
				$class_name = 'CustomForm_' . $type;
				if (!class_exists($class_name))
					fatal_error('Param "' . $type . '" not found for field "' . $row['text'] . '" at ID #' . $row['id_field'] . '.', false);

				$type = new $class_name($row, $_POST['CustomFormField'][$row['id_field']] ?? '');
				$type->setHtml();

				if (isset($_POST['n']))
				{
					if (!$type->validate())
						$post_errors['id_field' . $row['id_field']] = $type->getError();

					// Add this field's value to the list of variables for the output post.
					$vars[$row['title']] = $type->getValue();
				}

				$context['fields'][$row['title']] = array(
					'text' => $row['text'],
					'type' => $row['type'],
					'html' => $type->getInputHtml(),
					'required' => $type->isRequired(),
					'failed' => isset($post_errors['id_field' . $row['id_field']]),
				);
			}
			$smcFunc['db_free_result']($request);

			if (isset($_POST['n']))
			{
				if ('' != ($sc_error = checkSession('post', '', false)))
					$post_errors[] = array(['error_' . $sc_error, '']);
				if ($context['require_verification'])
				{
					require_once __DIR__ . '/Subs-Editor.php';
					$verificationOptions = array(
						'id' => 'customform',
					);
					if (true !== ($verification_errors = create_control_verification($verificationOptions, true)))
						foreach ($verification_errors as $verification_error)
							$post_errors[] = ['error_' . $verification_error, ''];
				}

				if ($post_errors === [])
				{
					require_once __DIR__ . '/Subs-Post.php';
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
						redirectexit();
					elseif ($exit == 'form')
						redirectexit('action=form;');
					elseif ($exit == 'thanks')
						redirectexit('action=form;thankyou');
					else
						redirectexit($exit);
				}
				else
				{
					$context['post_errors'] = $post_errors;
					$context['template_layers'][] = 'errors';
				}
			}

			loadLanguage('CustomForm+Post+Errors');
			$context['page_title'] = $form_title;
			$context['form_id'] = $form_id;
			$context['failed_form_submit'] = $post_errors != [];
			$template_function = 'template_' . $form_data['template_function'];
			$template = function_exists($template_function)
				? $form_data['template_function']
				: 'form';
			$context['sub_template'] = $template;
			loadTemplate('CustomForm');
			$context['template_layers'][] = function_exists($template_function . '_above') && function_exists($template_function . '_below') ? $template : 'form';
			$context['linktree'][] = [
				'url' => $scripturl . '?action=form',
				'name' => !empty($modSettings['customform_view_title'])
					? $modSettings['customform_view_title']
					: $txt['customform_tabheader'],
			];
			$context['linktree'][] = [
				'url' => $scripturl . '?action=form;n=' . $form_id,
				'name' => $form_title,
			];
			$context['meta_description'] = $smcFunc['htmlspecialchars']($form_title);
			setMetaProperty('type', 'website');
		}
		//	If not then fall to the default view form page, with the list of forms.
		else
			listCustomForm();
}

function require_verification()
{
	global $modSettings, $user_info;

	return $user_info['is_guest'] || !$user_info['is_mod'] && !$user_info['is_admin'] && !empty($modSettings['posts_require_captcha']) && ($user_info['posts'] < $modSettings['posts_require_captcha']);
}

function listCustomForm()
{
	global $context, $modSettings, $scripturl, $smcFunc, $txt;

	if (!allowedTo('customform_view_perms'))
		redirectexit();

	loadLanguage('CustomForm');
	loadTemplate('CustomForm');

	$request = $smcFunc['db_query']('', '
		SELECT id_form, title
		FROM {db_prefix}cf_forms AS f
		WHERE title != \'\'
			AND EXISTS
			(
				SELECT * FROM {db_prefix}cf_fields AS d
				WHERE d.id_form = f.id_form
					AND title != \'\'
					AND text != \'\'
					AND type != \'\'
			)');
	$context['forms'] = array();
	while (list ($id_form, $title) = $smcFunc['db_fetch_row']($request))
		if (allowedTo('custom_forms_' . $id_form))
			$context['forms'][] = [$id_form, $title, ''];
	$smcFunc['db_free_result']($request);

	$context['template_layers'][] = 'forms';
	$context['page_title'] = !empty($modSettings['customform_view_title'])
		? $modSettings['customform_view_title']
		: $txt['customform_tabheader'];
	$context['linktree'][] = [
		'url' => $scripturl . '?action=form',
		'name' => $context['page_title'],
	];
	$context['page_message'] = $modSettings['customform_view_text'] ?? '';
	$context['sub_template'] = 'forms';
	$context['meta_description'] = $smcFunc['htmlspecialchars']($context['page_message']);
	setMetaProperty('type', 'website');
}

function setMetaTag(string $key, string $value): void
{
	global $context;

	$found = false;
	foreach ($context['meta_tags'] as $i => $m)
		if (isset($m['name']) && $m['name'] == $key)
		{
			$context['meta_tags'][$i]['content'] = $value;
			$found = true;
		}

	if (!$found)
		$context['meta_tags'][] = ['name' => $key, 'content' => $value];
}

function setMetaProperty(string $key, string $value): void
{
	global $context;

	$found = false;
	foreach ($context['meta_tags'] as $i => $m)
		if (isset($m['property']) && $m['property'] == 'og:' . $key)
		{
			$context['meta_tags'][$i]['content'] = $value;
			$found = true;
		}

	if (!$found)
		$context['meta_tags'][] = ['property' => 'og:' . $key, 'content' => $value];
}
