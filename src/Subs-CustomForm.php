<?php

/**
 * @package   Custom Form mod
 * @version   3.0.0
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

function customform_who_allowed(array &$allowedActions)
{
	$allowedActions['form'] = array('customform_view_perms');
}

function customform_whos_online_after(/* string|array */ &$urls, array &$data)
{
	global $scripturl, $smcFunc, $txt;

	$forms = array();
	$request = $smcFunc['db_query']('', '
		SELECT id_form, title
		FROM {db_prefix}cf_forms AS f
		WHERE title != \'\'
			AND EXISTS
			(
				SELECT * FROM {db_prefix}cf_fields AS d
				WHERE d.id_form = f.id_form
					AND title != \'\'
					AND text != \'\'
					AND type != \'\'
			)');
	while (list ($id_form, $title) = $smcFunc['db_fetch_row']($request))
		if (allowedTo('custom_forms_' . $id_form))
			$forms[$id_form] = $title;
	$smcFunc['db_free_result']($request);

	loadLanguage('CustomForm');

	// Fix the anomaly where $urls is a string when
	// coming from the profile section.
	if (!is_array($urls))
		$url_list = array(array($urls, 0));
	else
		$url_list = $urls;
	foreach ($url_list as $k => $url)
	{
		// Get the request parameters..
		$actions = $smcFunc['json_decode']($url[0], true);
		if ($actions === array())
			continue;

		if (isset($actions['n'], $actions['action'], $forms[$actions['n']]) && $actions['action'] == 'form')
			$data[$k] = sprintf(
				$txt['customform_who'],
				$scripturl,
				$actions['n'],
				$forms[$actions['n']],
			);
	}
}

function customform_helpadmin()
{
	loadLanguage('CustomForm');
}

function customform_list_classes()
{
	foreach (get_declared_classes() as $class)
		if (is_subclass_of($class, 'CustomFormBase'))
			yield trim(strpbrk($class, '_'), '_');
}

function customform_replace_vars(string $text, array $array): string
{
	return preg_replace_callback(
		'~{{1,2}\s*?([a-zA-Z0-9\-_\.]+)\s*?}{1,2}~',
		fn($matches) => $array[$matches[1]] ?? $matches[1],
		$text
	);
}
