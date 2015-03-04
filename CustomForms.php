<?php

function ListCustomForms()
{
	global $txt, $context, $sourcedir, $smcFunc, $scripturl;

	// Deleting?
	if (isset($_POST['delete'], $_POST['remove']))
	{
		checkSession();

		// Delete the user data first.
		$smcFunc['db_query']('', '
			DELETE FROM {db_prefix}custom_form_field_data
			WHERE id_form IN ({array_int:fields})',
			array(
				'fields' => $_POST['remove'],
			)
		);
		// Then the link.
		$smcFunc['db_query']('', '
			DELETE FROM {db_prefix}custom_form_field_link
			WHERE id_form IN ({array_int:fields})',
			array(
				'fields' => $_POST['remove'],
			)
		);
		// Finally - the form themselves are gone!
		$smcFunc['db_query']('', '
			DELETE FROM {db_prefix}custom_forms
			WHERE id_form IN ({array_int:fields})',
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
		foreach (total_getCustomForms() as $field)
		{
			$bbc = !empty($_POST['bbc'][$field['id_form']]) ? 'yes' : 'no';
			if ($bbc != $field['bbc'])
				$smcFunc['db_query']('', '
					UPDATE {db_prefix}custom_forms
					SET bbc = {string:bbc}
					WHERE id_form = {int:field}',
					array(
						'bbc' => $bbc,
						'field' => $field['id_form'],
					)
				);

			$active = !empty($_POST['active'][$field['id_form']]) ? 'yes' : 'no';
			if ($active != $field['active'])
				$smcFunc['db_query']('', '
					UPDATE {db_prefix}custom_forms
					SET active = {string:active}
					WHERE id_form = {int:field}',
					array(
						'active' => $active,
						'field' => $field['id_form'],
					)
				);

			$can_search = !empty($_POST['can_search'][$field['id_form']]) ? 'yes' : 'no';
			if ($can_search != $field['can_search'])
				$smcFunc['db_query']('', '
					UPDATE {db_prefix}custom_forms
					SET can_search = {string:can_search}
					WHERE id_form = {int:field}',
					array(
						'can_search' => $can_search,
						'field' => $field['id_form'],
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
			'function' => 'list_getCustomForms',
		),
		'get_count' => array(
			'function' => 'list_getCustomFormFieldSize',
		),
		'columns' => array(
			'name' => array(
				'header' => array(
					'value' => $txt['pf_name'],
					'style' => 'text-align: left;',
				),
				'data' => array(
					'function' => create_function('$rowData', '
						global $scripturl;

						return sprintf(\'<a href="%1$s?action=admin;area=customforms;sa=edit;fid=%2$d">%3$s</a><div class="smalltext">%4$s</div>\', $scripturl, $rowData[\'id_form\'], $rowData[\'name\'], $rowData[\'description\']);
					'),
					'style' => 'width: 40%;',
				),
				'sort' => array(
					'default' => 'name',
					'reverse' => 'name DESC',
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
						return sprintf(\'<span id="bbc_%1$s" class="color_%4$s">%3$s</span>&nbsp;<input type="checkbox" name="bbc[%1$s]" id="bbc_%1$s" value="%1$s"%2$s>\', $rowData[\'id_form\'], $isChecked, $txt[$rowData[\'bbc\']], $rowData[\'bbc\']);
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
						return sprintf(\'<span id="active_%1$s" class="color_%4$s">%3$s</span>&nbsp;<input type="checkbox" name="active[%1$s]" id="active_%1$s" value="%1$s"%2$s>\', $rowData[\'id_form\'], $isChecked, $txt[$rowData[\'active\']], $rowData[\'active\']);
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
						return sprintf(\'<span id="can_search_%1$s" class="color_%4$s">%3$s</span>&nbsp;<input type="checkbox" name="can_search[%1$s]" id="can_search_%1$s" value="%1$s"%2$s>\', $rowData[\'id_form\'], $isChecked, $txt[$rowData[\'can_search\']], $rowData[\'can_search\']);
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
							'id_form' => false,
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
						return sprintf(\'<span id="remove_%1$s" class="color_no">%2$s</span>&nbsp;<input type="checkbox" name="remove[%1$s]" id="remove_%1$s" value="%1$s">\', $rowData[\'id_form\'], $txt[\'no\']);
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

function list_getCustomForms($start, $items_per_page, $sort)
{
	global $smcFunc;

	$list = array();
	$request = $smcFunc['db_query']('', '
		SELECT id_form, name, description, type, bbc, active, can_search
		FROM {db_prefix}custom_forms
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

function total_getCustomForms()
{
	global $smcFunc;

	$list = array();
	$request = $smcFunc['db_query']('', '
		SELECT *
		FROM {db_prefix}custom_forms');
	while ($row = $smcFunc['db_fetch_assoc']($request))
		$list[$row['id_form']] = $row;
	$smcFunc['db_free_result']($request);
	call_integration_hook('integrate_get_custom_forms', array(&$list));
	return $list;
}

function total_getCustomFormsSearchable()
{
	global $smcFunc;

	$list = array();
	$request = $smcFunc['db_query']('', '
		SELECT *
		FROM {db_prefix}custom_forms
		WHERE can_search = \'yes\'');
	while ($row = $smcFunc['db_fetch_assoc']($request))
		$list[$row['id_form']] = $row;
	$smcFunc['db_free_result']($request);
	call_integration_hook('integrate_get_custom_forms_searchable', array(&$list));
	return $list;
}

function get_custom_forms_filtered($form, $is_message_index = false)
{
	global $context, $user_info;

	$fields = total_getCustomForms();
	$list = array();
	foreach ($fields as $field)
	{
		$group_list = explode(',', $field['groups']);
		$is_allowed = array_intersect($user_info['groups'], $group_list);
		if (empty($is_allowed))
			continue;

		$list[$field['id_form']] = $field;
	}
	call_integration_hook('integrate_get_custom_forms_filtered', array(&$list, $form));
	return $list;
}

function list_getCustomFormFieldSize()
{
	global $smcFunc;

	$request = $smcFunc['db_query']('', '
		SELECT COUNT(*)
		FROM {db_prefix}custom_forms');

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
		SELECT id_field, name, type
		FROM {db_prefix}custom_form_fields');
	$context['fields'] = array();
	while ($row = $smcFunc['db_fetch_assoc']($request))
		$context['fields'][$row['id_field']] = $row['type'] . ' - ' . $row['name'];
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
			FROM {db_prefix}custom_forms
			WHERE id_form = {int:current_field}',
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
				'forms' => !empty($row['forms']) ? explode(',', $row['forms']) : array(),
				'groups' => !empty($row['groups']) ? explode(',', $row['groups']) : array(),
			);
		}
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
		$forms = !empty($_POST['forms']) ? implode(',', array_keys($_POST['forms'])) : '';

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
			'groups = {string:groups}', ' forms = {string:forms}',
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
			'forms' => $forms,
		);
		$in_col = array(
			'name' => 'string', 'description' => 'string', 'enclose' => 'string',
			'type' => 'string', 'size' => 'string', 'options' => 'string', 'active' => 'string', 'default_value' => 'string',
			'can_search' => 'string', 'bbc' => 'string', 'mask' => 'string', 'regex' => 'string', 'groups' => 'string', 'forms' => 'string',
		);
		$in_data = array(
			$_POST['name'], $_POST['description'], $_POST['enclose'],
			$_POST['type'], $length, $options, $active, $default,
			$can_search, $bbc, $mask, $regex, $groups, $forms,
		);
		call_integration_hook('integrate_save_post_field', array(&$up_col, &$up_data, &$in_col, &$in_data));

		if ($context['fid'])
		{
			$smcFunc['db_query']('', '
				UPDATE {db_prefix}custom_forms
				SET
					' . implode(',
					', $up_col) . '
				WHERE id_form = {int:current_field}',
				$up_data
			);
		}
		else
		{
			$smcFunc['db_insert']('',
				'{db_prefix}custom_forms',
				$in_col,
				$in_data,
				array('id_form')
			);
		}

		/* // As there's currently no option to priorize certain fields over others, let's order them alphabetically.
		$smcFunc['db_query']('', '
			ALTER TABLE {db_prefix}custom_forms
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
			WHERE id_form = {int:current_field}',
			array(
				'current_field' => $context['fid'],
			)
		);
		// Then the link.
		$smcFunc['db_query']('', '
			DELETE FROM {db_prefix}custom_form_field_link
			WHERE id_form = {int:current_field}',
			array(
				'current_field' => $context['fid'],
			)
		);
		// Finally - the form itself is gone!
		$smcFunc['db_query']('', '
			DELETE FROM {db_prefix}custom_forms
			WHERE id_form = {int:current_field}',
			array(
				'current_field' => $context['fid'],
			)
		);
		call_integration_hook('integrate_delete_post_field');
		redirectexit('action=admin;area=customforms');
	}
}
