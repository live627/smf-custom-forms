<?php

declare(strict_types=1);

/**
 * @package   Ultimate Menu mod
 * @version   4.0.2
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
