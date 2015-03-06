<?php

function custom_forms_admin_areas(&$admin_areas)
{
	global $txt;

	loadLanguage('ManageCustomForms');
	$admin_areas['layout']['areas']['customforms'] = array(
		'label' => $txt['custom_forms'],
		'icon' => 'settings.gif',
		'function' => 'ManageCustomFormFields',
		'subsections' => array(
			'index' => array($txt['pf_menu_index']),
			'edit' => array($txt['pf_menu_edit']),
			'index2' => array($txt['pf_menu_index2']),
			'edit2' => array($txt['pf_menu_edit2']),
		),
	);
}

function custom_forms_load_fields($fields)
{
	global $form, $context, $options, $smcFunc;

	if (empty($fields))
		return;

	$context['fields'] = array();
	$value = '';
	$exists = false;

	if (isset($_REQUEST['msg']))
	{
		$request = $smcFunc['db_query']('', '
			SELECT *
				FROM {db_prefix}custom_form_field_data
				WHERE id_msg = {int:msg}
					AND id_field IN ({array_int:field_list})',
				array(
					'msg' => (int) $_REQUEST['msg'],
					'field_list' => array_keys($fields),
			)
		);
		$values = array();
		while ($row = $smcFunc['db_fetch_assoc']($request))
			$values[$row['id_field']] = isset($row['value']) ? $row['value'] : '';
		$smcFunc['db_free_result']($request);
	}
	foreach ($fields as $field)
	{
		// If this was submitted already then make the value the posted version.
		if (isset($_POST['customform'], $_POST['customform'][$field['id_field']]))
		{
			$value = $smcFunc['htmlspecialchars']($_POST['customform'][$field['id_field']]);
			if (in_array($field['type'], array('select', 'radio')))
				$value = ($options = explode(',', $field['options'])) && isset($options[$value]) ? $options[$value] : '';
		}
		if (isset($values[$field['id_field']]))
			$value = $values[$field['id_field']];
		$exists = !empty($value);
		$context['fields'][] = rennder_field($field, $value, $exists);
	}
}

function rennder_field($field, $value, $exists)
{
	global $scripturl, $settings, $sourcedir;

	require_once($sourcedir . '/Class-CustomFormFields.php');
	$class_name = 'postFields_' . $field['type'];
	if (!class_exists($class_name))
		fatal_error('Param "' . $field['type'] . '" not found for field "' . $field['name'] . '" at ID #' . $field['id_field'] . '.', false);

	$param = new $class_name($field, $value, $exists);
	$param->setHtml();
	// Parse BBCode
	if ($field['bbc'] == 'yes')
		$param->output_html = parse_bbc($param->output_html);
	// Allow for newlines at least
	elseif ($field['type'] == 'textarea')
		$param->output_html = strtr($param->output_html, array("\n" => '<br>'));

	// Enclosing the user input within some other text?
	if (!empty($field['enclose']) && !empty($output_html))
	{
		$replacements = array(
			'{SCRIPTURL}' => $scripturl,
			'{IMAGES_URL}' => $settings['images_url'],
			'{DEFAULT_IMAGES_URL}' => $settings['default_images_url'],
			'{INPUT}' => $param->output_html,
		);
		call_integration_hook('integrate_enclose_post_field', array($field['id_field'], &$field['enclose'], &$replacements));
		$param->output_html = strtr($field['enclose'], $replacements);
	}

	return array(
		'name' => $field['name'],
		'description' => $field['description'],
		'type' => $field['type'],
		'input_html' => $param->input_html,
		'output_html' => $param->getOutputHtml(),
		'id_field' => $field['id_field'],
		'value' => $value,
	);
}

function custom_forms_post_form()
{
	global $form, $context, $options, $user_info;

	custom_forms_load_fields(get_custom_forms_filtered($form));
	loadLanguage('CustomFormFields');
	loadTemplate('CustomFormFields');
	$context['is_custom_forms_collapsed'] = $user_info['is_guest'] ? !empty($_COOKIE['postFields']) : !empty($options['postFields']);
}

