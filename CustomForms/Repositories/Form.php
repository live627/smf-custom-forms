<?php
// Version: 1.0: Form.php
namespace CustomForms\Repositories;

use \ModHelper\Database;

if (!defined('SMF')) {
	die('Hacking attempt...');
}

/**
 * @package CustomForms
 * @since 1.0
 */
class Form
{
	private $data;
	private $field;

	public function __construct($field)
	{
		$this->form = $field;
	}

	public function quickSave()
	{
		foreach (total_getManageCustomForms() as $field) {
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
	}

	protected function load()
	{
		if ($this->form) {
			$request = Database::query('', '
				SELECT *
				FROM {db_prefix}custom_forms
				WHERE id_form = {int:current_field}',
				array(
					'current_field' => $this->form,
				)
			);
			$this->data = array();
			while ($row = Database::fetch_assoc($request)) {
				$this->data = array(
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
					'current_field' => $this->form,
				)
			);
			$this->data['fields'] = array();
			while ($row = Database::fetch_assoc($request)) {
				$this->data['fields'][] = $row['id_field'];
			}
			Database::free_result($request);
		}

		// Setup the default values as needed.
		if (empty($this->data)) {
			$this->data = array(
				'name' => '',
				'description' => '',
				'bbc' => false,
				'active' => true,
				'can_search' => false,
				'fields' => array(),
				'groups' => array(),
			);
		}

		return $this->data;
	}

	public function quickSave($name, $description, $active, $can_search, $bbc, $groups)
	{
		$up_col = array(
			'name = {string:name}', ' description = {string:description}',
			'active = {string:active}', 'can_search = {string:can_search}', ' bbc = {string:bbc}',
			'groups = {string:groups}',
		);
		$up_data = array(
			'active' => $active,
			'can_search' => $can_search,
			'bbc' => $bbc,
			'current_field' => $this->form,
			'name' => $name,
			'description' => $description,
			'groups' => $groups,
		);
		$in_col = array(
			'name' => 'string', 'description' => 'string', 'active' => 'string',
			'can_search' => 'string', 'bbc' => 'string', 'groups' => 'string',
		);
		$in_data = array(
			$name'], $description'], $active, $can_search, $bbc, $groups,
		);
		call_integration_hook('integrate_save_post_field', array(&$up_col, &$up_data, &$in_col, &$in_data));

		if ($this->form) {
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
	}

	public function rewriteLinks($forms)
	{
		Database::query('', '
			DELETE FROM {db_prefix}custom_form_field_link
			WHERE id_field = {int:current_field}',
			array(
				'current_field' => $this->form,
			)
		);
		if (!empty($form)) {
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
	}
}
