<?php

/**
 * @package   Custom Form mod
 * @version   2.2.2
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2014, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

//	Fucntion to handle the settings for the Custom Form Mod.
function ModifyCustomFormSettings($return_config = false)
{
	global $modSettings, $sourcedir, $txt, $scripturl, $context, $settings, $smcFunc;

	$config_vars = array();

	//	Get the id of the current element and sanitize it.
	if (isset($_GET['form_id']))
		$form_id = (int) $_GET['form_id'];

	//	Get the id of the current element and sanitize it.
	if (isset($_GET['field_id']))
		$field_id = (int) $_GET['field_id'];

	//	Do we need to deal with showing form settings?
	if (isset($form_id))
	{
		//	Get some information about this form.
		$request = $smcFunc['db_query'](
			'',
			'
			SELECT title, id_board, icon, output, subject, form_exit, template_function
			FROM {db_prefix}cf_forms
			WHERE id_form = {int:id_form}',
			array(
				'id_form' => $form_id,
			)
		);

		$data = $smcFunc['db_fetch_assoc']($request);
		$smcFunc['db_free_result']($request);

		//	No data? Well, show the default settings page then.
		if (empty($data))
			redirectexit("action=admin;area=modsettings;sa=customform;");

		//	Do we need to delete the form?
		if (isset($_GET['delete']))
		{
			//	Delete the form itself.
			$smcFunc['db_query'](
				'',
				'
				DELETE
				FROM {db_prefix}cf_forms
				WHERE id_form = {int:id_form}',
				array(
					'id_form' => $form_id,
				)
			);

			//	Delete any permissions that belong to the form.
			$smcFunc['db_query'](
				'',
				'
				DELETE
				FROM {db_prefix}permissions
				WHERE permission = {string:permission}',
				array(
					'permission' => 'custom_forms_' . $form_id,
				)
			);

			//	Delete any fields that belong to the form.
			$smcFunc['db_query'](
				'',
				'
				DELETE
				FROM {db_prefix}cf_fields
				WHERE id_form = {int:id_form}',
				array(
					'id_form' => $form_id,
				)
			);
			redirectexit("action=admin;area=modsettings;sa=customform;");
		}

		//	Do we need to update the form?
		elseif (isset($_GET['update']))
		{
			//	Format the form output, so that the WYSIWYG editor works correctly
			if (!empty($_REQUEST['message_mode']) && isset($_REQUEST['output']))
			{
				require_once($sourcedir . '/Subs-Editor.php');
				$_REQUEST['output'] = html_to_bbc($_REQUEST['output']);
				$_REQUEST['output'] = un_htmlspecialchars($_REQUEST['output']);
				$_REQUEST['output'] = $smcFunc['htmlspecialchars']($_REQUEST['output'], ENT_QUOTES);
				preparsecode($_REQUEST['output']);
			}

			//	Perform the updating query.
			$smcFunc['db_query'](
				'',
				'
				UPDATE {db_prefix}cf_forms
				SET id_board = {int:id_board},
				icon = {string:icon},
				title = {string:title}, output = {string:output},
				subject = {string:subject},
				form_exit = {string:form_exit},
				template_function = {string:template_function}
				WHERE id_form = {int:id_form}',
				array(
					'id_form' => $form_id,
					'id_board' => intval($_REQUEST['board_id']),
					'icon' => $_REQUEST['icon'],
					'title' => $_REQUEST['title'],
					'output' => $_REQUEST['output'],
					'subject' => $_REQUEST['subject'],
					'form_exit' => $_REQUEST['exit'],
					'template_function' => $_REQUEST['template_function'],
				)
			);

			//	Update the permissions.
			require_once($sourcedir . '/ManagePermissions.php');
			save_inline_permissions(array('custom_forms_' . $form_id));

			redirectexit("action=admin;area=modsettings;sa=customform;form_id=" . $form_id . ";");
		}
		//	Do we need to add a new field?
		elseif (isset($_GET['add_field']))
		{
			//	Do the creation query.
			$smcFunc['db_insert'](
				'',
				'{db_prefix}cf_fields',
				array('id_form' => 'int'),
				array($form_id),
				array('id_field')
			);

			//	Get the field id.
			$field_id = $smcFunc['db_insert_id']('{db_prefix}cf_fields', 'id_field');

			//	Take us to the newly created form.
			redirectexit("action=admin;area=modsettings;sa=customform;field_id=" . $field_id . ";");
		}

		//	The template will need some data.
		$context['custom_form_settings'] = array(
			'permissions' => 'custom_forms_' . $form_id,
			'form_board_id' => $data['id_board'],
			'icon' => $data['icon'],
			'form_title' => $data['title'],
			'subject' => $data['subject'],
			'form_exit' => $data['form_exit'],
			'output' => $data['output'],
			'template_function' => $data['template_function'],
		);

		//	Call the function to setup the wysiwyg editor.
		require_once($sourcedir . '/Subs-Editor.php');
		create_control_richedit(
			array(
				'id' => 'output',
				'value' => $data['output'],
				'richvalue' => $data['output'],
				'width' => '100%',
			)
		);

		//	Create the list of fields.
		$list = array(
			'id' => 'customform_list_fields',
			'title' => $txt['customform_listheading_fields'],
			'no_items_label' => $txt['customform_list_noelements'],
			'get_items' => array(
				'function' => 'list_customform_fields',
				'params' => array($form_id),
			),
			'columns' => array(
				'title' => array(
					'header' => array(
						'value' => $txt['title'],
					),
					'data' => array(
						'db' => 'title',
					),
				),
				'text' => array(
					'header' => array(
						'value' => $txt['customform_text'],
					),
					'data' => array(
						'db' => 'text',
					),
				),
				'type' => array(
					'header' => array(
						'value' => $txt['customform_type'],
					),
					'data' => array(
						'db' => 'type',
					),
				),
				'modify' => array(
					'header' => array(
						'value' => $txt['modify'],
					),
					'data' => array(
						'db' => 'modify',
					),
				),
			),
			'additional_rows' => array(
				array(
					'position' => 'below_table_data',
					'value' => '<a href="' . $scripturl . '?action=admin;area=modsettings;sa=customform;form_id=' . $form_id . ';add_field;">' . $txt['customform_add_field'] . '</a>',
					'class' => 'titlebg',
				),
			),
		);

		//	Call the function to setup the list for the template.
		require_once($sourcedir . '/Subs-List.php');
		createList($list);

		//	Call the function to setup the inline permissions for the template.
		require_once($sourcedir . '/ManagePermissions.php');
		init_inline_permissions(array('custom_forms_' . $form_id));
		if (function_exists('createToken'))
			createToken('admin-mp');

		//	Set up the variables needed by the template.
		$context['settings_title'] =
			'<a href="' . $scripturl . '?action=admin;area=modsettings;sa=customform;">' . $txt['customform_generalsettings_heading']
			. '</a> -> ' . $data['title'] . '" ' . $txt['customform_form'];
		$context['post_url'] =
			$scripturl . '?action=admin;area=modsettings;sa=customform;form_id=' . $form_id . ';update;';
		$context['page_title'] = $txt['customform_tabheader'];
		$context['sub_template'] = 'customform_FormSettings';
		$context['default_list'] = 'customform_list_fields';
		$context['sub_template'] = 'customform_GeneralSettings';

		// Load the boards and categories for adding or editing a Form.
		$request = $smcFunc['db_query'](
			'',
			'
		 SELECT b.id_board, b.name, b.child_level, c.name AS cat_name, c.id_cat
		 FROM {db_prefix}boards AS b
			LEFT JOIN {db_prefix}categories AS c ON (c.id_cat = b.id_cat)',
			array()
		);
		$context['categories'] = array();
		while ($row = $smcFunc['db_fetch_assoc']($request))
		{
			if (!isset($context['categories'][$row['id_cat']]))
				$context['categories'][$row['id_cat']] = array(
					'name' => strip_tags($row['cat_name']),
					'boards' => array(),
				);

			$context['categories'][$row['id_cat']]['boards'][] = array(
				'id' => $row['id_board'],
				'name' => strip_tags($row['name']),
				'category' => strip_tags($row['cat_name']),
				'child_level' => $row['child_level'],
				'selected' => !empty($_SESSION['move_to_topic']) && $_SESSION['move_to_topic'] == $row['id_board'] && $row['id_board'] != $board,
			);
		}
		$smcFunc['db_free_result']($request);

		if (empty($context['categories']))
			fatal_lang_error('Invalid Board', false);

		$context['icon'] = !empty($context['cf_forms']['icon']) ? $context['cf_forms']['icon'] : 'xx';

		require_once($sourcedir . '/Subs-Editor.php');
		// Message icons - customized icons are off?
		$context['icons'] =
			getMessageIcons(!empty($context['cf_forms']['id_board']) ? $context['cf_forms']['id_board'] : 0);

		if (!empty($context['icons']))
			$context['icons'][count($context['icons']) - 1]['is_last'] = true;

		$context['icon_url'] = '';
		for ($i = 0, $n = count($context['icons']); $i < $n; $i++)
		{
			$context['icons'][$i]['selected'] =
				$context['custom_form_settings']['icon'] == $context['icons'][$i]['value'];
			if ($context['icons'][$i]['selected'])
				$context['icon_url'] = $context['icons'][$i]['url'];
		}
		if (empty($context['icon_url']))
		{
			$context['icon_url'] = $settings[file_exists(
					$settings['theme_dir'] . '/images/post/' . $context['icon'] . '.gif'
				) ? 'images_url' : 'default_images_url'] . '/post/' . $context['icon'] . '.gif';
			array_unshift(
				$context['icons'],
				array(
					'value' => $context['icon'],
					'name' => $txt['current_icon'],
					'url' => $context['icon_url'],
					'is_last' => empty($context['icons']),
					'selected' => true,
				)
			);
		}
	$request = $smcFunc['db_query']('order_by_board_order', '
		SELECT b.id_board, b.name AS board_name, c.name AS cat_name
		FROM {db_prefix}boards AS b
			LEFT JOIN {db_prefix}categories AS c ON (c.id_cat = b.id_cat)
		WHERE redirect = {string:empty_string}',
		array(
			'empty_string' => '',
		)
	);
	while ($row = $smcFunc['db_fetch_assoc']($request))
		$boards[$row['id_board']] = $row['cat_name'] . ' - ' . $row['board_name'];
	$smcFunc['db_free_result']($request);

	if (!empty($boards))
	{
		require_once($sourcedir . '/Subs-Boards.php');
		sortBoards($boards);
		$boards = array('') + $boards;
	}
	else
		$boards = array('');
		$config_vars = array(
			array(
				'text',
				'title',
				'value' => $data['title'],
				'text_label' => $txt['title'],
				'help' => 'customform_field_title',
			),
			array(
				'select',
				'board_id',
				$boards,
				'value' => $data['id_board'],
				'text_label' => $txt['customform_board_id'],
				'help' => 'customform_board_id',
			),
			array(
				'select',
				'icon',
				array_column($context['icons'], 'name', 'value'),
				'value' => $data['icon'],
				'text_label' => $txt['message_icon'],
				'help' => 'customform_icon',
			),
			array(
				'text',
				'template_function',
				'value' => $data['template_function'],
				'text_label' => $txt['customform_template_function'],
				'help' => 'customform_template_function',
			),
			array(
				'permissions',
				'custom_forms_' . $form_id,
				'value' => 'custom_forms_' . $form_id,
				'text_label' => $txt['edit_permissions'],
				'help' => 'customform_permissions',
			),
			array(
				'text',
				'subject',
				'value' => $data['subject'],
				'text_label' => $txt['subject'],
				'help' => 'customform_subject',
			),
			array(
				'text',
				'exit',
				'value' => $data['form_exit'],
				'text_label' => $txt['customform_exit'],
				'help' => 'customform_exit',
			),
			array(
				'callback',
				'output',
			),
		);


		//	Load all of the templates that we need.
		loadTemplate('CustomForm');
		loadTemplate('GenericControls');
		loadTemplate('GenericList');
		//	Finally prepare the settings array to be shown by the 'show_settings' template.
		prepareDBSettingContext($config_vars);
		if (function_exists('createToken'))
		{
			// Two tokens because saving these settings requires both save_inline_permissions and saveDBSettings
			createToken('admin-mp');
			createToken('admin-dbsc');
		}
	}
	//	Do we need to deal with showing specific field settings?
	elseif (isset($field_id))
	{
		$request = $smcFunc['db_query'](
			'',
			'
			SELECT title, type, id_form, text, type_vars
			FROM {db_prefix}cf_fields
			WHERE id_field = {int:id_field}',
			array(
				'id_field' => $field_id,
			)
		);

		$data = $smcFunc['db_fetch_assoc']($request);
		$smcFunc['db_free_result']($request);

		//	No data? Well, show the default settings page then.
		if (empty($data))
			redirectexit("action=admin;area=modsettings;sa=customform;");

		//	Get some information about the parent form.
		$request = $smcFunc['db_query'](
			'',
			'
			SELECT title, id_board
			FROM {db_prefix}cf_forms
			WHERE id_form = {int:id_form}',
			array(
				'id_form' => $data['id_form'],
			)
		);

		$parent_data = $smcFunc['db_fetch_assoc']($request);
		$smcFunc['db_free_result']($request);

		//	Do we need to delete the field?
		if (isset($_GET['delete']))
		{
			$smcFunc['db_query'](
				'',
				'
				DELETE
				FROM {db_prefix}cf_fields
				WHERE id_field = {int:id_field}',
				array(
					'id_field' => $field_id,
				)
			);
			redirectexit("action=admin;area=modsettings;sa=customform;form_id=" . $data['id_form'] . ";");
		}
		//	Do we need to update the field?
		elseif (isset($_GET['update']))
		{
			//	Perform the updating query.
			$smcFunc['db_query'](
				'',
				'
				UPDATE {db_prefix}cf_fields
				SET title = {string:title}, text = {string:text},
				type = {string:type}, type_vars = {string:type_vars}
				WHERE id_field = {int:id_field}',
				array(
					'id_field' => $field_id,
					'title' => $_REQUEST['field_title'],
					'text' => $_REQUEST['field_text'],
					'type' => $_REQUEST['field_type'],
					'type_vars' => $_REQUEST['field_type_vars'],
				)
			);
			redirectexit("action=admin;area=modsettings;sa=customform;form_id=" . $data['id_form'] . ";");
		}
		//	Do we need to move the field?
		elseif (isset($_GET['moveup'])
			|| isset($_GET['movedown'])
		)
		{
			$factor = isset($_GET['moveup']) ? -1 : 1;

			//	Get a list of all of the 'siblings' of this field.
			$request = $smcFunc['db_query'](
				'',
				'
				SELECT id_field
				FROM {db_prefix}cf_fields
				WHERE id_form = {int:id_form}
				ORDER BY id_field',
				array(
					'id_form' => $data['id_form'],
				)
			);

			$siblings = array();
			$count = 0;
			$field_pos = 0;

			//	Make a list of the siblings
			While ($row = $smcFunc['db_fetch_assoc']($request))
			{
				//	Get the spot of the current field;
				if ($row['id_field'] == $field_id)
					$field_pos = $count;
				//	Store the necessary information.
				$siblings[] = $row['id_field'];
				$count++;
			}

			//	Free the db result.
			$smcFunc['db_free_result']($request);

			//	Can we move the field?
			if (!($count == 0)
				&& !empty($siblings)
				&& !(($field_pos == 0) && ($factor == -1))
				&& !(($field_pos == $count - 1) && ($factor == 1))
			)
			{
				$replace_id = $siblings[$field_pos + $factor];
				//	Perform the rather hacky updating queries. - They do work, just hackily! ;D
				$smcFunc['db_query'](
					'',
					'
					UPDATE {db_prefix}cf_fields
					SET id_field = \'0\'
					WHERE id_field = {int:field_id}',
					array(
						'field_id' => $field_id,
					)
				);
				$smcFunc['db_query'](
					'',
					'
					UPDATE {db_prefix}cf_fields
					SET id_field = {int:field_id}
					WHERE id_field = {int:replace_id}',
					array(
						'field_id' => $field_id,
						'replace_id' => $replace_id,
					)
				);
				$smcFunc['db_query'](
					'',
					'
					UPDATE {db_prefix}cf_fields
					SET id_field = {int:replace_id}
					WHERE id_field = \'0\'',
					array(
						'replace_id' => $replace_id,
					)
				);
			}
			//	Take us back to the form setting page.
			redirectexit("action=admin;area=modsettings;sa=customform;form_id=" . $data['id_form'] . ";");
		}

		require_once($sourcedir . '/Class-CustomForm.php');
		$result = [];
		foreach (customform_list_classes() as $cn)
			$result[$cn] = $txt['customform_type_' . $cn];

		//	Otherwise just show the settings for this field.
		$config_vars = array(
			array(
				'text',
				'field_title',
				'value' => $data['title'],
				'text_label' => $txt['title'],
				'help' => 'customform_field_title',
			),
			array(
				'large_text',
				'field_text',
				'value' => $data['text'],
				'text_label' => $txt['customform_text'],
				'help' => 'customform_text',
			),
			array(
				'select',
				'field_type',
				'value' => strtr($data['type'], [
					'largetextbox' =>'textarea',
					'textbox' =>'text',
					'checkbox' =>'check',
					'selectbox' =>'select',
					'float' =>'text',
					'int' =>'text',
					'radiobox' =>'radio',
					'infobox' =>'info'
				]),
				'text_label' => $txt['customform_type'],
				'help' => 'customform_type',
				$result
			),
			array(
				'text',
				'field_type_vars',
				'value' => $data['type_vars'],
				'text_label' => $txt['customform_type_vars'],
				'help' => 'customform_type_vars',
			),
		);

		//	Set up the variables needed by the template.
		$context['settings_title'] =
			'<a href="' . $scripturl . '?action=admin;area=modsettings;sa=customform;">' . $txt['customform_generalsettings_heading']
			. '</a> -> <a href="' . $scripturl . '?action=admin;area=modsettings;sa=customform;form_id=' . $data['id_form'] . ';">"' . $parent_data['title'] . '" ' . $txt['customform_form']
			. '</a> -> "' . $data['title'] . '" ' . $txt['customform_field'];
		$context['post_url'] =
			$scripturl . '?action=admin;area=modsettings;sa=customform;field_id=' . $field_id . ';update;';
		$context['page_title'] = $txt['customform_tabheader'];
		$context['sub_template'] = 'show_settings';
		$context['html_headers'] .= '
			<script>
				window.addEventListener("DOMContentLoaded", function()
				{
					var
						el = document.createElement("div"),
						textarea = document.getElementById("field_text"),
						textareaLengthCheck = () =>
						{
							var charactersLeft = 4096 - textarea.value.length;
							el.innerHTML = "Max characters: <b>4096</b>; characters remaining: <b>" + charactersLeft + "</b>";
							if (charactersLeft < 0)
							{
								el.className = "error";
								textarea.style.border = "1px solid red";
							}
							else
							{
								el.className = "";
								textarea.style.border = "";
							}
						};
					el.className = "smalltext";
					textarea.parentNode.appendChild(el);
					textarea.addEventListener("keyup", textareaLengthCheck, false);

					textareaLengthCheck.call(textarea);
				});
			</script>';

		//	Finally prepare the settings array to be shown by the 'show_settings' template.
		prepareDBSettingContext($config_vars);
		if (function_exists('createToken'))
			createToken('admin-dbsc');
	}
	//	Do we need to add a new form?
	elseif (isset($_GET['add_form']))
	{
		//	Do the creation query.
		$smcFunc['db_insert'](
			'',
			'{db_prefix}cf_forms',
			array('id_board' => 'int'),
			array('0'),
			array('id_form')
		);

		//	Get a list of all of the form ids.
		$form_id = $smcFunc['db_insert_id']('{db_prefix}cf_forms', 'id_form');

		//	Take us to the newly created form.
		redirectexit("action=admin;area=modsettings;sa=customform;form_id=" . $form_id . ";");
	}
	//	Otherwise show the generic list of custom forms.
	else
	{
		$config_vars = array(
			array('permissions', 'customform_view_perms'),
			array('text', 'customform_view_title'),
			array('text', 'customform_view_text'),
		);

		//	Save the permissions?
		if (isset($_GET['update']))
		{
			//	Make sure that an admin is doing the updating.
			checkSession();
			saveDBSettings($config_vars);
			redirectexit("action=admin;area=modsettings;sa=customform;");
		}

		$list = array(
			'id' => 'customform_list',
			'title' => $txt['customform_tabheader'],
			'no_items_label' => $txt['customform_list_noelements'],
			'get_items' => array(
				'function' => 'list_CustomForms',
			),
			'columns' => array(
				'title' => array(
					'header' => array(
						'value' => $txt['title'],
					),
					'data' => array(
						'db' => 'title',
					),
				),
				'board' => array(
					'header' => array(
						'value' => $txt['customform_board_id'],
					),
					'data' => array(
						'db' => 'board',
					),
				),
				'permissions' => array(
					'header' => array(
						'value' => $txt['edit_permissions'],
					),
					'data' => array(
						'db' => 'permissions',
					),
				),
				'modify' => array(
					'header' => array(
						'value' => $txt['modify'],
					),
					'data' => array(
						'db' => 'modify',
					),
				),
			),
			'additional_rows' => array(
				array(
					'position' => 'below_table_data',
					'value' => '<a href="' . $scripturl . '?action=admin;area=modsettings;sa=customform;add_form;">' . $txt['customform_add_form'] . '</a>',
					'class' => 'titlebg',
				),
			),
		);


		//	Call the function to setup the list for the template.
		require_once($sourcedir . '/Subs-List.php');
		createList($list);

		//	Set up the variables needed by the template.
		$context['settings_title'] = $txt['customform_generalsettings_heading'];
		$context['page_title'] = $txt['customform_tabheader'];
		$context['default_list'] = 'customform_list';
		$context['post_url'] = $scripturl . '?action=admin;area=modsettings;sa=customform;update';
		loadTemplate('CustomForm');

		$context['sub_template'] = 'customform_GeneralSettings';

		//	Finally prepare the settings array to be shown by the 'show_settings' template.
		prepareDBSettingContext($config_vars);
		if (function_exists('createToken'))
		{
			// Two tokens because saving these settings requires both save_inline_permissions and saveDBSettings
			createToken('admin-mp');
			createToken('admin-dbsc');
		}
	}
}

//	Fucntion to produce a list of custom forms.
function list_CustomForms()
{
	global $txt, $scripturl, $smcFunc;

	//	Get the data from the cf_forms table.
	$request = $smcFunc['db_query'](
		'',
		'
		SELECT id_form, title, id_board
		FROM {db_prefix}cf_forms'
	);

	//	Get some general permissions info.
	$permissions = get_customform_permissions();
	$membergroups = get_customform_membergroups();
	$list = array();

	//	Go through every form.
	While ($row = $smcFunc['db_fetch_assoc']($request))
	{
		//	Create a list of the groups which can use this form.
		$permissions_string = $txt['admin'];
		if (isset($permissions['custom_forms_' . $row['id_form']]))
			foreach ($permissions['custom_forms_' . $row['id_form']] as $membergroup_id)
				$permissions_string .= ', ' . $membergroups[$membergroup_id];

		//	Try to find the name of the board.
		$board_name = 'Invalid Board';

		$board_request = $smcFunc['db_query'](
			'',
			'
			SELECT name
			FROM {db_prefix}boards
			WHERE id_board = {int:id_board}
			AND redirect = \'\'',
			array(
				'id_board' => $row['id_board'],
			)
		);

		//	Try to get the name from the returned row.
		if ($board = $smcFunc['db_fetch_assoc']($board_request))
			$board_name = $board['name'];
		$smcFunc['db_free_result']($board_request);

		//	Add the current entry into the list.
		$list[] = array(
			'title' => $row['title'],
			'board' => $row['id_board'] . ' ("' . $board_name . '")',
			'permissions' => $permissions_string,
			'modify' => '
			<table width="100%">
				<tr>
					<td width="50%" style="text-align:center;">
						<a href="' . $scripturl . '?action=admin;area=modsettings;sa=customform;form_id=' . $row['id_form'] . ';">
							(' . $txt['customform_edit'] . ')
						</a>
					</td>
					<td width="50%" style="text-align:center;">
						<a href="' . $scripturl . '?action=admin;area=modsettings;sa=customform;form_id=' . $row['id_form'] . ';delete;" onclick="return confirm(\'' . $txt['customform_delete_warning'] . '\')" >
							(' . $txt['delete'] . ')
						</a>
					</td>
				</tr>
			</table>',
		);
	}
	$smcFunc['db_free_result']($request);

	return $list;
}

//	Fucntion to produce a list of custom form fields.
function list_customform_fields($nul0, $nul1, $nul2, $id)
{
	global $txt, $scripturl, $sourcedir, $smcFunc;

	//	Get the data from the cf_fields table.
	$request = $smcFunc['db_query'](
		'',
		'
		SELECT id_field, title, type, text
		FROM {db_prefix}cf_fields
		WHERE id_form = {int:id_form}
		ORDER BY id_field',
		array(
			'id_form' => $id,
		)
	);

	$data = array();

	while ($row = $smcFunc['db_fetch_assoc']($request))
		$data[] = $row;

	$list = array();
	$i = 1;
	$end = count($data);

	require_once($sourcedir . '/Class-CustomForm.php');
	$result = [];
	foreach (customform_list_classes() as $cn)
		$result[$cn] = $txt['customform_type_' . $cn];
	$result += [
		'largetextbox' =>$result['textarea'],
		'textbox' =>$result['text'],
		'checkbox' =>$result['check'],
		'selectbox' =>$result['select'],
		'float' =>$result['text'],
		'int' =>$result['text'],
		'radiobox' =>$result['radio'],
		'infobox' =>$result['info']
	];
	foreach ($data as $field)
	{
		$list[] = array(
			'title' => $field['title'],
			'text' => $field['text'],
			'type' => $result[$field['type']]??$field['type'],
			'modify' => '
			<table width="100%">
				<tr>
					<td width="25%" style="text-align:center;">
						<a href="' . $scripturl . '?action=admin;area=modsettings;sa=customform;field_id=' . $field['id_field'] . ';moveup;">
							' . (($i != 1) ? '(' . $txt['customform_moveup'] . ')' : '') . '
						</a>
					</td>
					<td width="25%" style="text-align:center;">
						<a href="' . $scripturl . '?action=admin;area=modsettings;sa=customform;field_id=' . $field['id_field'] . ';movedown;" >
							' . (($i != $end) ? '(' . $txt['customform_movedown'] . ')' : '') . '
						</a>
					</td>
					<td width="50%" style="text-align:center;">
						<a href="' . $scripturl . '?action=admin;area=modsettings;sa=customform;field_id=' . $field['id_field'] . ';">
							(' . $txt['customform_edit'] . ')
						</a>
					</td>
					<td width="50%" style="text-align:center;">
						<a href="' . $scripturl . '?action=admin;area=modsettings;sa=customform;field_id=' . $field['id_field'] . ';delete;" onclick="return confirm(\'' . $txt['customform_delete_warning'] . '\')" >
							(' . $txt['delete'] . ')
						</a>
					</td>
				</tr>
			</table>',
		);
		$i++;
	}
	$smcFunc['db_free_result']($request);

	return $list;
}

//	Get all of the permissions settings for the Custom Form Mod.
function get_customform_permissions()
{
	global $context, $smcFunc;

	$permissions = array();

	// 	Get the permissions for the Custom Menu System.
	$request = $smcFunc['db_query'](
		'',
		'
		SELECT permission, id_group
		FROM {db_prefix}permissions
		WHERE permission
		LIKE \'custom_forms_%\''
	);

	//	Store the data in a way that is easy to use. Permission => array (id_groups)
	while ($row = $smcFunc['db_fetch_assoc']($request))
		$permissions[$row['permission']][] = $row['id_group'];

	//	Don't forget to free the request!!!
	$smcFunc['db_free_result']($request);

	return $permissions;
}

//	Simple function to return all the membergroups.
function get_customform_membergroups()
{
	global $smcFunc, $modSettings, $txt;

	$membergroups = array();

	//	Fix up a few errors that occur, by adding guests and regular members to the list.
	$membergroups['-1'] = $txt['guests'];
	$membergroups['0'] = $txt['users'];

	// Get the permissions from the table, make sure we only get them for the Menu System.
	$request = $smcFunc['db_query'](
		'',
		'
		SELECT ID_GROUP, group_name
		FROM {db_prefix}membergroups' . (($modSettings['permission_enable_postgroups']) ? '' : '
		WHERE min_posts = -1')
	);

	//	Store the data in a way that is easy to use. Permission => array (id_groups)
	while ($row = $smcFunc['db_fetch_assoc']($request))
		$membergroups[$row['ID_GROUP']] = $row['group_name'];

	//	Don't forget to free the request!!!
	$smcFunc['db_free_result']($request);

	return $membergroups;
}

?>