<?php
// Version: 1.0: Integration.php
namespace CustomForms\Services;

if (!defined('SMF')) {
	die('Hacking attempt...');
}

/**
 * @package CustomForms
 * @since 1.0
 */
class Integration
{
	public static function admin_areas(&$admin_areas)
	{
		global $txt;
		loadLanguage('ManageCustomForms');
		$admin_areas['layout']['areas']['customforms'] = array(
			'label' => $txt['custom_forms'],
			'icon' => 'settings.gif',
			'function' => function() { \CustomForms\Controllers\Dispatcher::getInstance(); },
			'subsections' => array(
				'index' => array($txt['custom_forms_menu_index']),
				'edit' => array($txt['custom_forms_menu_edit']),
				'index2' => array($txt['custom_forms_menu_index2']),
				'edit2' => array($txt['custom_forms_menu_edit2']),
			),
		);
	}

	public static function load_theme()
	{
		loadLanguage('CustomForms');
		global $sourcedir;
		if (!class_exists('ModHelper\Psr4AutoloaderClass')) {
			require_once(__DIR__ . '/CustomForms/ModHelper/Psr4AutoloaderClass.php');
		}
		// instantiate the loader
		$loader = new \ModHelper\Psr4AutoloaderClass;
		// register the autoloader
		$loader->register();
		// register the base directories for the namespace prefix
		$loader->addNamespace('ModHelper', $sourcedir . '/CustomForms/ModHelper');
		$loader->addNamespace('CustomForms', $sourcedir . '/CustomForms');
		$loader->addNamespace('Suki', $sourcedir . '/CustomForms/Suki');
	}

	public static function menu_buttons(&$menu_buttons)
	{
		global $txt, $context, $modSettings, $scripturl;
		$new_button = array(
			'title' => $txt['custom_forms'],
			'href' => $scripturl . '?action=forms',
			'show' => allowedTo('view_custom_forms'),
		);
		$new_menu_buttons = array();
		foreach ($menu_buttons as $area => $info) {
			$new_menu_buttons[$area] = $info;
			if ($area == 'mlist') {
				$new_menu_buttons['forms'] = $new_button;
			}
		}
		$menu_buttons = $new_menu_buttons;
	}

	public static function ModifyCustomFormsSettings($return_config = false)
	{
		global $txt, $scripturl, $context, $settings, $sc;
		$config_vars = array(
			array('check', 'custom_forms_enable'),
			'',
			array('check', 'custom_forms_enable_menu'),
		);
		if ($return_config) {
			return $config_vars;
		}
		if (isset($_GET['save'])) {
			checkSession();
			saveDBSettings($config_vars);
			writeLog();
			redirectexit('action=admin;area=customforms;sa=settings');
		}
		$context['post_url'] = $scripturl . '?action=admin;area=customforms;save;sa=settings';
		$context['settings_title'] = $txt['custom_forms'];
		prepareDBSettingContext($config_vars);
	}

	public static function actions(&$action_array)
	{
		$action_array['forms'] = array('CustomForms/CustomForms.php', '\CustomForms\CustomForms::getInstance');
	}

	public static function load_permissions(&$permissionGroups, &$permissionList, &$leftPermissionGroups, &$hiddenPermissions, &$relabelPermissions)
	{
		$permissionList['membergroup'] += array(
			'view_custom_forms' => array(false, 'general', 'view_basic_info'),
		);
	}
}
