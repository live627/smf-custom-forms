<?php
// Version: 1.0: Forms.php
namespace CustomForms\Repositories;

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
	protected function deleteForms($forms)
	{
		// Delete the user data first.
		Database::query('', '
			DELETE FROM {db_prefix}custom_form_field_data
			WHERE id_form IN ({array_int:fields})',
			array(
				'fields' => $forms,
			)
		);
		// Then the link.
		Database::query('', '
			DELETE FROM {db_prefix}custom_form_field_link
			WHERE id_form IN ({array_int:fields})',
			array(
				'fields' => $forms,
			)
		);
		// Finally - the forms themselves are gone!
		Database::query('', '
			DELETE FROM {db_prefix}custom_forms
			WHERE id_form IN ({array_int:fields})',
			array(
				'fields' => $forms,
			)
		);
		call_integration_hook('integrate_delete_custom_forms', array($forms));
	}

	public static function list_getManageCustomForms($start, $items_per_page, $sort)
	{
		global $smcFunc;

		$list = array();
		$request = Database::query('', '
			SELECT id_form, name, description, bbc, active, can_search
			FROM {db_prefix}custom_forms
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

	public static function total_getManageCustomForms()
	{
		global $smcFunc;

		$list = array();
		$request = Database::query('', '
			SELECT id_form, name, description, bbc
			FROM {db_prefix}custom_forms');
		while (list ($id_form, $name, $description, $bbc) = Database::fetch_row($request)) {
			$list[$id_form] = [$name, $description, $bbc];
		}
		Database::free_result($request);
		call_integration_hook('integrate_get_custom_forms', array(&$list));

		return $list;
	}

	public static function total_getManageCustomFormsSearchable()
	{
		global $smcFunc;

		$list = array();
		$request = Database::query('', '
			SELECT id_form, name, description, bbc
			FROM {db_prefix}custom_forms
			WHERE can_search = \'yes\'');
		while ($row = Database::fetch_assoc($request)) {
			$list[$row['id_form']] = $row;
		}
		Database::free_result($request);
		call_integration_hook('integrate_get_custom_forms_searchable', array(&$list));

		return $list;
	}

	 function get_custom_forms_filtered3()
	{
		global $context, $user_info;

		$fields = total_getManageCustomForms();
		$list = array();
		foreach ($fields as $field) {
			$group_list = explode(',', $field['groups']);
			$is_allowed = array_intersect($user_info['groups'], $group_list);
			if (empty($is_allowed)) {
				continue;
			}

			$list[$field['id_form']] = $field;
		}
		call_integration_hook('integrate_get_custom_forms_filtered', array(&$list, $form));

		return $list;
	}

	public static function list_getNumManageCustomForms()
	{
		global $smcFunc;

		$request = Database::query('', '
			SELECT COUNT(*)
			FROM {db_prefix}custom_forms');

		list ($numProfileFields) = Database::fetch_row($request);
		Database::free_result($request);

		return $numProfileFields;
	}

}
