<?php

add_integration_function('integrate_pre_include', '$sourcedir/Subs-CustomForm.php');
add_integration_function('integrate_admin_include', '$sourcedir/ManageCustomForm.php');
add_integration_function('integrate_actions', 'customform_actions');
add_integration_function('integrate_modify_modifications', 'customform_modify_modifications');
add_integration_function('integrate_admin_areas', 'customform_admin_areas');
add_integration_function('integrate_load_theme', 'customform_load_theme');

//	Set up the correct columns for the table.
$columns = array(
	array(
		'name' => 'id_form',
		'type' => 'smallint',
		'size' => '5',
		'auto' => 1,
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

//	Set up the correct indexes for the table.
$indexes = array(
	array(
		'type' => 'primary',
		'columns' => array('id_form'),
	),
);

//	Perform the table creation.
$smcFunc['db_create_table']('{db_prefix}cf_forms', $columns, $indexes, array(), 'update_remove');

foreach ($columns as $column)
	if (stripos($column['type'], 'char') !== false)
		$smcFunc['db_change_column']('{db_prefix}cf_forms', $column['name'], $column + ['default' => '']);

//	Set up the correct columns for the table.
$columns = array(
	array(
		'name' => 'id_field',
		'type' => 'smallint',
		'size' => '5',
		'auto' => 1,
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

//	Set up the correct indexes for the table.
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

foreach ($columns as $column)
	if (stripos($column['type'], 'char') !== false)
		$smcFunc['db_change_column']('{db_prefix}cf_fields', $column['name'], $column + ['default' => '']);

//	Delete any field that has the ID 0, just for version compatibility reasons.
$smcFunc['db_query'](
	'',
	'
	DELETE 
	FROM {db_prefix}cf_fields 
	WHERE id_field = \'0\''
);