function custom_forms_after($msgOptions, $topicOptions)
{
	global $form, $context, $smcFunc, $topic, $user_info;

	$field_list = get_custom_forms_filtered($form);
	$changes = $log_changes = array();
	$_POST['icon'] = 'xx';

	if (isset($_REQUEST['msg']))
	{
		$request = $smcFunc['db_query']('', '
			SELECT *
			FROM {db_prefix}custom_form_field_data
			WHERE id_msg = {int:msg}
				AND id_field IN ({array_int:field_list})',
			array(
				'msg' => (int) $_REQUEST['msg'],
				'field_list' => array_keys($field_list),
			)
		);
		$values = array();
		while ($row = $smcFunc['db_fetch_assoc']($request))
			$values[$row['id_field']] = isset($row['value']) ? $row['value'] : '';
		$smcFunc['db_free_result']($request);
	}

	if (isset($topic))
	{
		$request = $smcFunc['db_query']('', '
			SELECT id_first_msg
			FROM {db_prefix}topics
			WHERE id_topic = {int:current_topic}',
			array(
				'current_topic' => $topic,
			)
		);
		list ($topic_value) = $smcFunc['db_fetch_row']($request);
		$topic_value = $topic_value != $_REQUEST['msg'];
		$smcFunc['db_free_result']($request);
	}
	foreach ($field_list as $field)
		if ((empty($topic) || empty($topic_value)) && $field['topic_only'] == 'yes')
		{
			$value = isset($_POST['customform'][$field['id_field']]) ? $_POST['customform'][$field['id_field']] : '';
			$class_name = 'postFields_' . $field['type'];

			if (!class_exists($class_name) || (isset($values[$field['id_field']]) && $values[$field['id_field']] == $value))
				continue;

			$type = new $class_name($field, $value, !empty($value));
			$changes[] = array($field['id_field'], $type->getValue(), $msgOptions['id']);

			// Rather than calling logAction(), we build our own array to log everything in one go.
			$log_changes[] = array(
				'action' => 'custom_form_field_' . $field['id_field'],
				'id_log' => 2,
				'log_time' => time(),
				'id_member' => $user_info['id'],
				'ip' => $user_info['ip'],
				'extra' => serialize(array('old' => isset($values[$field['id_field']]) ? $values[$field['id_field']] : '', 'new' => $value, 'name' => $field['name'])),
				'id_msg' => $msgOptions['id'],
				'id_topic' => $topicOptions['id'],
				'id_form' => $topicOptions['form'],
			);
		}

	if (!empty($changes))
	{
		$smcFunc['db_insert']('replace',
			'{db_prefix}custom_form_field_data',
			array('id_field' => 'int', 'value' => 'string', 'id_msg' => 'int'),
			$changes,
			array('id_field', 'id_msg')
		);

		if (!empty($log_changes) && !empty($modSettings['modlog_enabled']))
			$smcFunc['db_insert']('',
				'{db_prefix}log_actions',
				array(
					'action' => 'string', 'id_log' => 'int', 'log_time' => 'int', 'id_member' => 'int', 'ip' => 'string-16',
					'extra' => 'string-65534',
				),
				$log_changes,
				array('id_action')
			);
	}
}

function custom_forms_post_post_validate(&$post_errors, $posterIsGuest)
{
	global $form, $context, $sourcedir, $smcFunc, $topic;

	// $context['post_error']['no_subject'] = false;
	foreach ($post_errors as $id => $post_error)
		if ($post_error == 'no_message')
			unset($post_errors[$id]);

	if (isset($_POST['customform']))
		$_POST['customform'] = htmlspecialchars__recursive($_POST['customform']);

	$field_list = get_custom_forms_filtered($form);
	require_once($sourcedir . '/Class-CustomFormFields.php');
	loadLanguage('CustomFormFields');

	if (isset($topic))
	{
		$request = $smcFunc['db_query']('', '
			SELECT id_first_msg
			FROM {db_prefix}topics
			WHERE id_topic = {int:current_topic}',
			array(
				'current_topic' => $topic,
			)
		);
		list ($topic_value) = $smcFunc['db_fetch_row']($request);
		$topic_value = $topic_value != $_REQUEST['msg'];
		$smcFunc['db_free_result']($request);
	}
	foreach ($field_list as $field)
		if ((empty($topic) || empty($topic_value)) && $field['topic_only'] == 'yes')
		{
			$value = isset($_POST['customform'][$field['id_field']]) ? $_POST['customform'][$field['id_field']] : '';
			$class_name = 'postFields_' . $field['type'];
			if (!class_exists($class_name))
				fatal_error('Param "' . $field['type'] . '" not found for field "' . $field['name'] . '" at ID #' . $field['id_field'] . '.', false);

			$type = new $class_name($field, $value, !empty($value));
			$type->validate();
			if (false !== ($err = $type->getError()))
				$post_errors[] = $err;
		}
}

