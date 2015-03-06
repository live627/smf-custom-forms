<?php

function pf_admin_areas(&$admin_areas)
{
	global $txt;

	loadLanguage('CustomForms');
	$admin_areas['layout']['areas']['customforms'] = array(
		'label' => $txt['custom_forms'],
		'icon' => 'settings.gif',
		'function' => 'CustomFormFields',
		'subsections' => array(
			'index' => array($txt['pf_menu_index']),
			'edit' => array($txt['pf_menu_edit']),
			'index2' => array($txt['pf_menu_index2']),
			'edit2' => array($txt['pf_menu_edit2']),
		),
	);
}

function CustomFormFields()
{
	global $context, $sourcedir, $txt;

	// Load up all the tabs...
	$context[$context['admin_menu_name']]['tab_data'] = array(
		'title' => $txt['custom_forms'],
		'description' => $txt['custom_forms_desc'],
	);

	$sub_actions = array(
		'index' => 'ListCustomForms',
		'edit' => 'EditCustomForm',
		'index2' => 'ListCustomFormFields',
		'edit2' => 'EditCustomFormField',
	);

	// Default to sub action 'index'
	if (!isset($_GET['sa']) || !isset($sub_actions[$_GET['sa']]))
		$_GET['sa'] = 'index';

	$context['sub_template'] = $_GET['sa'];
	require_once($sourcedir . '/CustomForms.php');

	// This area is reserved for admins only - do this here since the menu code does not.
	isAllowedTo('asmin_forum');

	// Calls a function based on the sub-action
	$sub_actions[$_GET['sa']]();
}

