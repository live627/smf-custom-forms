<?php
// Version: 1.0: Fields.php
namespace CustomForms\Services;

if (!defined('SMF')) {
	die('Hacking attempt...');
}

/**
 * @package CustomForms
 * @since 1.0
 */
class Fields
{
	public static function load_fields($fields)
	{
		global $form, $context, $options, $smcFunc;
		if (empty($fields)) {
			return;
		}
		$context['fields'] = array();
		$value = '';
		$exists = false;
		if (isset($_REQUEST['msg'])) {
			$request = \ModHelper\Database::query('', '
					SELECT *
						FROM {db_prefix}custom_form_field_data
						WHERE id_msg = {int:msg}
							AND id_field IN ({array_int:field_list})',
				array(
					'msg' => (int)$_REQUEST['msg'],
					'field_list' => array_keys($fields),
				)
			);
			$values = array();
			while ($row = \ModHelper\Database::fetch_assoc($request)) {
				$values[$row['id_field']] = isset($row['value']) ? $row['value'] : '';
			}
			\ModHelper\Database::free_result($request);
		}
		foreach ($fields as $field) {
			// If this was submitted already then make the value the posted version.
			if (isset($_POST['customform'], $_POST['customform'][$field['id_field']])) {
				$value = $smcFunc['htmlspecialchars']($_POST['customform'][$field['id_field']]);
				if (in_array($field['type'], array('select', 'radio'))) {
					$value = ($options = explode(',', $field['options'])) && isset($options[$value]) ? $options[$value] : '';
				}
			}
			if (isset($values[$field['id_field']])) {
				$value = $values[$field['id_field']];
			}
			$exists = !empty($value);
			$context['fields'][] = self::rennder_field($field, $value, $exists);
		}
	}

