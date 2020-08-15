<?php
// Data Base Functions for the Custom Form Mod version 1.7
//	File to create the Custom Form tables.

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
		'default' => '',
	),
	array(
		'name' => 'subject',
		'type' => 'varchar',
		'size' => 150,
		'default' => '',
	),
	array(
		'name' => 'icon',
		'type' => 'varchar',
		'size' => 150,
		'default' => '',
	),
	array(
		'name' => 'form_exit',
		'type' => 'varchar',
		'size' => 150,
		'default' => '',
	),
	array(
		'name' => 'template_function',
		'type' => 'varchar',
		'size' => 150,
		'default' => '',
	),
	array(
		'name' => 'output',
		'type' => 'text',
		'default' => '',
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
	if (stripos($column['type'], 'char') !== false || stripos($column['type'], 'text') !== false)
		$smcFunc['db_change_column']('{db_prefix}cf_forms', $column['name'], $column);

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
		'default' => '',
	),
	array(
		'name' => 'text',
		'type' => 'varchar',
		'size' => 150,
		'default' => '',
	),
	array(
		'name' => 'type',
		'type' => 'varchar',
		'size' => 150,
		'default' => '',
	),
	array(
		'name' => 'type_vars',
		'type' => 'text',
		'default' => '',
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
	if (stripos($column['type'], 'char') !== false || stripos($column['type'], 'text') !== false)
		$smcFunc['db_change_column']('{db_prefix}cf_fields', $column['name'], $column);

//	Delete any field that has the ID 0, just for version compatibility reasons.
$smcFunc['db_query']('', '
	DELETE 
	FROM {db_prefix}cf_fields 
	WHERE id_field = \'0\''
);
?>