<?php

declare(strict_types=1);

/**
 * @package   Ultimate Menu mod
 * @version   4.0.4
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2014, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

if (!array_key_exists('db_add_column', $smcFunc))
	db_extend('packages');

$column = array(
	'name' => 'output_type',
	'type' => 'varchar',
	'size' => 150,
	'default' => 'ForumPost'
);

$smcFunc['db_add_column']('{db_prefix}cf_forms', $column);

$request = $smcFunc['db_query']('', '
	SELECT id_file
	FROM {db_prefix}admin_info_files
	WHERE filename = \'customform/versions.json\''
);

if ($smcFunc['db_num_rows']($request) == 0)
	$smcFunc['db_insert']('',
		'{db_prefix}admin_info_files',
		array('filename' => 'string', 'path' => 'string', 'parameters' => 'string', 'data' => 'string', 'filetype' => 'string'),
		array('customform/versions.json', 'https://mods.live627.com//api/', '', '', 'application/json'),
		array('id_file')
	);

$smcFunc['db_free_result']($request);

$smcFunc['db_query']('', '
	UPDATE {db_prefix}cf_forms
	SET icon = CASE WHEN icon IS NULL THEN \'\' ELSE icon END,
	title = CASE WHEN title IS NULL THEN \'\' ELSE title END, output = CASE WHEN output IS NULL THEN \'\' ELSE output END,
	subject = CASE WHEN subject IS NULL THEN \'\' ELSE subject END,
	form_exit = CASE WHEN form_exit IS NULL THEN \'\' ELSE form_exit END,
	template_function = CASE WHEN template_function IS NULL THEN \'\' ELSE template_function END,
	output_type = CASE WHEN output_type IS NULL THEN \'\' ELSE output_type END');

$smcFunc['db_query']('', '
	UPDATE {db_prefix}cf_fields
	SET title = CASE WHEN title IS NULL THEN \'\' ELSE title END, text = CASE WHEN text IS NULL THEN \'\' ELSE text END,
	type = CASE WHEN type IS NULL THEN \'\' ELSE type END, type_vars = CASE WHEN type_vars IS NULL THEN \'\' ELSE type_vars END');
