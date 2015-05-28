<?php
// Version: 1.0: Dispatcher.php
namespace CustomForms\Controllers;

if (!defined('SMF')) {
	die('Hacking attempt...');
}

/**
 * @package CustomForms
 * @since 1.0
 */
class Dispatcher
{
	use \ModHelper\SingletonTrait;

	public function __construct()
	{
		global $context, $txt;

		// Load up all the tabs...
		$context[$context['admin_menu_name']]['tab_data'] = array(
			'title' => $txt['custom_forms'],
			'description' => $txt['custom_forms_desc'],
		);

		$sub_actions = [
			'index' => ['CustomForms\Controllers\Forms', 'Index', 'admin_forum'],
			'edit' => ['CustomForms\Controllers\Forms', 'Edit', 'admin_forum'],
			'index2' => ['CustomForms\Controllers\Fields', 'Index', 'admin_forum'],
			'edit2' => ['CustomForms\Controllers\Fields', 'Edit', 'admin_forum'],
		];

		// Default to sub action 'index'
		if (!isset($_GET['sa']) || !isset($sub_actions[$_GET['sa']])) {
			$_GET['sa'] = 'index';
		}
		$this_sub_action = $sub_actions[$_GET['sa']];
		$context['sub_template'] = $_GET['sa'];

		// This area is reserved for admins only - do this here since the menu code does not.
		isAllowedTo($this_sub_action[2]);

		// Calls a private function based on the sub-action
		(new $this_sub_action[0])->$this_sub_action[1]();
	}
}
