<?php

declare(strict_types=1);

/**
 * @package   Ultimate Menu mod
 * @version   3.1.0
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2014, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */


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
