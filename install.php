<?php

if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
{
	$ssi = true;
	require_once(dirname(__FILE__) . '/SSI.php');
}
elseif (!defined('SMF'))
	exit('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');

add_integration_function('integrate_pre_include', '$sourcedir/Subs-CustomForms.php');
add_integration_function('integrate_load_theme', 'custom_forms_load_theme');
add_integration_function('integrate_actions', 'custom_forms_actions');
add_integration_function('integrate_menu_buttons', 'custom_forms_buttons');
add_integration_function('integrate_modify_modifications', 'custom_forms_modify_modifications');
add_integration_function('integrate_admin_areas', 'custom_forms_admin_areas');

if (!array_key_exists('db_add_column', $smcFunc))
	db_extend('packages');

$columns = array(
	array(
		'name' => 'id_form',
		'type' => 'mediumint',
		'size' => 8,
		'unsigned' => true,
		'auto' => true,
	),
	array(
		'name' => 'name',
		'type' => 'varchar',
		'size' => 80,
	),
	array(
		'name' => 'description',
		'type' => 'varchar',
		'size' => 4096,
	),
	array(
		'name' => 'groups',
		'type' => 'varchar',
		'size' => 80,
	),
	array(
		'name' => 'bbc',
		'type' => 'enum(\'no\',\'yes\')',
	),
	array(
		'name' => 'can_search',
		'type' => 'enum(\'no\',\'yes\')',
	),
	array(
		'name' => 'active',
		'type' => 'enum(\'yes\',\'no\')',
	),
);

$indexes = array(
	array(
		'type' => 'primary',
		'columns' => array('id_form')
	),
);

$smcFunc['db_create_table']('{db_prefix}custom_forms', $columns, $indexes, array(), 'update_remove');

$columns = array(
	array(
		'name' => 'id_field',
		'type' => 'mediumint',
		'size' => 8,
		'unsigned' => true,
		'auto' => true,
	),
	array(
		'name' => 'name',
		'type' => 'varchar',
		'size' => 80,
	),
	array(
		'name' => 'type',
		'type' => 'varchar',
		'size' => 20,
	),
	array(
		'name' => 'description',
		'type' => 'varchar',
		'size' => 4096,
	),
	array(
		'name' => 'enclose',
		'type' => 'varchar',
		'size' => 4096,
	),
	array(
		'name' => 'options',
		'type' => 'varchar',
		'size' => 4096,
	),
	array(
		'name' => 'size',
		'type' => 'smallint',
		'size' => 5,
		'unsigned' => true,
	),
	array(
		'name' => 'default_value',
		'type' => 'varchar',
		'size' => 80,
	),
	array(
		'name' => 'mask',
		'type' => 'varchar',
		'size' => 20,
	),
	array(
		'name' => 'regex',
		'type' => 'varchar',
		'size' => 80,
	),
	array(
		'name' => 'forms',
		'type' => 'varchar',
		'size' => 80,
	),
	array(
		'name' => 'groups',
		'type' => 'varchar',
		'size' => 80,
	),
	array(
		'name' => 'bbc',
		'type' => 'enum(\'no\',\'yes\')',
	),
	array(
		'name' => 'can_search',
		'type' => 'enum(\'no\',\'yes\')',
	),
	array(
		'name' => 'active',
		'type' => 'enum(\'yes\',\'no\')',
	),
	array(
		'name' => 'required',
		'type' => 'enum(\'yes\',\'no\')',
	),
);

$indexes = array(
	array(
		'type' => 'primary',
		'columns' => array('id_field')
	),
);

$smcFunc['db_create_table']('{db_prefix}custom_form_fields', $columns, $indexes, array(), 'update_remove');

$columns = array(
	array(
		'name' => 'id_field',
		'type' => 'mediumint',
		'size' => 8,
		'unsigned' => true,
	),
	array(
		'name' => 'id_form',
		'type' => 'mediumint',
		'size' => 8,
		'unsigned' => true,
	),
);

$indexes = array(
	array(
		'type' => 'primary',
		'columns' => array('id_field', 'id_form')
	),
);

$smcFunc['db_create_table']('{db_prefix}custom_form_field_link', $columns, $indexes, array(), 'update_remove');

$columns = array(
	array(
		'name' => 'id_field',
		'type' => 'mediumint',
		'size' => 8,
		'unsigned' => true,
	),
	array(
		'name' => 'id_form',
		'type' => 'mediumint',
		'size' => 8,
		'unsigned' => true,
	),
	array(
		'name' => 'id_member',
		'type' => 'mediumint',
		'size' => 8,
		'unsigned' => true,
	),
	array(
		'name' => 'value',
		'type' => 'varchar',
		'size' => 4096,
	),
);

$indexes = array(
	array(
		'type' => 'primary',
		'columns' => array('id_field', 'id_form')
	),
);

$smcFunc['db_create_table']('{db_prefix}custom_form_field_data', $columns, $indexes, array(), 'update_remove');

if (!empty($ssi))
	echo 'Database installation complete!';

?>