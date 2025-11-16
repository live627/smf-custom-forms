<?php

/**
 * @package   Custom Form mod
 * @version   4.1.0
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2014, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

declare(strict_types=1);

namespace CustomForm;

class Integration
{
	public static function autoload(&$classMap): void
	{
		$classMap['CustomForm\\'] = 'CustomForm/';
	}

	public static function actions(array &$action_array): void
	{
		$action_array['form'] = [false, 'CustomForm\CustomForm::create'];
	}

	public static function admin_areas(array &$admin_areas): void
	{
		global $txt;

		loadLanguage('CustomForm');
		$admin_areas['config']['areas']['modsettings']['subsections']['customform'] = [$txt['customform_tabheader']];
	}

	public static function modify_modifications(array &$sub_actions): void
	{
		$sub_actions['customform'] = 'CustomForm\ManageCustomForm::create';
	}

	public static function who_allowed(array &$allowedActions): void
	{
		$allowedActions['form'] = ['customform_view_perms'];
	}

	public static function whos_online_after(/*string|array*/ &$urls, array &$data): void
	{
		global $scripturl, $smcFunc, $txt;

		loadLanguage('CustomForm');
		$requested_data = [];
		$requested_ids = [];

		// Fix the anomaly where $urls is a string when
		// coming from the profile section.
		foreach (!is_array($urls) ? [[$urls, 0]] : $urls as $k => $url)
		{
			// Get the request parameters..
			$actions = $smcFunc['json_decode']($url[0], true);

			if ($actions === [])
				continue;

			if (isset($actions['n'], $actions['action']) && $actions['action'] == 'form')
			{
				$requested_ids[] = (int) $actions['n'];
				$requested_data[$k] = (int) $actions['n'];
			}
		}

		if ($requested_ids === [])
			return;

		$requested_ids = array_unique($requested_ids);

		$request = $smcFunc['db_query']('', '
			SELECT f.id_form, f.title
			FROM {db_prefix}cf_forms AS f
			WHERE f.id_form IN ({array_int:ids})
				AND f.title != \'\'
				AND EXISTS (
					SELECT 1
					FROM {db_prefix}cf_fields AS d
					WHERE d.id_form = f.id_form
						AND d.title != \'\'
						AND d.text  != \'\'
						AND d.type  != \'\'
				)',
			[
				'ids' => $requested_ids,
			]
		);

		$forms = [];

		while ([$id_form, $title] = $smcFunc['db_fetch_row']($request))
		{
			if (allowedTo('custom_forms_' . $id_form))
				$forms[$id_form] = $title;
		}

		$smcFunc['db_free_result']($request);

		foreach ($requested_data as $k => $form_id)
		{
			if (isset($forms[$form_id]))
			{
				$data[$k] = sprintf(
					$txt['customform_who'],
					$scripturl,
					$form_id,
					$forms[$form_id]
				);
			}
		}
	}

	public static function helpadmin(): void
	{
		loadLanguage('CustomForm');
	}

	public static function sce_options(&$sce_options)
	{
		$sce_options['plugins'] = ($sce_options['plugins'] != '' ? $sce_options['plugins'] . ',' : '') . 'customform';
		$sce_options['toolbar'] .= '|customformFields';
		$sce_options['toolbarExclude'] = (!empty($sce_options['toolbarExclude']) ? $sce_options['toolbarExclude'] . ',' : '') . 'table,sup,sub,floatright,floatleft,hr,email,img,size,pre,code,youtube';
	}

	public static function sce_options2(&$sce_options)
	{
		$sce_options['plugins'] = ($sce_options['plugins'] != '' ? $sce_options['plugins'] . ',' : '') . 'customform2';
		$sce_options['customformel'] = 'field_text';
		$sce_options['toolbar'] = 'bold,italic,underline|font,size,color,removeformat|maximize,source';
	}
}