	public static function rennder_field($field, $value, $exists)
	{
		global $scripturl, $settings, $sourcedir;
		$class_name = '\\CustomForms\\Fields\\' . ucfirst($field['type']);
		$param = new $class_name($field, $value, $exists);
		$param->setHtml();
		// Parse BBCode
		if ($field['bbc'] == 'yes') {
			$param->output_html = parse_bbc($param->output_html);
		} // Allow for newlines at least
		elseif ($field['type'] == 'textarea') {
			$param->output_html = strtr($param->output_html, array("\n" => '<br>'));
		}
		// Enclosing the user input within some other text?
		if (!empty($field['enclose']) && !empty($output_html)) {
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

	public static function post_form()
	{
		global $form, $context, $options, $user_info;
		\CustomForms\Integration::load_fields(get_\CustomForms\Integration::filtered($form));
		loadLanguage('CustomFormFields');
		loadTemplate('CustomFormFields');
		$context['is_CustomForms\\Integration::collapsed'] = $user_info['is_guest'] ? !empty($_COOKIE['Fields']) : !empty($options['Fields']);
	}

	public static function after($msgOptions, $topicOptions)
	{
		global $form, $context, $smcFunc, $topic, $user_info;
		$field_list = get_\CustomForms\Integration::filtered($form);
		$changes = $log_changes = array();
		$_POST['icon'] = 'xx';
		if (isset($_REQUEST['msg'])) {
			$request = \ModHelper\Database::query('', '
					SELECT *
					FROM {db_prefix}custom_form_field_data
					WHERE id_msg = {int:msg}
						AND id_field IN ({array_int:field_list})',
				array(
					'msg' => (int)$_REQUEST['msg'],
					'field_list' => array_keys($field_list),
				)
			);
			$values = array();
			while ($row = \ModHelper\Database::fetch_assoc($request)) {
				$values[$row['id_field']] = isset($row['value']) ? $row['value'] : '';
			}
			\ModHelper\Database::free_result($request);
		}
		if (isset($topic)) {
			$request = \ModHelper\Database::query('', '
					SELECT id_first_msg
					FROM {db_prefix}topics
					WHERE id_topic = {int:current_topic}',
				array(
					'current_topic' => $topic,
				)
			);
			list ($topic_value) = \ModHelper\Database::fetch_row($request);
			$topic_value = $topic_value != $_REQUEST['msg'];
			\ModHelper\Database::free_result($request);
		}
		foreach ($field_list as $field) {
			if ((empty($topic) || empty($topic_value)) && $field['topic_only'] == 'yes') {
				$value = isset($_POST['customform'][$field['id_field']]) ? $_POST['customform'][$field['id_field']] : '';
				$class_name = 'Fields_' . $field['type'];
				if (!class_exists($class_name) || (isset($values[$field['id_field']]) && $values[$field['id_field']] == $value)) {
					continue;
				}
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
		}
		if (!empty($changes)) {
			\ModHelper\Database::insert('replace',
				'{db_prefix}custom_form_field_data',
				array('id_field' => 'int', 'value' => 'string', 'id_msg' => 'int'),
				$changes,
				array('id_field', 'id_msg')
			);
			if (!empty($log_changes) && !empty($modSettings['modlog_enabled'])) {
				\ModHelper\Database::insert('',
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
	}

	public static function post_post_validate(&$post_errors, $posterIsGuest)
	{
		global $form, $context, $sourcedir, $smcFunc, $topic;
		// $context['post_error']['no_subject'] = false;
		foreach ($post_errors as $id => $post_error) {
			if ($post_error == 'no_message') {
				unset($post_errors[$id]);
			}
		}
		if (isset($_POST['customform'])) {
			$_POST['customform'] = htmlspecialchars__recursive($_POST['customform']);
		}
		$field_list = get_\CustomForms\Integration::filtered($form);
		require_once($sourcedir . '/Class-CustomFormFields.php');
		loadLanguage('CustomFormFields');
		if (isset($topic)) {
			$request = \ModHelper\Database::query('', '
					SELECT id_first_msg
					FROM {db_prefix}topics
					WHERE id_topic = {int:current_topic}',
				array(
					'current_topic' => $topic,
				)
			);
			list ($topic_value) = \ModHelper\Database::fetch_row($request);
			$topic_value = $topic_value != $_REQUEST['msg'];
			\ModHelper\Database::free_result($request);
		}
		foreach ($field_list as $field) {
			if ((empty($topic) || empty($topic_value)) && $field['topic_only'] == 'yes') {
				$value = isset($_POST['customform'][$field['id_field']]) ? $_POST['customform'][$field['id_field']] : '';
				$class_name = 'Fields_' . $field['type'];
				if (!class_exists($class_name)) {
					fatal_error('Param "' . $field['type'] . '" not found for field "' . $field['name'] . '" at ID #' . $field['id_field'] . '.', false);
				}
				$type = new $class_name($field, $value, !empty($value));
				$type->validate();
				if (false !== ($err = $type->getError())) {
					$post_errors[] = $err;
				}
			}
		}
	}

	public static function remove_message($message, $decreasePostCount)
	{
		\CustomForms\Integration::remove_messages($message, $decreasePostCount);
	}

	public static function remove_messages($message, $decreasePostCount)
	{
		global $smcFunc;
		if (!empty($messages)) {
			\ModHelper\Database::query('', '
					DELETE FROM {db_prefix}custom_form_field_data
					WHERE id_msg IN ({array_int:message_list})',
				array(
					'message_list' => $messages,
				)
			);
		}
	}

	public static function remove_topics($topics, $decreasePostCount, $ignoreRecycling)
	{
		global $smcFunc;
		$messages = array();
		$request = \ModHelper\Database::query('', '
				SELECT id_msg
				FROM {db_prefix}messages
				WHERE id_topic IN ({array_int:topics})',
			array(
				'topics' => $topics,
			)
		);
		while ($row = \ModHelper\Database::fetch_assoc($request)) {
			$messages[] = $row['id_msg'];
		}
		\ModHelper\Database::free_result($request);
		if (!empty($messages)) {
			\CustomForms\Integration::remove_messages($messages, $decreasePostCount);
		}
	}

	public static function display_topics($topic_ids)
	{
		global $smcFunc;
		if (empty($topic_ids)) {
			return;
		}
		$messages = array();
		$request = \ModHelper\Database::query('', '
				SELECT id_first_msg
				FROM {db_prefix}topics
				WHERE id_topic IN ({array_int:topics})',
			array(
				'topics' => $topic_ids,
			)
		);
		while ($row = \ModHelper\Database::fetch_row($request)) {
			$messages[] = $row[0];
		}
		\ModHelper\Database::free_result($request);
		if (!empty($messages)) {
			\CustomForms\Integration::display_message_list($messages, true);
		}
	}

	public static function display_message_list($messages, $is_message_index = false)
	{
		global $form, $context, $smcFunc;
		$field_list = get_\CustomForms\Integration::filtered($form, $is_message_index);
		if (empty($field_list)) {
			return;
		}
		$request = \ModHelper\Database::query('', '
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
		while ($row = \ModHelper\Database::fetch_assoc($request)) {
			$exists = isset($row['value']);
			$value = $exists ? $row['value'] : '';
			$context['fields'][$row['id_msg']][$row['id_field']] = rennder_field($field_list[$row['id_field']], $value, $exists);
		}
		\ModHelper\Database::free_result($request);
		if (!empty($context['fields'])) {
			loadLanguage('CustomFormFields');
			loadTemplate('CustomFormFields');
		}
	}
}
