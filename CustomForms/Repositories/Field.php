<?php
// Version: 1.0: Field.php
namespace CustomForms;

use \ModHelper\Database;

if (!defined('SMF')) {
	die('Hacking attempt...');
}

/**
 * @package CustomForms
 * @since 1.0
 */
class Field
{
	private $data;
	private $field;

	public function __construct($field)
	{
		$this->field = $field;
	}

	public function quickSave()
	{
		foreach (total_getManageCustomFormFields() as $field) {
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
	}

	public function quickSave($name, $description$enclose, $type, $length, $options, $active, $default, $can_search, $bbc, $mask, $regex, $groups)
	{
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
			'current_field' => $this->field,
			'name' => $name'],
			'description' => $description,
			'enclose' => $enclose,
			'type' => $type'],
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
			$name, $description, $enclose,
			$type, $length, $options, $active, $default,
			$can_search, $bbc, $mask, $regex, $groups,
		);
		call_integration_hook('integrate_save_post_field', array(&$up_col, &$up_data, &$in_col, &$in_data));

		if ($this->field) {
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
			$this->field = Database::insert_id('{db_prefix}custom_form_fields', 'id_field');
		}
	}

	public function rewriteLinks($forms)
	{
		Database::query('', '
			DELETE FROM {db_prefix}custom_form_field_link
			WHERE id_field = {int:current_field}',
			array(
				'current_field' => $this->field,
			)
		);
		if (!empty($forms)) {
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
	}

	public function load()
	{
		if ($this->field) {
			$request = Database::query('', '
				SELECT *
				FROM {db_prefix}custom_form_fields
				WHERE id_field = {int:current_field}',
				array(
					'current_field' => $this->field,
				)
			);
			$this->data = array();
			while ($row = Database::fetch_assoc($request)) {
				if ($row['type'] == 'textarea') {
					@list ($rows, $cols) = @explode(',', $row['default_value']);
				} else {
					$rows = 3;
					$cols = 30;
				}

				$this->data = array(
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
					'current_field' => $this->field,
				)
			);
			$this->data['forms'] = array();
			while ($row = Database::fetch_assoc($request)) {
				$this->data['forms'][] = $row['id_form'];
			}
			Database::free_result($request);
		}

		return $this->data;
	}
}
