<?php

/**
 * @package   Custom Form mod
 * @version   2.0.1
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

function customform_replace_vars(string $text, array $array): string
{
	return preg_replace_callback('~{{1,2}\s*?([a-zA-Z0-9\-_\.]+)\s*?}{1,2}~',
		function($matches) use ($array)
		{
			return $array[$matches[1]] ?? $matches[1];
		},
		$text);
}