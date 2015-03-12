<?php
// Version 1.0: CustomForms.php
namespace CustomForms;

if (!defined('SMF')) {
	die('Hacking attempt...');
}

/**
 * @package CustomForms
 * @since 1.0
 */
class ManageCustomFormFields
{
	public function __construct()
	{
		global $boarddir, $context, $modSettings, $scripturl, $settings, $sourcedir, $txt;

		isAllowedTo('view_custom_forms');

		$context['current_page'] = isset($_REQUEST['in']) ? $_REQUEST['in'] : 'all';
		$forms = total_getManageCustomForms();

		switch ($context['current_page']) {
			case 'all':
				$num_per_page = 25;
				$start = 0;
				$context['page_index'] = constructPageIndex($scripturl . '?action=forms', $start, 7, $num_per_page);
				$context['page_title'] = $txt['additional'];
				require_once($sourcedir . '/ManageCustomForms.php');
				foreach ($forms as $id_form => list ($name, $description, $bbc)) {
					$context['forms'][$id_form]['link'] = '<a href="' . $scripturl . '?action=forms;in=' . $id_form . '>' . $name . '</a>';
					$context['forms'][$id_form]['description'] = $bbc == 'yes' ? parse_bbc($description) : $description;
				}
				break;

			default:
				$context['page_title'] = $forms[$context['current_page']][0];
				CustomForms\\Integration::load_fields(get_CustomForms\\Integration::filtered($context['current_page']));
		}

		require_once($sourcedir . '/Subs-Menu.php');

		// Define all the menu structure - see Subs-Menu.php for details!
		$custom_forms_areas = array(
			'forms' => array(
				'title' => $txt['custom_forms'],
				'areas' => array(
					'all' => array(
						'label' => $txt['custom_forms_all'],
					),
				),
			),
		);

		require_once($sourcedir . '/ManageCustomForms.php');
		foreach ($forms as $id_form => list ($name)) {
			$custom_forms_areas['forms']['areas'][$id_form]['label'] = $name;
			$custom_forms_areas['forms']['areas'][$id_form]['custom_url'] = $scripturl . '?action=forms;in=' . $id_form;
		}

		// We don't want or need the session in the URL - they're quite disruptive!
		$menuOptions = array(
			'disable_url_session_check' => true,
		);

		// Any files to include?
		if (!empty($modSettings['integrate_custom_forms_include'])) {
			$custom_forms_includes = explode(',', $modSettings['integrate_custom_forms_include']);
			foreach ($custom_forms_includes as $include) {
				$include = strtr(trim($include), array('$boarddir' => $boarddir, '$sourcedir' => $sourcedir, '$themedir' => $settings['theme_dir']));
				if (file_exists($include)) {
					require_once($include);
				}
			}
		}

		// Let them modify custom_forms areas easily.
		call_integration_hook('integrate_custom_forms_areas', array(&$custom_forms_areas));

		// Actually create the menu!
		$custom_forms_include_data = createMenu($custom_forms_areas, $menuOptions);
		unset($custom_forms_areas);

		// Nothing valid?
		if ($custom_forms_include_data == false) {
			fatal_lang_error('no_access', false);
		}

		// Build the link tree.
		$context['linktree'][] = array(
			'url' => $scripturl . '?action=forms',
			'name' => $txt['custom_forms'],
		);
		if (isset($custom_forms_include_data['current_area']) && $custom_forms_include_data['current_area'] != 'all') {
			$context['linktree'][] = array(
				'url' => $scripturl . '?action=forms;area=' . $custom_forms_include_data['current_area'],
				'name' => $custom_forms_include_data['label'],
			);
		}

		loadTemplate('CustomForms');
	}
}
