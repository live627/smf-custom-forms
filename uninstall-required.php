<?php

// If SSI.php is in the same place as this file, and SMF isn't defined...
if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF')) {
	require_once(dirname(__FILE__) . '/SSI.php');
} // Hmm... no SSI.php and no SMF?
elseif (!defined('SMF')) {
	die('<b>Error:</b> Cannot uninstall - please verify you put this in the same place as SMF\'s index.php.');
}

remove_integration_function('integrate_pre_include', '$sourcedir/CustomForms/ModHelper/Psr4AutoloaderClass.php');
remove_integration_function('integrate_load_theme', 'CustomForms\\Integration::load_theme');
remove_integration_function('integrate_actions', 'CustomForms\\Integration::actions');
remove_integration_function('integrate_menu_buttons', 'CustomForms\\Integration::menu_buttons');
remove_integration_function('integrate_modify_modifications', 'CustomForms\\Integration::modify_modifications');
remove_integration_function('integrate_admin_areas', 'CustomForms\\Integration::admin_areas');

?>
