<?php
// Version: 1.0: Fields.php
namespace CustomForms\Controllers;

use \ModHelper\Database;

if (!defined('SMF')) {
	die('Hacking attempt...');
}

/**
 * @package CustomForms
 * @since 1.0
 */
class Fields
{
	private $repository;

	public function __construct()
	{
		$this->repository = new \CustomForms\Repositories\Fields();

		return $this;
	}

	public function ListManageCustomFormFields()
	{
		global $txt, $context, $sourcedir, $smcFunc, $scripturl;

		// Deleting?
		if (isset($_POST['delete'], $_POST['remove'])) {
			checkSession();
			$this->deleteFields($_POST['remove']);
			redirectexit('action=admin;area=customforms;sa=index2');
		}

		// Changing the status?
		if (isset($_POST['save'])) {
			checkSession();
			foreach (total_getManageCustomFormFields() as $field) {
				$bbc = !empty($_POST['bbc'][$field['id_field']]) ? 'yes' : 'no';
				if ($bbc != $field['bbc']) {
					Database::query('', '
						UPDATE {db_prefix}custom_form_fields
						SET bbc = {string:bbc}
						WHERE id_field = {int:field}',
						array(
							'bbc' => $bbc,
							'field' => $field['id_field'],
						)
					);
				}

				$active = !empty($_POST['active'][$field['id_field']]) ? 'yes' : 'no';
				if ($active != $field['active']) {
					Database::query('', '
						UPDATE {db_prefix}custom_form_fields
						SET active = {string:active}
						WHERE id_field = {int:field}',
						array(
							'active' => $active,
							'field' => $field['id_field'],
						)
					);
				}

				$can_search = !empty($_POST['can_search'][$field['id_field']]) ? 'yes' : 'no';
				if ($can_search != $field['can_search']) {
					Database::query('', '
						UPDATE {db_prefix}custom_form_fields
						SET can_search = {string:can_search}
						WHERE id_field = {int:field}',
						array(
							'can_search' => $can_search,
							'field' => $field['id_field'],
						)
					);
				}
				call_integration_hook('integrate_update_post_field', array($field));
			}
			redirectexit('action=admin;area=customforms;sa=index2');
		}

		// New field?
		if (isset($_POST['new'])) {
			redirectexit('action=admin;area=customforms;sa=edit2');
		}

		$listOptions = array(
			'id' => 'custom_forms_fields',
			'base_href' => $scripturl . '?action=action=admin;area=customforms;sa=index2',
			'default_sort_col' => 'name',
			'no_items_label' => $txt['custom_forms_none'],
			'items_per_page' => 25,
			'get_items' => array(
				'function' => ['\\CustomForms\\ManageCustomFormFields', 'list_getManageCustomFormFields'],
			),
			'get_count' => array(
				'function' => ['\\CustomForms\\ManageCustomFormFields', 'list_getManageCustomFormFieldSize'],
			),
			'columns' => array(
				'name' => array(
					'header' => array(
						'value' => $txt['custom_forms_fieldname'],
						'style' => 'text-align: left;',
					),
					'data' => array(
						'function' => create_function('$rowData', '
							global $scripturl;

							return sprintf(\'<a href="%1$s?action=admin;area=customforms;sa=edit2;fid=%2$d">%3$s</a><div class="smalltext">%4$s</div>\', $scripturl, $rowData[\'id_field\'], $rowData[\'name\'], $rowData[\'description\']);
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
						'value' => $txt['custom_forms_fieldtype'],
					),
					'data' => array(
						'function' => create_function('$rowData', '
							global $txt;

							$textKey = sprintf(\'custom_forms_type_%1$s\', $rowData[\'type\']);
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
						'value' => $txt['custom_forms_bbc'],
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
						'value' => $txt['custom_forms_active'],
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
						'value' => $txt['custom_forms_can_search'],
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
							'format' => '<a href="' . $scripturl . '?action=admin;area=customforms;sa=edit2;fid=%1$s">' . $txt['modify'] . '</a>',
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
				'href' => $scripturl . '?action=admin;area=customforms;sa=index2',
				'name' => 'postProfileFields',
			),
			'additional_rows' => array(
				array(
					'position' => 'below_table_data',
					'value' => '<input type="submit" name="save" value="' . $txt['save'] . '" class="submit">&nbsp;&nbsp;<input type="submit" name="delete" value="' . $txt['delete'] . '" onclick="return confirm(' . JavaScriptEscape($txt['custom_forms_delete_sure']) . ');" class="delete">&nbsp;&nbsp;<input type="submit" name="new" value="' . $txt['custom_forms_make_new'] . '" class="new">',
					'style' => 'text-align: right;',
				),
			),
		);
		require_once($sourcedir . '/Subs-List.php');
		call_integration_hook('integrate_list_custom_forms', array(&$listOptions));
		createList($listOptions);
		$context['sub_template'] = 'show_list';
		$context['default_list'] = 'custom_forms_fields';
	}

	public function EditManageCustomFormField()
	{
		global $txt, $scripturl, $context, $settings, $smcFunc;

		$context['fid'] = isset($_REQUEST['fid']) ? (int)$_REQUEST['fid'] : 0;
		$context['page_title'] = $txt['custom_forms'] . ' - ' . ($context['fid'] ? $txt['custom_forms_title'] : $txt['custom_forms_add']);
		$context['page_title2'] = $txt['custom_forms'] . ' - ' . ($context['fid'] ? $txt['custom_forms_title'] : $txt['custom_forms_add']);
		$context['html_headers'] .= '<script type="text/javascript" src="' . $settings['default_theme_url'] . '/scripts/customformsadmin.js"></script>';
		loadTemplate('ManageCustomForms');

		$request = Database::query('', '
			SELECT id_form, name
			FROM {db_prefix}custom_forms');
		$context['forms'] = array();
		while ($row = Database::fetch_assoc($request)) {
			$context['forms'][$row['id_form']] = $row['name'];
		}
		Database::free_result($request);

		$request = Database::query('', '
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
		while ($row = Database::fetch_assoc($request)) {
			$context['groups'][$row['id_group']] = '<span' . ($row['online_color'] ? ' style="color: ' . $row['online_color'] . '"' : '') . '>' . $row['group_name'] . '</span>';
		}
		Database::free_result($request);

		loadLanguage('Profile');

		if ($context['fid']) {
			$request = Database::query('', '
				SELECT *
				FROM {db_prefix}custom_form_fields
				WHERE id_field = {int:current_field}',
				array(
					'current_field' => $context['fid'],
				)
			);
			$context['field'] = array();
			while ($row = Database::fetch_assoc($request)) {
				if ($row['type'] == 'textarea') {
					@list ($rows, $cols) = @explode(',', $row['default_value']);
				} else {
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
			Database::free_result($request);

			$request = Database::query('', '
				SELECT id_form
				FROM {db_prefix}custom_form_field_link
				WHERE id_field = {int:current_field}',
				array(
					'current_field' => $context['fid'],
				)
			);
			$context['field']['forms'] = array();
			while ($row = Database::fetch_assoc($request)) {
				$context['field']['forms'][] = $row['id_form'];
			}
			Database::free_result($request);
		}

		// Setup the default values as needed.
		if (empty($context['field'])) {
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
		}

		// Are we saving?
		if (isset($_POST['save'])) {
			checkSession();

			if (trim($_POST['name']) == '') {
				fatal_lang_error('post_option_need_name');
			}
			$_POST['name'] = $smcFunc['htmlspecialchars']($_POST['name']);
			$_POST['description'] = $smcFunc['htmlspecialchars']($_POST['description']);

			$bbc = !empty($_POST['bbc']) ? 'yes' : 'no';
			$active = !empty($_POST['active']) ? 'yes' : 'no';
			$can_search = !empty($_POST['can_search']) ? 'yes' : 'no';

			$mask = isset($_POST['mask']) ? $_POST['mask'] : '';
			$regex = isset($_POST['regex']) ? $_POST['regex'] : '';
			$length = isset($_POST['length']) ? (int)$_POST['length'] : 255;
			$groups = !empty($_POST['groups']) ? implode(',', array_keys($_POST['groups'])) : '';

			$options = '';
			$newOptions = array();
			$default = isset($_POST['default_check']) && $_POST['type'] == 'check' ? 1 : '';
			if (!empty($_POST['select_option']) && ($_POST['type'] == 'select' || $_POST['type'] == 'radio')) {
				foreach ($_POST['select_option'] as $k => $v) {
					$v = $smcFunc['htmlspecialchars']($v);
					$v = strtr($v, array(',' => ''));

					if (trim($v) == '') {
						continue;
					}

					$newOptions[$k] = $v;

					if (isset($_POST['default_select']) && $_POST['default_select'] == $k) {
						$default = $v;
					}
				}
				$options = implode(',', $newOptions);
			}

			if ($_POST['type'] == 'textarea') {
				$default = (int)$_POST['rows'] . ',' . (int)$_POST['cols'];
			}

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

			if ($context['fid']) {
				Database::query('', '
					UPDATE {db_prefix}custom_form_fields
					SET
						' . implode(',
						', $up_col) . '
					WHERE id_field = {int:current_field}',
					$up_data
				);
			} else {
				Database::insert('',
					'{db_prefix}custom_form_fields',
					$in_col,
					$in_data,
					array('id_field')
				);
				$context['fid'] = Database::insert_id('{db_prefix}custom_form_fields', 'id_field');
			}
			if (!empty($_POST['forms'])) {
				Database::query('', '
					DELETE FROM {db_prefix}custom_form_field_link
					WHERE id_field = {int:current_field}',
					array(
						'current_field' => $context['fid'],
					)
				);
				$forms = array_map(function ($value) use ($context) {
					return [(int)$value, $context['fid']];
				}, array_keys($_POST['forms']));
				Database::insert('',
					'{db_prefix}custom_form_field_link',
					array('id_form' => 'int', 'id_field' => 'int'),
					$forms,
					array('id_field')
				);
			}

			/* // As there's currently no option to priorize certain fields over others, let's order them alphabetically.
			Database::query('', '
				ALTER TABLE {db_prefix}custom_form_fields
				ORDER BY name',
				array(
					'db_error_skip' => true,
				)
			); */
			redirectexit('action=admin;area=customforms;sa=index2');
		} elseif (isset($_POST['delete']) && $context['field']['colname']) {
			checkSession();
			$this->deleteFields($context['fid']);
			redirectexit('action=admin;area=customforms;sa=index2');
		}
	}
}
