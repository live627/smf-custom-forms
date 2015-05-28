<?php
// Version: 1.0: Fields.php
namespace CustomForms\Repositories;

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
	private function deleteFields($fields)
	{
		// Delete the user data first.
		Database::query('', '
			DELETE FROM {db_prefix}custom_form_field_data
			WHERE id_field IN ({array_int:fields})',
			array(
				'fields' => $fields,
			)
		);
		// Then the link.
		Database::query('', '
			DELETE FROM {db_prefix}custom_form_field_link
			WHERE id_field IN ({array_int:fields})',
			array(
				'fields' => $fields,
			)
		);
		// Finally - the fields themselves are gone!
		Database::query('', '
			DELETE FROM {db_prefix}custom_form_fields
			WHERE id_field IN ({array_int:fields})',
			array(
				'fields' => $fields,
			)
		);
		call_integration_hook('integrate_delete_custom_forms', array($fields));
	}

	public static function list_getManageCustomFormFields($start, $items_per_page, $sort)
	{
		global $smcFunc;

		$list = array();
		$request = Database::query('', '
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
		while ($row = Database::fetch_assoc($request)) {
			$list[] = $row;
		}
		Database::free_result($request);

		return $list;
	}

	public static function total_getManageCustomFormFields()
	{
		global $smcFunc;

		$list = array();
		$request = Database::query('', '
			SELECT *
			FROM {db_prefix}custom_form_fields');
		while ($row = Database::fetch_assoc($request)) {
			$list[$row['id_field']] = $row;
		}
		Database::free_result($request);
		$request = Database::query('', '
			SELECT id_form, id_field
			FROM {db_prefix}custom_form_field_link');
		while (list ($id_form, $id_field) = Database::fetch_row($request)) {
			if (!isset($list[$id_field]['forms'])) {
				$list[$id_field]['forms'] = [];
			}
			$list[$id_field]['forms'][$id_form] = $id_form;
		}
		Database::free_result($request);
		call_integration_hook('integrate_get_custom_forms', array(&$list));

		return $list;
	}

	public static function total_getManageCustomFormFieldsSearchable()
	{
		global $smcFunc;

		$list = array();
		$request = Database::query('', '
			SELECT *
			FROM {db_prefix}custom_form_fields
			WHERE can_search = \'yes\'');
		while ($row = Database::fetch_assoc($request)) {
			$list[$row['id_field']] = $row;
		}
		Database::free_result($request);
		call_integration_hook('integrate_get_custom_forms_searchable', array(&$list));

		return $list;
	}

	public static function get_custom_forms_filtered($id_form)
	{
		global $context, $user_info;

		$fields = self::total_getManageCustomFormFields();
		$list = array();
		foreach ($fields as $field) {
			if (!isset($field['forms'], $field['forms'][$id_form])) {
				continue;
			}

			$group_list = explode(',', $field['groups']);
			$is_allowed = array_intersect($user_info['groups'], $group_list);
			if (empty($is_allowed)) {
				continue;
			}

			$list[$field['id_field']] = $field;
		}
		call_integration_hook('integrate_get_custom_forms_filtered', array(&$list, $id_form));

		return $list;
	}

	public static function list_getManageCustomFormFieldSize()
	{
		global $smcFunc;

		$request = Database::query('', '
			SELECT COUNT(*)
			FROM {db_prefix}custom_form_fields');

		list ($numProfileFields) = Database::fetch_row($request);
		Database::free_result($request);

		return $numProfileFields;
	}
}
