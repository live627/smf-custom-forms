<?php

/**
 * @package   Custom Form mod
 * @version   1.0.3
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2014, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

declare(strict_types=1);

function customform_actions(array &$action_array)
{
	$action_array['form'] = array('CustomForm.php', 'CustomForm');
}

function customform_admin_areas(array &$admin_areas)
{
	global $txt;

	loadLanguage('CustomForm');
	$admin_areas['config']['areas']['modsettings']['subsections']['customform'] = array($txt['customform_tabheader']);
}

function customform_modify_modifications(array &$sub_actions)
{
	$sub_actions['customform'] = 'ModifyCustomFormSettings';
}

function customform_list_classes()
{
	foreach (get_declared_classes() as $class)
		if (is_subclass_of($class, 'CustomFormBase'))
			yield trim(strpbrk($class, '_'), '_');
}