<?php

/**
 * @package CustomForms
 * @since 1.0
 */
$required_php_version = '5.5.0';
if (version_compare(PHP_VERSION, $required_php_version, '<')) {
	die('Custom Forms requires a minimum of PHP ' . $required_php_version . ' in order to function. (You are currently running PHP: ' . PHP_VERSION . ')');
}