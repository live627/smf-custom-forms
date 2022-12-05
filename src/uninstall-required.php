<?php

declare(strict_types=1);

/**
 * @package   Custom Form mod
 * @version   3.1.0
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2014, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

$smcFunc['db_query']('', '
	DELETE
	FROM {db_prefix}admin_info_files
	WHERE filename = \'customform/versions.json\''
);
