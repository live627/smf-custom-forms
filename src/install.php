<?php

declare(strict_types=1);

/**
 * @package   Ultimate Menu mod
 * @version   4.0.1
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2014, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

// If SSI.php is in the same place as this file, and SMF isn't defined...
if (file_exists(__DIR__ . '/SSI.php') && !defined('SMF'))
	require_once __DIR__ . '/SSI.php';

// Hmm... no SSI.php and no SMF?
elseif (!defined('SMF'))
	die('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');

$columns = [
	[
		'name' => 'id_form',
		'type' => 'smallint',
		'size' => '5',
		'auto' => true,
	],
	[
		'name' => 'id_board',
		'type' => 'smallint',
		'size' => '5',
	],
	[
		'name' => 'title',
		'type' => 'varchar',
		'size' => 150,
		'null' => true,
	],
	[
		'name' => 'subject',
		'type' => 'varchar',
		'size' => 150,
		'null' => true,
	],
	[
		'name' => 'icon',
		'type' => 'varchar',
		'size' => 150,
		'null' => true,
	],
	[
		'name' => 'form_exit',
		'type' => 'varchar',
		'size' => 150,
		'null' => true,
	],
	[
		'name' => 'template_function',
		'type' => 'varchar',
		'size' => 150,
		'null' => true,
	],
	[
		'name' => 'output',
		'type' => 'text',
		'null' => true,
	],
];
$indexes = [
	[
		'type' => 'primary',
		'columns' => ['id_form'],
	],
];
$smcFunc['db_create_table']('{db_prefix}cf_forms', $columns, $indexes, [], 'update_remove');

$columns = [
	[
		'name' => 'id_field',
		'type' => 'smallint',
		'size' => '5',
		'auto' => true,
	],
	[
		'name' => 'id_form',
		'type' => 'smallint',
		'size' => '5',
	],
	[
		'name' => 'title',
		'type' => 'varchar',
		'size' => 150,
		'null' => true,
	],
	[
		'name' => 'text',
		'type' => 'varchar',
		'size' => 4096,
		'null' => true,
	],
	[
		'name' => 'type',
		'type' => 'varchar',
		'size' => 150,
		'null' => true,
	],
	[
		'name' => 'type_vars',
		'type' => 'text',
		'null' => true,
	],
];
$indexes = [
	[
		'type' => 'primary',
		'columns' => ['id_field'],
	],
	[
		'type' => 'index',
		'columns' => ['id_form'],
	],
];
$smcFunc['db_create_table']('{db_prefix}cf_fields', $columns, $indexes, [], 'update_remove');
