<?php

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
		'type' => 'tinytext',
	),
	array(
		'name' => 'subject',
		'type' => 'tinytext',
	),
	array(
		'name' => 'template_function',
		'type' => 'tinytext',
	),
	array(
		'name' => 'output',
		'type' => 'text',
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
$smcFunc['db_create_table']('cf_forms', $columns, $indexes, array(), 'update_remove');

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
		'type' => 'tinytext',
	),
	array(
		'name' => 'text',
		'type' => 'tinytext',
	),
	array(
		'name' => 'type',
		'type' => 'tinytext',
	),
	array(
		'name' => 'type_vars',
		'type' => 'text',
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

$smcFunc['db_create_table']('cf_fields', $columns, $indexes, array(), 'update_remove');

//	Delete any field that has the ID 0, just for version compatibility reasons.
$smcFunc['db_query']('', '
	DELETE 
	FROM {db_prefix}cf_fields 
	WHERE id_field = \'0\''
);
?>