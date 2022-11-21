<?php

declare(strict_types=1);

/**
 * @package   Ultimate Menu mod
 * @version   3.1.0
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
 
$columns = array(
	array(
		'name' => 'id_form',
		'type' => 'smallint',
		'size' => '5',
		'auto' => true,
	),
	array(
		'name' => 'id_board',
		'type' => 'smallint',
		'size' => '5',
	),
	array(
		'name' => 'title',
		'type' => 'varchar',
		'size' => 150,
		'null' => true,
	),
	array(
		'name' => 'subject',
		'type' => 'varchar',
		'size' => 150,
		'null' => true,
	),
	array(
		'name' => 'icon',
		'type' => 'varchar',
		'size' => 150,
		'null' => true,
	),
	array(
		'name' => 'form_exit',
		'type' => 'varchar',
		'size' => 150,
		'null' => true,
	),
	array(
		'name' => 'template_function',
		'type' => 'varchar',
		'size' => 150,
		'null' => true,
	),
	array(
		'name' => 'output',
		'type' => 'text',
		'null' => true,
	),
);
$indexes = array(
	array(
		'type' => 'primary',
		'columns' => array('id_form'),
	),
);
$smcFunc['db_create_table']('{db_prefix}cf_forms', $columns, $indexes, array(), 'update_remove');

$columns = array(
	array(
		'name' => 'id_field',
		'type' => 'smallint',
		'size' => '5',
		'auto' => true,
	),
	array(
		'name' => 'id_form',
		'type' => 'smallint',
		'size' => '5',
	),
	array(
		'name' => 'title',
		'type' => 'varchar',
		'size' => 150,
		'null' => true,
	),
	array(
		'name' => 'text',
		'type' => 'varchar',
		'size' => 4096,
		'null' => true,
	),
	array(
		'name' => 'type',
		'type' => 'varchar',
		'size' => 150,
		'null' => true,
	),
	array(
		'name' => 'type_vars',
		'type' => 'text',
		'null' => true,
	),
);
$indexes = array(
	array(
		'type' => 'primary',
		'columns' => array('id_field'),
	),
	array(
		'type' => 'index',
		'columns' => array('id_form'),
	),
);
$smcFunc['db_create_table']('{db_prefix}cf_fields', $columns, $indexes, array(), 'update_remove');
