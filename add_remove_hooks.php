<?php

/**
 * @package CustomForms
 * @since 1.0
 */
if (file_exists(__DIR__ . '/SSI.php') && !defined('SMF')) {
	$ssi = true;
	require_once(__DIR__ . '/SSI.php');
} elseif (!defined('SMF')) {
	exit('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');
}

if (!class_exists('ModHelper\Psr4AutoloaderClass')) {
	require_once(__DIR__ . '/CustomForms/ModHelper/Psr4AutoloaderClass.php');
}
// instantiate the loader
$loader = new \ModHelper\Psr4AutoloaderClass;
// register the autoloader
$loader->register();
// register the base directories for the namespace prefix
$loader->addNamespace('ModHelper', __DIR__ . '/CustomForms/ModHelper');
$loader->addNamespace('CustomForms', __DIR__ . '/CustomForms');

(new \ModHelper\Hooks)->add('integrate_pre_include', '$sourcedir/CustomForms/Services/Integration.php')
	->add('integrate_load_theme', '\\CustomForms\\Services\\Integration::load_theme')
	->add('integrate_actions', '\\CustomForms\\Services\\Integration::actions')
	->add('integrate_menu_buttons', '\\CustomForms\\Services\\Integration::menu_buttons')
	->add('integrate_admin_areas', '\\CustomForms\\Services\\Integration::admin_areas')
	->execute(empty($context['uninstalling']));

if (!empty($ssi)) {
	echo 'Database installation complete!';
}
