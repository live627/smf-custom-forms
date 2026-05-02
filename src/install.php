<?php

declare(strict_types=1);

/**
 * @package   Custom Form mod
 * @version   4.1.1
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2014, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

use SMF\Db\Schema\Table;
use SMF\Db\Schema\Column;
use SMF\Db\Schema\DbIndex;

// If SSI.php is in the same place as this file, and SMF isn't defined...
if (file_exists(__DIR__ . '/SSI.php') && !defined('SMF')) {
	require_once __DIR__ . '/SSI.php';
}

// Hmm... no SSI.php and no SMF?
elseif (!defined('SMF')) {
	die('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');
}

// Create the cf_forms table
class CfForms extends Table
{
	public function __construct()
	{
		$this->name = 'cf_forms';

		$this->columns = [
			'id_form' => new Column(
				name: 'id_form',
				type: 'smallint',
				size: 5,
				auto: true,
			),
			'id_board' => new Column(
				name: 'id_board',
				type: 'smallint',
				size: 5,
			),
			'title' => new Column(
				name: 'title',
				type: 'varchar',
				size: 150,
			),
			'subject' => new Column(
				name: 'subject',
				type: 'varchar',
				size: 150,
			),
			'icon' => new Column(
				name: 'icon',
				type: 'varchar',
				size: 150,
			),
			'form_exit' => new Column(
				name: 'form_exit',
				type: 'varchar',
				size: 150,
			),
			'template_function' => new Column(
				name: 'template_function',
				type: 'varchar',
				size: 150,
			),
			'output' => new Column(
				name: 'output',
				type: 'text',
			),
			'output' => new Column(
				name: 'output_type',
				type: 'varchar',
				size: 150,
				default: 'ForumPost',
			),
		];

		$this->indexes = [
			'primary' => new DbIndex(
				type: 'primary',
				columns: [['name' => 'id_form']],
			),
		];

		parent::__construct();
	}
}

$cf_forms_table = new CfForms();
$cf_forms_table->create([], 'update_remove');

// Create the cf_fields table
class CfFields extends Table
{
	public function __construct()
	{
		$this->name = 'cf_fields';

		$this->columns = [
			'id_field' => new Column(
				name: 'id_field',
				type: 'smallint',
				size: 5,
				auto: true,
			),
			'id_form' => new Column(
				name: 'id_form',
				type: 'smallint',
				size: 5,
			),
			'title' => new Column(
				name: 'title',
				type: 'varchar',
				size: 150,
			),
			'text' => new Column(
				name: 'text',
				type: 'varchar',
				size: 4096,
			),
			'type' => new Column(
				name: 'type',
				type: 'varchar',
				size: 150,
			),
			'type_vars' => new Column(
				name: 'type_vars',
				type: 'text',
			),
		];

		$this->indexes = [
			'primary' => new DbIndex(
				type: 'primary',
				columns: [['name' => 'id_field']],
			),
			'idx_id_form' => new DbIndex(
				name: 'idx_id_form',
				columns: [['name' => 'id_form']],
			),
		];

		parent::__construct();
	}
}

$cf_fields_table = new CfFields();
$cf_fields_table->create([], 'update_remove');
