<?php
// Version: 1.0: Forms.php
namespace CustomForms\Controllers;

use \ModHelper\Database;

if (!defined('SMF')) {
	die('Hacking attempt...');
}

/**
 * @package CustomForms
 * @since 1.0
 */
class Forms
{
	private $repository;

	public function __construct()
	{
		$this->repository = new \CustomForms\Repositories\Forms();

		return $this;
	}

	public function List()
	{
		global $txt, $context, $sourcedir, $smcFunc, $scripturl;

		// Deleting?
		if (isset($_POST['delete'], $_POST['remove'])) {
			checkSession();
			$this->deleteForms($_POST['remove']);
			redirectexit('action=admin;area=customforms');
		}

		// Changing the status?
		if (isset($_POST['save'])) {
			checkSession();
			foreach (total_getManageCustomForms() as $field) {
				$bbc = !empty($_POST['bbc'][$field['id_form']]) ? 'yes' : 'no';
				if ($bbc != $field['bbc']) {
					Database::query('', '
						UPDATE {db_prefix}custom_forms
						SET bbc = {string:bbc}
						WHERE id_form = {int:field}',
						array(
							'bbc' => $bbc,
							'field' => $field['id_form'],
						)
					);
				}

				$active = !empty($_POST['active'][$field['id_form']]) ? 'yes' : 'no';
				if ($active != $field['active']) {
					Database::query('', '
						UPDATE {db_prefix}custom_forms
						SET active = {string:active}
						WHERE id_form = {int:field}',
						array(
							'active' => $active,
							'field' => $field['id_form'],
						)
					);
				}

				$can_search = !empty($_POST['can_search'][$field['id_form']]) ? 'yes' : 'no';
				if ($can_search != $field['can_search']) {
					Database::query('', '
						UPDATE {db_prefix}custom_forms
						SET can_search = {string:can_search}
						WHERE id_form = {int:field}',
						array(
							'can_search' => $can_search,
							'field' => $field['id_form'],
						)
					);
				}
				call_integration_hook('integrate_update_post_field', array($field));
			}
			redirectexit('action=admin;area=customforms');
		}

		// New field?
		if (isset($_POST['new'])) {
			redirectexit('action=admin;area=customforms;sa=edit');
		}

		$listOptions = array(
			'id' => 'custom_forms_fields',
			'base_href' => $scripturl . '?action=action=admin;area=customforms',
			'default_sort_col' => 'name',
			'no_items_label' => $txt['custom_forms_none'],
			'items_per_page' => 25,
			'get_items' => array(
				'function' => ['\\CustomForms\\ManageCustomForms', 'list_getManageCustomForms'],
			),
			'get_count' => array(
				'function' => ['\\CustomForms\\ManageCustomForms', 'list_getNumManageCustomForms'],
			),
			'columns' => array(
				'name' => array(
					'header' => array(
						'value' => $txt['custom_forms_name'],
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
						'value' => $txt['custom_forms_bbc'],
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
						'value' => $txt['custom_forms_active'],
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
						'value' => $txt['custom_forms_can_search'],
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

	public function Edit()
	{
		global $txt, $scripturl, $context, $settings, $smcFunc;

		$context['fid'] = isset($_REQUEST['fid']) ? (int)$_REQUEST['fid'] : 0;
		$context['page_title'] = $txt['custom_forms'] . ' - ' . ($context['fid'] ? $txt['custom_forms_title'] : $txt['custom_forms_add']);
		$context['page_title2'] = $txt['custom_forms'] . ' - ' . ($context['fid'] ? $txt['custom_forms_title'] : $txt['custom_forms_add']);
		$context['html_headers'] .= '<script type="text/javascript" src="' . $settings['default_theme_url'] . '/scripts/customformsadmin.js"></script>';
		loadTemplate('ManageCustomForms');

		$request = Database::query('', '
			SELECT id_field, name, type
			FROM {db_prefix}custom_form_fields');
		$context['fields'] = array();
		while ($row = Database::fetch_assoc($request)) {
			$context['fields'][$row['id_field']] = $row['type'] . ' - ' . $row['name'];
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
				FROM {db_prefix}custom_forms
				WHERE id_form = {int:current_field}',
				array(
					'current_field' => $context['fid'],
				)
			);
			$context['field'] = array();
			while ($row = Database::fetch_assoc($request)) {
				$context['field'] = array(
					'name' => $row['name'],
					'description' => $row['description'],
					'bbc' => $row['bbc'] == 'yes',
					'active' => $row['active'] == 'yes',
					'can_search' => $row['can_search'] == 'yes',
					'fields' => array(),
					'groups' => !empty($row['groups']) ? explode(',', $row['groups']) : array(),
				);
			}
			Database::free_result($request);

			$request = Database::query('', '
				SELECT id_field
				FROM {db_prefix}custom_form_field_link
				WHERE id_form = {int:current_field}',
				array(
					'current_field' => $context['fid'],
				)
			);
			$context['field']['fields'] = array();
			while ($row = Database::fetch_assoc($request)) {
				$context['field']['fields'][] = $row['id_field'];
			}
			Database::free_result($request);
		}

		// Setup the default values as needed.
		if (empty($context['field'])) {
			$context['field'] = array(
				'name' => '',
				'description' => '',
				'bbc' => false,
				'active' => true,
				'can_search' => false,
				'fields' => array(),
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

			$groups = !empty($_POST['groups']) ? implode(',', array_keys($_POST['groups'])) : '';
			$forms = !empty($_POST['forms']) ? implode(',', array_keys($_POST['forms'])) : '';

			$up_col = array(
				'name = {string:name}', ' description = {string:description}',
				'active = {string:active}', 'can_search = {string:can_search}', ' bbc = {string:bbc}',
				'groups = {string:groups}',
			);
			$up_data = array(
				'active' => $active,
				'can_search' => $can_search,
				'bbc' => $bbc,
				'current_field' => $context['fid'],
				'name' => $_POST['name'],
				'description' => $_POST['description'],
				'groups' => $groups,
			);
			$in_col = array(
				'name' => 'string', 'description' => 'string', 'active' => 'string',
				'can_search' => 'string', 'bbc' => 'string', 'groups' => 'string',
			);
			$in_data = array(
				$_POST['name'], $_POST['description'], $active, $can_search, $bbc, $groups,
			);
			call_integration_hook('integrate_save_post_field', array(&$up_col, &$up_data, &$in_col, &$in_data));

			if ($context['fid']) {
				Database::query('', '
					UPDATE {db_prefix}custom_forms
					SET
						' . implode(',
						', $up_col) . '
					WHERE id_form = {int:current_field}',
					$up_data
				);
			} else {
				Database::insert('',
					'{db_prefix}custom_forms',
					$in_col,
					$in_data,
					array('id_form')
				);
			}
			if (!empty($_POST['forms'])) {
				Database::query('', '
					DELETE FROM {db_prefix}custom_form_field_link
					WHERE id_field = {int:current_field}',
					array(
						'current_field' => $context['fid'],
					)
				);
				$fields = array_map(function ($value) use ($context) {
					return [$context['fid'], (int)$value];
				}, array_keys($_POST['fields']));
				Database::insert('',
					'{db_prefix}custom_form_field_link',
					array('id_form' => 'int', 'id_field' => 'int'),
					$fields,
					array('id_field')
				);
			}

			/* // As there's currently no option to priorize certain fields over others, let's order them alphabetically.
			Database::query('', '
				ALTER TABLE {db_prefix}custom_forms
				ORDER BY name',
				array(
					'db_error_skip' => true,
				)
			); */
			redirectexit('action=admin;area=customforms');
		} elseif (isset($_POST['delete']) && $context['field']['colname']) {
			checkSession();
			$this->deleteForms($context['fid']);
			redirectexit('action=admin;area=customforms');
		}
	}
}