function custom_forms_remove_message($message, $decreasePostCount)
{
	custom_forms_remove_messages($message, $decreasePostCount);
}

function custom_forms_remove_messages($message, $decreasePostCount)
{
	global $smcFunc;

	if (!empty($messages))
		$smcFunc['db_query']('', '
			DELETE FROM {db_prefix}custom_form_field_data
			WHERE id_msg IN ({array_int:message_list})',
			array(
				'message_list' => $messages,
			)
		);
}

function custom_forms_remove_topics($topics, $decreasePostCount, $ignoreRecycling)
{
	global $smcFunc;

	$messages = array();
	$request = $smcFunc['db_query']('', '
		SELECT id_msg
		FROM {db_prefix}messages
		WHERE id_topic IN ({array_int:topics})',
		array(
			'topics' => $topics,
		)
	);
	while ($row = $smcFunc['db_fetch_assoc']($request))
		$messages[] = $row['id_msg'];

	$smcFunc['db_free_result']($request);

	if (!empty($messages))
		custom_forms_remove_messages($messages, $decreasePostCount);
}

function custom_forms_display_topics($topic_ids)
{
	global $smcFunc;

	if (empty($topic_ids))
		return;

	$messages = array();
	$request = $smcFunc['db_query']('', '
		SELECT id_first_msg
		FROM {db_prefix}topics
		WHERE id_topic IN ({array_int:topics})',
		array(
			'topics' => $topic_ids,
		)
	);
	while ($row = $smcFunc['db_fetch_row']($request))
		$messages[] = $row[0];

	$smcFunc['db_free_result']($request);

	if (!empty($messages))
		custom_forms_display_message_list($messages, true);
}

function custom_forms_display_message_list($messages, $is_message_index = false)
{
	global $form, $context, $smcFunc;

	$field_list = get_custom_forms_filtered($form, $is_message_index);

	if (empty($field_list))
		return;

	$request = $smcFunc['db_query']('', '
		SELECT *
		FROM {db_prefix}custom_form_field_data
		WHERE id_msg IN ({array_int:message_list})
			AND id_field IN ({array_int:field_list})',
		array(
			'message_list' => $messages,
			'field_list' => array_keys($field_list),
		)
	);
	$context['fields'] = array();
	while ($row = $smcFunc['db_fetch_assoc']($request))
	{
		$exists = isset($row['value']);
		$value = $exists ? $row['value'] : '';

		$context['fields'][$row['id_msg']][$row['id_field']] = rennder_field($field_list[$row['id_field']], $value, $exists);
	}
	$smcFunc['db_free_result']($request);

	if (!empty($context['fields']))
	{
		loadLanguage('CustomFormFields');
		loadTemplate('CustomFormFields');
	}
}

function custom_forms_display_post_done($counter, &$output)
{
	global $context;
	$field_order = array(1, 2, 4, 5, 6, 10);

	if (!empty($context['fields'][$output['id']]))
	{
		$body = '
						<br />
						<dl class="settings">';

		foreach ($field_order as $fo)
		{
			$field = $context['fields'][$output['id']][$fo];

			if ($field['id_field'] == 4)
			{
				$field = $context['fields'][$output['id']][3];
				if ($field['output_html'] == 'Fixed')
					$field['output_html'] = '£ ' . $context['fields'][$output['id']][4]['output_html'];
			}

			if ($field['id_field'] == 5)
				$field['output_html'] .= ' ' . $context['fields'][$output['id']][7]['output_html'];

			if ($field['id_field'] == 6)
				$field['output_html'] .= ' ' . $context['fields'][$output['id']][8]['output_html'];

			if ($field['id_field'] == 5 || $field['id_field'] == 6 || $field['id_field'] == 10)
				$body .= '
						</dl>
						<hr />
						<dl class="settings" style="margin-top: 10px;">';

			$body .= '
							<dt>
								<strong>' . $field['name'] . ': </strong><br />
							</dt>
							<dd>
								' . $field['output_html'] . '
							</dd>';
		}

		$output['body'] = $body . '
						</dl>
						<hr />
						<br />' . $output['body'];
	}
}

?>
