<?php

/**
 * @package   Custom Form mod
 * @version   2.1.1
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

$smcFunc['db_query'](
	'',
	'
	DELETE FROM {db_prefix}permissions
	WHERE permission
	LIKE {string:fuzzy_permissions}',
	array(
		'fuzzy_permissions' => 'custom_forms_%',
	)
);

?>