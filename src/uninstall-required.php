<?php

/**
 * @package   Custom Form mod
 * @version   2.1.0
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2014, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

// If SSI.php is in the same place as this file, and SMF isn't defined...
if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
	require_once(dirname(__FILE__) . '/SSI.php');

// Hmm... no SSI.php and no SMF?
elseif (!defined('SMF'))
	die('<b>Error:</b> Cannot uninstall - please verify you put this in the same place as SMF\'s index.php.');

remove_integration_function('integrate_pre_include', '$sourcedir/Subs-CustomForm.php');
remove_integration_function('integrate_admin_include', '$sourcedir/ManageCustomForm.php');
remove_integration_function('integrate_actions', 'customform_actions');
remove_integration_function('integrate_menu_buttons', 'customform_menu_buttons');
remove_integration_function('integrate_modify_modifications', 'customform_modify_modifications');
remove_integration_function('integrate_admin_areas', 'customform_admin_areas');

?>
