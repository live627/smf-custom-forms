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

use SMF\Forum;
use SMF\QueryString;
use SMF\Slug;

class Integration
{
	public static function autoload(&$classMap): void
	{
		//~ \SMF\IntegrationHook::add('integrate_pre_load',self::class.'::pre_load',);
		$classMap['CustomForm\\'] = 'CustomForm/';
	}

	public static function pre_load(): void
	{
		Forum::addAction('form', '', CustomForm::class);
		QueryString::$route_parsers['form'] = CustomForm::class;
		Slug::$redirect_patterns['form'] = [
			'action' => 'form',
			'n' => '{id}',
		];
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

		$forms = [];
		$request = $smcFunc['db_query'](
			'',
			'
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
				)',
		);

		while ([$id_form, $title] = $smcFunc['db_fetch_row']($request)) {
			if (allowedTo('custom_forms_' . $id_form)) {
				$forms[$id_form] = $title;
			}
		}
		$smcFunc['db_free_result']($request);

		loadLanguage('CustomForm');

		// Fix the anomaly where $urls is a string when
		// coming from the profile section.
		foreach (!is_array($urls) ? [[$urls, 0]] : $urls as $k => $url) {
			// Get the request parameters..
			$actions = $smcFunc['json_decode']($url[0], true);

			if ($actions === []) {
				continue;
			}

			if (isset($actions['n'], $actions['action'], $forms[$actions['n']]) && $actions['action'] == 'form') {
				$data[$k] = sprintf(
					$txt['customform_who'],
					$scripturl,
					$actions['n'],
					$forms[$actions['n']],
				);
			}
		}
	}

	public static function helpadmin(): void
	{
		loadLanguage('CustomForm');
	}

	public static function sce_options(&$sce_options): void
	{
		$sce_options['plugins'] = ($sce_options['plugins'] != '' ? $sce_options['plugins'] . ',' : '') . 'customform';
		$sce_options['toolbar'] .= '|customformFields';
		$sce_options['toolbarExclude'] = (!empty($sce_options['toolbarExclude']) ? $sce_options['toolbarExclude'] . ',' : '') . 'table,sup,sub,floatright,floatleft,hr,email,img,size,pre,code,youtube';
	}

	public static function sce_options2(&$sce_options): void
	{
		$sce_options['plugins'] = ($sce_options['plugins'] != '' ? $sce_options['plugins'] . ',' : '') . 'customform2';
		$sce_options['customformel'] = 'field_text';
		$sce_options['toolbar'] = 'bold,italic,underline|font,size,color,removeformat|maximize,source';
	}
}
