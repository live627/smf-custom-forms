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

use SMF\{Config, Forum, Lang, QueryString, Slug, User};

class Integration
{
	public static function autoload(&$classMap): void
	{
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
		Lang::load('CustomForm');
		$admin_areas['config']['areas']['modsettings']['subsections']['customform'] = [Lang::$txt['customform_tabheader']];
	}

	public static function modify_modifications(array &$sub_actions): void
	{
		$sub_actions['customform'] = 'CustomForm\ManageCustomForm::create';
	}

	public static function who_allowed(array &$allowedActions): void
	{
		$allowedActions['form'] = ['customform_view_perms'];
	}

	public static function whos_online_after(string|array &$urls, array &$data): void
	{
		$forms = [];
		$entries = Form::fetchMany(null, false);

		foreach ($entries as $form) {
			if (User::$me->allowedTo('custom_forms_' . $form->id)) {
				$forms[$form->id] = $form->title;
			}

			// Create the slug for this form.  Do this here to prevent
			// extra queries when building the route.
			Slug::create($form->title, 'form', $form->id);
		}

		Lang::load('CustomForm');

		// Fix the anomaly where $urls is a string when
		// coming from the profile section.
		foreach (!is_array($urls) ? [[$urls, 0]] : $urls as $k => $url) {
			// Get the request parameters..
			$actions = json_decode($url[0], true);

			// Error...
			if ($actions === null) {
				continue;
			}

			if (isset($actions['n'], $actions['action'], $forms[$actions['n']]) && $actions['action'] == 'form') {
				$data[$k] = sprintf(
					Lang::$txt['customform_who'],
					Config::$scripturl,
					$actions['n'],
					$forms[$actions['n']],
				);
			}
		}
	}

	public static function helpadmin(): void
	{
		Lang::load('CustomForm');
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