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
		$field = isset($_REQUEST['fid']) ? (int)$_REQUEST['fid'] : 0;
		$this->repository = new \CustomForms\Repositories\Field($field);

		return $this;
	}

	public function Index()
	{
		global $txt, $context, $sourcedir, $smcFunc, $scripturl;

		// Deleting?
		if (isset($_POST['delete'], $_POST['remove'])) {
			checkSession();
			$this->repository->deleteFields($_POST['remove']);
			redirectexit('action=admin;area=customforms;sa=index2');
		}

		// Changing the status?
		if (isset($_POST['save'])) {
			checkSession();
			foreach (total_getManageCustomFormFields() as $field) {
				$bbc = !empty($_POST['bbc'][$field['id_field']]) ? 'yes' : 'no';
				$active = !empty($_POST['active'][$field['id_field']]) ? 'yes' : 'no';
				$can_search = !empty($_POST['can_search'][$field['id_field']]) ? 'yes' : 'no';
				$this->repository
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
				'function' => ['\\CustomForms\\Repositories\\Fields', 'list_getManageCustomFormFields'],
			),
			'get_count' => array(
				'function' => ['\\CustomForms\\Repositories\\Fields', 'list_getManageCustomFormFieldSize'],
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

	public function Edit()
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
			$context['field'] = $this->repository->dele();
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

			$this->repository->savee(
				$_POST['name'], $_POST['description'], $_POST['enclose'],
				$_POST['type'], $length, $options, $active, $default,
				$can_search, $bbc, $mask, $regex, $groups,
			);
			if (!empty($_POST['forms'])) {
				$this->repository->rewriteLinks(array_map(function ($value) use ($context) {
					return [(int)$value, $context['fid']];
				}, array_keys($_POST['forms'])));
			redirectexit('action=admin;area=customforms;sa=index2');
		} elseif (isset($_POST['delete']) && $context['field']['colname']) {
			checkSession();
			$this->repository->deleteFields([$context['fid']]);
			redirectexit('action=admin;area=customforms;sa=index2');
		}
	}
}
