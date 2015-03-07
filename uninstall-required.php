<?php

// If SSI.php is in the same place as this file, and SMF isn't defined...
if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
	require_once(dirname(__FILE__) . '/SSI.php');

// Hmm... no SSI.php and no SMF?
elseif (!defined('SMF'))
	die('<b>Error:</b> Cannot uninstall - please verify you put this in the same place as SMF\'s index.php.');

remove_integration_function('integrate_pre_include', '$sourcedir/Subs-CustomForms.php');
remove_integration_function('integrate_load_theme', 'custom_forms_load_theme');
remove_integration_function('integrate_actions', 'custom_forms_actions');
remove_integration_function('integrate_menu_buttons', 'custom_forms_menu_buttons');
remove_integration_function('integrate_modify_modifications', 'custom_forms_modify_modifications');
remove_integration_function('integrate_admin_areas', 'custom_forms_admin_areas');

?>
