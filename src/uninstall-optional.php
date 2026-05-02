<?php

declare(strict_types=1);

/**
 * @package   Custom Form mod
 * @version   4.1.1
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2014, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

use SMF\Db\DatabaseApi as Db;

Db::$db->query('
	DELETE FROM {db_prefix}permissions
	WHERE permission
	LIKE \'custom_forms_%\'');