function ListCustomFormFields()
{
	global $txt, $context, $sourcedir, $smcFunc, $scripturl;

	// Deleting?
	if (isset($_POST['delete'], $_POST['remove']))
	{
		checkSession();

		// Delete the user data first.
		$smcFunc['db_query']('', '
			DELETE FROM {db_prefix}custom_form_field_data
			WHERE id_field IN ({array_int:fields})',
			array(
				'fields' => $_POST['remove'],
			)
		);
		// Then the link.
		$smcFunc['db_query']('', '
			DELETE FROM {db_prefix}custom_form_field_link
			WHERE id_field IN ({array_int:fields})',
			array(
				'fields' => $_POST['remove'],
			)
		);
		// Finally - the fields themselves are gone!
		$smcFunc['db_query']('', '
			DELETE FROM {db_prefix}custom_form_fields
			WHERE id_field IN ({array_int:fields})',
			array(
				'fields' => $_POST['remove'],
			)
		);
		call_integration_hook('integrate_delete_custom_forms', array($_POST['remove']));
		redirectexit('action=admin;area=customforms');
	}

	// Changing the status?
	if (isset($_POST['save']))
	{
		checkSession();
		foreach (total_getCustomFormFields() as $field)
		{
			$bbc = !empty($_POST['bbc'][$field['id_field']]) ? 'yes' : 'no';
			if ($bbc != $field['bbc'])
				$smcFunc['db_query']('', '
					UPDATE {db_prefix}custom_form_fields
					SET bbc = {string:bbc}
					WHERE id_field = {int:field}',
					array(
						'bbc' => $bbc,
						'field' => $field['id_field'],
					)
				);

			$active = !empty($_POST['active'][$field['id_field']]) ? 'yes' : 'no';
			if ($active != $field['active'])
				$smcFunc['db_query']('', '
					UPDATE {db_prefix}custom_form_fields
					SET active = {string:active}
					WHERE id_field = {int:field}',
					array(
						'active' => $active,
						'field' => $field['id_field'],
					)
				);

			$can_search = !empty($_POST['can_search'][$field['id_field']]) ? 'yes' : 'no';
			if ($can_search != $field['can_search'])
				$smcFunc['db_query']('', '
					UPDATE {db_prefix}custom_form_fields
					SET can_search = {string:can_search}
					WHERE id_field = {int:field}',
					array(
						'can_search' => $can_search,
						'field' => $field['id_field'],
					)
				);
			call_integration_hook('integrate_update_post_field', array($field));
		}
		redirectexit('action=admin;area=customforms');
	}

	// New field?
	if (isset($_POST['new']))
		redirectexit('action=admin;area=customforms;sa=edit');

	$listOptions = array(
		'id' => 'pf_fields',
		'base_href' => $scripturl . '?action=action=admin;area=customforms',
		'default_sort_col' => 'name',
		'no_items_label' => $txt['pf_none'],
		'items_per_page' => 25,
		'get_items' => array(
			'function' => 'list_getCustomFormFields',
		),
		'get_count' => array(
			'function' => 'list_getCustomFormFieldSize',
		),
		'columns' => array(
			'name' => array(
				'header' => array(
					'value' => $txt['pf_fieldname'],
					'style' => 'text-align: left;',
				),
				'data' => array(
					'function' => create_function('$rowData', '
						global $scripturl;

						return sprintf(\'<a href="%1$s?action=admin;area=customforms;sa=edit;fid=%2$d">%3$s</a><div class="smalltext">%4$s</div>\', $scripturl, $rowData[\'id_field\'], $rowData[\'name\'], $rowData[\'description\']);
					'),
					'style' => 'width: 40%;',
				),
				'sort' => array(
					'default' => 'name',
					'reverse' => 'name DESC',
				),
			),
			'type' => array(
				'header' => array(
					'value' => $txt['pf_fieldtype'],
				),
				'data' => array(
					'function' => create_function('$rowData', '
						global $txt;

						$textKey = sprintf(\'pf_type_%1$s\', $rowData[\'type\']);
						return isset($txt[$textKey]) ? $txt[$textKey] : $textKey;
					'),
					'style' => 'width: 10%; text-align: center;',
				),
				'sort' => array(
					'default' => 'type',
					'reverse' => 'type DESC',
				),
			),
			'bbc' => array(
				'header' => array(
					'value' => $txt['pf_bbc'],
				),
				'data' => array(
					'function' => create_function('$rowData', '
						global $txt;
						$isChecked = $rowData[\'bbc\'] == \'no\' ? \'\' : \' checked\';
						return sprintf(\'<span id="bbc_%1$s" class="color_%4$s">%3$s</span>&nbsp;<input type="checkbox" name="bbc[%1$s]" id="bbc_%1$s" value="%1$s"%2$s>\', $rowData[\'id_field\'], $isChecked, $txt[$rowData[\'bbc\']], $rowData[\'bbc\']);
					'),
					'style' => 'width: 10%; text-align: center;',
				),
				'sort' => array(
					'default' => 'bbc DESC',
					'reverse' => 'bbc',
				),
			),
			'active' => array(
				'header' => array(
					'value' => $txt['pf_active'],
				),
				'data' => array(
					'function' => create_function('$rowData', '
						global $txt;
						$isChecked = $rowData[\'active\'] == \'no\' ? \'\' : \' checked\';
						return sprintf(\'<span id="active_%1$s" class="color_%4$s">%3$s</span>&nbsp;<input type="checkbox" name="active[%1$s]" id="active_%1$s" value="%1$s"%2$s>\', $rowData[\'id_field\'], $isChecked, $txt[$rowData[\'active\']], $rowData[\'active\']);
					'),
					'style' => 'width: 10%; text-align: center;',
				),
				'sort' => array(
					'default' => 'active DESC',
					'reverse' => 'active',
				),
			),
			'can_search' => array(
				'header' => array(
					'value' => $txt['pf_can_search'],
				),
				'data' => array(
					'function' => create_function('$rowData', '
						global $txt;
						$isChecked = $rowData[\'can_search\'] == \'no\' ? \'\' : \' checked\';
						return sprintf(\'<span id="can_search_%1$s" class="color_%4$s">%3$s</span>&nbsp;<input type="checkbox" name="can_search[%1$s]" id="can_search_%1$s" value="%1$s"%2$s>\', $rowData[\'id_field\'], $isChecked, $txt[$rowData[\'can_search\']], $rowData[\'can_search\']);
					'),
					'style' => 'width: 10%; text-align: center;',
				),
				'sort' => array(
					'default' => 'can_search DESC',
					'reverse' => 'can_search',
				),
			),
			'modify' => array(
				'header' => array(
					'value' => $txt['modify'],
				),
				'data' => array(
					'sprintf' => array(
						'format' => '<a href="' . $scripturl . '?action=admin;area=customforms;sa=edit;fid=%1$s">' . $txt['modify'] . '</a>',
						'params' => array(
							'id_field' => false,
						),
					),
					'style' => 'width: 10%; text-align: center;',
				),
			),
			'remove' => array(
				'header' => array(
					'value' => $txt['remove'],
				),
				'data' => array(
					'function' => create_function('$rowData', '
						global $txt;
						return sprintf(\'<span id="remove_%1$s" class="color_no">%2$s</span>&nbsp;<input type="checkbox" name="remove[%1$s]" id="remove_%1$s" value="%1$s">\', $rowData[\'id_field\'], $txt[\'no\']);
					'),
					'style' => 'width: 10%; text-align: center;',
				),
				'sort' => array(
					'default' => 'remove DESC',
					'reverse' => 'remove',
				),
			),
		),
		'form' => array(
			'href' => $scripturl . '?action=admin;area=customforms',
			'name' => 'postProfileFields',
		),
		'additional_rows' => array(
			array(
				'position' => 'below_table_data',
				'value' => '<input type="submit" name="save" value="' . $txt['save'] . '" class="submit">&nbsp;&nbsp;<input type="submit" name="delete" value="' . $txt['delete'] . '" onclick="return confirm(' . JavaScriptEscape($txt['pf_delete_sure']) . ');" class="delete">&nbsp;&nbsp;<input type="submit" name="new" value="' . $txt['pf_make_new'] . '" class="new">',
				'style' => 'text-align: right;',
			),
		),
	);
	require_once($sourcedir . '/Subs-List.php');
	call_integration_hook('integrate_list_custom_forms', array(&$listOptions));
	createList($listOptions);
	$context['sub_template'] = 'show_list';
	$context['default_list'] = 'pf_fields';
}

function list_getCustomFormFields($start, $items_per_page, $sort)
{
	global $smcFunc;

	$list = array();
	$request = $smcFunc['db_query']('', '
		SELECT id_field, name, description, type, bbc, active, can_search
		FROM {db_prefix}custom_form_fields
		ORDER BY {raw:sort}
		LIMIT {int:start}, {int:items_per_page}',
		array(
			'sort' => $sort,
			'start' => $start,
			'items_per_page' => $items_per_page,
		)
	);
	while ($row = $smcFunc['db_fetch_assoc']($request))
		$list[] = $row;
	$smcFunc['db_free_result']($request);

	return $list;
}

function total_getCustomFormFields()
{
	global $smcFunc;

	$list = array();
	$request = $smcFunc['db_query']('', '
		SELECT *
		FROM {db_prefix}custom_form_fields');
	while ($row = $smcFunc['db_fetch_assoc']($request))
		$list[$row['id_field']] = $row;
	$smcFunc['db_free_result']($request);
	call_integration_hook('integrate_get_custom_forms', array(&$list));
	return $list;
}

function total_getCustomFormFieldsSearchable()
{
	global $smcFunc;

	$list = array();
	$request = $smcFunc['db_query']('', '
		SELECT *
		FROM {db_prefix}custom_form_fields
		WHERE can_search = \'yes\'');
	while ($row = $smcFunc['db_fetch_assoc']($request))
		$list[$row['id_field']] = $row;
	$smcFunc['db_free_result']($request);
	call_integration_hook('integrate_get_custom_forms_searchable', array(&$list));
	return $list;
}

function get_custom_forms_filtered($form, $is_message_index = false)
{
	global $context, $user_info;

	$fields = total_getCustomFormFields();
	$list = array();
	foreach ($fields as $field)
	{
		$form_list = array_flip(explode(',', $field['forms']));
		if (!isset($form_list[$form]))
			continue;

		$group_list = explode(',', $field['groups']);
		$is_allowed = array_intersect($user_info['groups'], $group_list);
		if (empty($is_allowed))
			continue;

		$list[$field['id_field']] = $field;
	}
	call_integration_hook('integrate_get_custom_forms_filtered', array(&$list, $form));
	return $list;
}

function list_getCustomFormFieldSize()
{
	global $smcFunc;

	$request = $smcFunc['db_query']('', '
		SELECT COUNT(*)
		FROM {db_prefix}custom_form_fields');

	list ($numProfileFields) = $smcFunc['db_fetch_row']($request);
	$smcFunc['db_free_result']($request);

	return $numProfileFields;
}

function EditCustomFormField()
{
	global $txt, $scripturl, $context, $settings, $smcFunc;

	$context['fid'] = isset($_REQUEST['fid']) ? (int) $_REQUEST['fid'] : 0;
	$context['page_title'] = $txt['custom_forms'] . ' - ' . ($context['fid'] ? $txt['pf_title'] : $txt['pf_add']);
	$context['page_title2'] = $txt['custom_forms'] . ' - ' . ($context['fid'] ? $txt['pf_title'] : $txt['pf_add']);
	$context['html_headers'] .= '<script type="text/javascript" src="' . $settings['default_theme_url'] . '/scripts/customformsadmin.js"></script>';
	loadTemplate('CustomForms');

	$request = $smcFunc['db_query']('', '
		SELECT id_form, name
		FROM {db_prefix}custom_forms');
	$context['forms'] = array();
	while ($row = $smcFunc['db_fetch_assoc']($request))
		$context['forms'][$row['id_form']] = $row['name'];
	$smcFunc['db_free_result']($request);

	$request = $smcFunc['db_query']('', '
		SELECT id_group, group_name, online_color
		FROM {db_prefix}membergroups
		WHERE min_posts = {int:min_posts}
			AND id_group != {int:mod_group}
		ORDER BY group_name',
		array(
			'min_posts' => -1,
			'mod_group' => 3,
		)
	);
	$context['groups'] = array();
	while ($row = $smcFunc['db_fetch_assoc']($request))
		$context['groups'][$row['id_group']] = '<span' . ($row['online_color'] ? ' style="color: ' . $row['online_color'] . '"' : '') . '>' . $row['group_name'] . '</span>';
	$smcFunc['db_free_result']($request);

	loadLanguage('Profile');

	if ($context['fid'])
	{
		$request = $smcFunc['db_query']('', '
			SELECT *
			FROM {db_prefix}custom_form_fields
			WHERE id_field = {int:current_field}',
			array(
				'current_field' => $context['fid'],
			)
		);
		$context['field'] = array();
		while ($row = $smcFunc['db_fetch_assoc']($request))
		{
			if ($row['type'] == 'textarea')
				@list ($rows, $cols) = @explode(',', $row['default_value']);
			else
			{
				$rows = 3;
				$cols = 30;
			}

			$context['field'] = array(
				'name' => $row['name'],
				'description' => $row['description'],
				'enclose' => $row['enclose'],
				'type' => $row['type'],
				'length' => $row['size'],
				'rows' => $rows,
				'cols' => $cols,
				'bbc' => $row['bbc'] == 'yes',
				'default_check' => $row['type'] == 'check' && $row['default_value'] ? true : false,
				'default_select' => $row['type'] == 'select' || $row['type'] == 'radio' ? $row['default_value'] : '',
				'options' => strlen($row['options']) > 1 ? explode(',', $row['options']) : array('', '', ''),
				'active' => $row['active'] == 'yes',
				'can_search' => $row['can_search'] == 'yes',
				'mask' => $row['mask'],
				'regex' => $row['regex'],
				'forms' => array(),
				'groups' => !empty($row['groups']) ? explode(',', $row['groups']) : array(),
			);
		}
		$smcFunc['db_free_result']($request);

		$request = $smcFunc['db_query']('', '
			SELECT id_form
			FROM {db_prefix}custom_form_field_link
			WHERE id_field = {int:current_field}',
			array(
				'current_field' => $context['fid'],
			)
		);
		$context['field']['forms'] = array();
		while ($row = $smcFunc['db_fetch_assoc']($request))
			$context['field']['forms'][] = $row['id_form'];
		$smcFunc['db_free_result']($request);
	}

	// Setup the default values as needed.
	if (empty($context['field']))
		$context['field'] = array(
			'name' => '',
			'description' => '',
			'enclose' => '',
			'type' => 'text',
			'length' => 255,
			'rows' => 4,
			'cols' => 30,
			'bbc' => false,
			'default_check' => false,
			'default_select' => '',
			'options' => array('', '', ''),
			'active' => true,
			'can_search' => false,
			'mask' => '',
			'regex' => '',
			'forms' => array(),
			'groups' => array(),
		);

	// Are we saving?
	if (isset($_POST['save']))
	{
		checkSession();

		if (trim($_POST['name']) == '')
			fatal_lang_error('post_option_need_name');
		$_POST['name'] = $smcFunc['htmlspecialchars']($_POST['name']);
		$_POST['description'] = $smcFunc['htmlspecialchars']($_POST['description']);

		$bbc = !empty($_POST['bbc']) ? 'yes' : 'no';
		$active = !empty($_POST['active']) ? 'yes' : 'no';
		$can_search = !empty($_POST['can_search']) ? 'yes' : 'no';

		$mask = isset($_POST['mask']) ? $_POST['mask'] : '';
		$regex = isset($_POST['regex']) ? $_POST['regex'] : '';
		$length = isset($_POST['length']) ? (int) $_POST['length'] : 255;
		$groups = !empty($_POST['groups']) ? implode(',', array_keys($_POST['groups'])) : '';

		$options = '';
		$newOptions = array();
		$default = isset($_POST['default_check']) && $_POST['type'] == 'check' ? 1 : '';
		if (!empty($_POST['select_option']) && ($_POST['type'] == 'select' || $_POST['type'] == 'radio'))
		{
			foreach ($_POST['select_option'] as $k => $v)
			{
				$v = $smcFunc['htmlspecialchars']($v);
				$v = strtr($v, array(',' => ''));

				if (trim($v) == '')
					continue;

				$newOptions[$k] = $v;

				if (isset($_POST['default_select']) && $_POST['default_select'] == $k)
					$default = $v;
			}
			$options = implode(',', $newOptions);
		}

		if ($_POST['type'] == 'textarea')
			$default = (int) $_POST['rows'] . ',' . (int) $_POST['cols'];

		$up_col = array(
			'name = {string:name}', ' description = {string:description}', ' enclose = {string:enclose}',
			'`type` = {string:type}', ' size = {int:length}',
			'options = {string:options}',
			'active = {string:active}', ' default_value = {string:default_value}',
			'can_search = {string:can_search}', ' bbc = {string:bbc}', ' mask = {string:mask}', ' regex = {string:regex}',
			'groups = {string:groups}',
		);
		$up_data = array(
			'length' => $length,
			'active' => $active,
			'can_search' => $can_search,
			'bbc' => $bbc,
			'current_field' => $context['fid'],
			'name' => $_POST['name'],
			'description' => $_POST['description'],
			'enclose' => $_POST['enclose'],
			'type' => $_POST['type'],
			'options' => $options,
			'default_value' => $default,
			'mask' => $mask,
			'regex' => $regex,
			'groups' => $groups,
		);
		$in_col = array(
			'name' => 'string', 'description' => 'string', 'enclose' => 'string',
			'type' => 'string', 'size' => 'string', 'options' => 'string', 'active' => 'string', 'default_value' => 'string',
			'can_search' => 'string', 'bbc' => 'string', 'mask' => 'string', 'regex' => 'string', 'groups' => 'string',
		);
		$in_data = array(
			$_POST['name'], $_POST['description'], $_POST['enclose'],
			$_POST['type'], $length, $options, $active, $default,
			$can_search, $bbc, $mask, $regex, $groups,
		);
		call_integration_hook('integrate_save_post_field', array(&$up_col, &$up_data, &$in_col, &$in_data));

		if ($context['fid'])
		{
			$smcFunc['db_query']('', '
				UPDATE {db_prefix}custom_form_fields
				SET
					' . implode(',
					', $up_col) . '
				WHERE id_field = {int:current_field}',
				$up_data
			);
		}
		else
		{
			$smcFunc['db_insert']('',
				'{db_prefix}custom_form_fields',
				$in_col,
				$in_data,
				array('id_field')
			);
			$context['fid'] = $smcFunc['db_insert_id']('{db_prefix}custom_form_fields', 'id_field');
		}
		if (!empty($_POST['forms']))
		{
			$smcFunc['db_query']('', '
				DELETE FROM {db_prefix}custom_form_field_link
				WHERE id_field = {int:current_field}',
				array(
					'current_field' => $context['fid'],
				)
			);
			$forms = array_map(function ($value) use ($context) {
				return [(int) $value, $context['fid']];
			}, array_keys($_POST['forms']));
			$smcFunc['db_insert']('',
				'{db_prefix}custom_form_field_link',
				array('id_form' => 'int', 'id_field' => 'int'),
				$forms,
				array('id_field')
			);
		}

		/* // As there's currently no option to priorize certain fields over others, let's order them alphabetically.
		$smcFunc['db_query']('', '
			ALTER TABLE {db_prefix}custom_form_fields
			ORDER BY name',
			array(
				'db_error_skip' => true,
			)
		); */
		redirectexit('action=admin;area=customforms');
	}
	elseif (isset($_POST['delete']) && $context['field']['colname'])
	{
		checkSession();

		// Delete the user data first.
		$smcFunc['db_query']('', '
			DELETE FROM {db_prefix}custom_form_field_data
			WHERE id_field = {int:current_field}',
			array(
				'current_field' => $context['fid'],
			)
		);
		// Then the link.
		$smcFunc['db_query']('', '
			DELETE FROM {db_prefix}custom_form_field_link
			WHERE id_field = {int:current_field}',
			array(
				'current_field' => $context['fid'],
			)
		);
		// Finally - the field itself is gone!
		$smcFunc['db_query']('', '
			DELETE FROM {db_prefix}custom_form_fields
			WHERE id_field = {int:current_field}',
			array(
				'current_field' => $context['fid'],
			)
		);
		call_integration_hook('integrate_delete_post_field');
		redirectexit('action=admin;area=customforms');
	}
}

function pf_load_fields($fields)
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

function pf_post_form()
{
	global $form, $context, $options, $user_info;

	pf_load_fields(get_custom_forms_filtered($form));
	loadLanguage('CustomFormFields');
	loadTemplate('CustomFormFields');
	$context['is_custom_forms_collapsed'] = $user_info['is_guest'] ? !empty($_COOKIE['postFields']) : !empty($options['postFields']);
}

function pf_after($msgOptions, $topicOptions)
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

function pf_post_post_validate(&$post_errors, $posterIsGuest)
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

function pf_remove_message($message, $decreasePostCount)
{
	pf_remove_messages($message, $decreasePostCount);
}

function pf_remove_messages($message, $decreasePostCount)
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

function pf_remove_topics($topics, $decreasePostCount, $ignoreRecycling)
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
		pf_remove_messages($messages, $decreasePostCount);
}

function pf_display_topics($topic_ids)
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
		pf_display_message_list($messages, true);
}

function pf_display_message_list($messages, $is_message_index = false)
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

function pf_display_post_done($counter, &$output)
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
