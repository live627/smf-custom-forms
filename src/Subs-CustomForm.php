<?php

/**
 * @package   Custom Form mod
 * @version   2.2.2
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

function customform_load_theme()
{
	global $context, $scripturl, $smcFunc, $txt;

	if ($context['current_action'] == 'who')
	{
		$request = $smcFunc['db_query']('', 'SELECT id_form, title FROM {db_prefix}cf_forms');
		while ([$id_form, $title] = $smcFunc['db_fetch_row']($request))
			if (allowedTo('custom_forms_' . $form_id))
				$txt['customform_whoallow_' . $id_form] = sprintf(
					$txt['customform_who'],
					$scripturl,
					$id_form,
					$title,
				);
		$smcFunc['db_free_result']($request);
	}

	if ($context['current_action'] == 'helpadmin')
		loadLanguage('CustomForm');
}

function customform_whos_online(array $actions)
{
	global $txt;

	if (isset($txt['customform_whoallow_' . $actions['n']]))
		return $txt['customform_whoallow_' . $actions['n']];

	return $txt['who_hidden'];
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
