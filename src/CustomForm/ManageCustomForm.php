<?php

declare(strict_types=1);

/**
 * @package   Custom Form mod
 * @version   3.1.0
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2014, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace CustomForm;

use FilesystemIterator;
use GlobIterator;

class ManageCustomForm
{
	const ADDFORM = 'addform';
	const DELETEFORM = 'deleteform';
	const EDITFORM = 'editform';
	const SAVEFORM = 'saveform';
	const ADDFIELD = 'addfield';
	const EDITFIELD = 'editfield';
	const DELETEFIELD = 'deletefield';
	const MOVEFIELDDOWN = 'movefielddown';
	const MOVEFIELDUP = 'movefieldup';
	const SAVEFIELD = 'savefield';
	const VERSION = '3.0.0';

	private string $scripturl;
	private string $sourcedir;
	private array $smcFunc;
	private Util $util;

	public function __construct(string $act)
	{
		global $scripturl, $sourcedir, $smcFunc;

		$this->scripturl = $scripturl;
		$this->sourcedir = $sourcedir;
		$this->smcFunc = $smcFunc;

		isAllowedTo('admin_forum');

		$form_id = (int) ($_GET['form_id'] ?? 0);
		$field_id = (int) ($_GET['field_id'] ?? 0);

		$call = match ($act)
		{
			'delay' => ['Delay', null],
			self::ADDFORM => ['AddForm', null],
			self::DELETEFORM => ['DeleteForm', $form_id],
			self::EDITFORM => ['EditForm', $form_id],
			self::SAVEFORM => ['SaveForm', $form_id],
			self::ADDFIELD => ['AddField', $form_id],
			self::EDITFIELD => ['EditField', $field_id],
			self::DELETEFIELD => ['DeleteField', $field_id],
			self::MOVEFIELDDOWN => ['MoveFieldDown', $field_id],
			self::MOVEFIELDUP => ['MoveFieldUp', $field_id],
			self::SAVEFIELD => ['SaveField', $field_id],
			default => ['ModifySettings', false],
		};
		$this->util = new Util;
		call_user_func([$this, $call[0]], $call[1]);
	}

	/**
	 * Static constructor / factory
	 */
	public static function create(): ManageCustomForm
	{
		return new self($_GET['act'] ?? '');
	}

	public static function getConfigVars(): array
	{
		global $txt;

		return [
			['permissions', 'customform_view_perms', 'subtext' => $txt['customform_view_perms_desc']],
			['text', 'customform_view_title', 'subtext' => $txt['customform_view_title_desc']],
			['large_text', 'customform_view_text', 'subtext' => $txt['customform_view_text_desc']],
		];
	}

	public function ModifySettings(): void
	{
		global $context, $txt;

		$config_vars = self::getConfigVars();

		if (isset($_GET['update']))
		{
			checkSession();
			saveDBSettings($config_vars);
			redirectexit('action=admin;area=modsettings;sa=customform');
		}

		$list = [
			'id' => 'customform_list',
			'title' => $txt['customform_tabheader'],
			'no_items_label' => $txt['customform_list_noelements'],
			'get_items' => [
				'function' => [$this, 'list_CustomForms'],
			],
			'columns' => [
				'title' => [
					'header' => [
						'value' => $txt['title'],
					],
					'data' => [
						'db_htmlsafe' => 'title',
					],
				],
				'board' => [
					'header' => [
						'value' => $txt['customform_board'],
					],
					'data' => [
						'db' => 'board',
					],
				],
				'permissions' => [
					'header' => [
						'value' => $txt['edit_permissions'],
					],
					'data' => [
						'db' => 'permissions',
					],
				],
				'modify' => [
					'data' => [
						'db' => 'modify',
					],
				],
			],
			'additional_rows' => [
				[
					'position' => 'below_table_data',
					'value' => sprintf(
						'<a class="button" href="%s?action=admin;area=modsettings;sa=customform;act=addform">%s</a>',
						$this->scripturl,
						$txt['customform_add_form']
					),
					'class' => 'righttext',
				],
			],
		];

		require_once $this->sourcedir . '/Subs-List.php';
		createList($list);

		//	Set up the variables needed by the template.
		$context['settings_title'] = $txt['customform_generalsettings_heading'];
		$context['page_title'] = $txt['customform_tabheader'];
		$context['default_list'] = 'customform_list';
		$context['post_url'] = $this->scripturl . '?action=admin;area=modsettings;sa=customform;update';
		loadTemplate('CustomForm');
		loadJavaScriptFile('customform.js', array('minimize' => true));
		addInlineJavaScript('
		textareaLengthCheck(document.getElementById("customform_view_text"), 320);', true);
		$context['sub_template'] = 'customform_GeneralSettings';
		prepareDBSettingContext($config_vars);

		// Two tokens because saving these settings requires both save_inline_permissions and saveDBSettings
		createToken('admin-mp');
		createToken('admin-dbsc');

		$request = $this->smcFunc['db_query']('', '
			SELECT data
			FROM {db_prefix}admin_info_files
			WHERE filename = \'customform/versions.json\''
		);

		[$data] = $this->smcFunc['db_fetch_row']($request);
		$this->smcFunc['db_free_result']($request);

		if ($data !== null)
		{
			$data = json_decode($data, true);
			require_once $this->sourcedir . '/Subs-Package.php';
			if ($data !== null && compareVersions(self::VERSION, $data[0]['version']['string']) < 1)
			{
				$changes = '';
				foreach ($data[1]['changes'] as $type => $change)
					$changes .= '<h4>'.$type.'</h4><ul><li>&emsp;'.implode('</li><li>&emsp;', $change).'</li></ul>';

				$context['settings_insert_above'] = sprintf(
					'<div class="noticebox" style="overflow:auto; max-height: 11em;"><h3 id="update_title">%s</h3><div class="padding"><a class="button floatnone" href="%s?action=admin;area=packages;pgdownload;auto;package=%s;%s=%s">%s</a>&emsp;<a class="button floatnone" href="%2$s?action=admin;area=modsettings;sa=customform;act=delay">%s</a></div>%s</div>',
					$txt['customform_update_available'],
					$this->scripturl,
					sprintf(
						'https://github.com/live627/smf-custom-forms/releases/download/v%s/custom-forms_%d-%d-%d.tgz',
						$data[0]['version']['string'],
						$data[0]['version']['major'],
						$data[0]['version']['minor'],
						$data[0]['version']['patch'],
					),
					$context['session_var'],
					$context['session_id'],
					sprintf($txt['customform_update_action'], $data[0]['version']['string']),
					$txt['customform_update_later'],
					$changes,
				);
			}
		}
	}

	public function Delay(): void
	{
		$this->smcFunc['db_query']('', '
			UPDATE {db_prefix}admin_info_files
			SET data = \'\'
			WHERE filename = \'customform/versions.json\''
		);

		redirectexit('action=admin;area=modsettings;sa=customform');
	}

	public function EditForm(int $form_id): void
	{
		global $context, $settings, $txt;

		$request = $this->smcFunc['db_query']('', '
			SELECT title, id_board, icon, output, subject, form_exit, template_function, output_type
			FROM {db_prefix}cf_forms
			WHERE id_form = {int:id_form}',
			[
				'id_form' => $form_id,
			]
		);

		$data = $this->smcFunc['db_fetch_assoc']($request);
		$this->smcFunc['db_free_result']($request);

		//	No data? Well, show the default settings page then.
		if ($data == [])
			redirectexit('action=admin;area=modsettings;sa=customform');

		$context['custom_form_settings'] = [
			'permissions' => 'custom_forms_' . $form_id,
			'form_board_id' => $data['id_board'],
			'icon' => $data['icon'],
			'form_title' => $data['title'],
			'subject' => $data['subject'],
			'form_exit' => $data['form_exit'],
			'output' => $data['output'],
			'template_function' => $data['template_function'],
		];

		//	Create the list of fields.
		$list = [
			'id' => 'customform_list_fields',
			'title' => $txt['customform_listheading_fields'],
			'no_items_label' => $txt['customform_list_noelements'],
			'get_items' => [
				'function' => [$this, 'list_customform_fields'],
				'params' => [$form_id],
			],
			'columns' => [
				'title' => [
					'header' => [
						'value' => $txt['customform_identifier'],
					],
					'data' => [
						'db_htmlsafe' => 'title',
					],
				],
				'text' => [
					'header' => [
						'value' => $txt['customform_text'],
					],
					'data' => [
						'db_htmlsafe' => 'text',
					],
				],
				'type' => [
					'header' => [
						'value' => $txt['customform_type'],
					],
					'data' => [
						'db' => 'type',
					],
				],
				'modify' => [
					'data' => [
						'db' => 'modify',
					],
				],
			],
			'additional_rows' => [
				[
					'position' => 'below_table_data',
					'value' => sprintf(
						'<a class="button" href="%s?action=admin;area=modsettings;sa=customform;form_id=%d;act=addfield">%s</a>',
						$this->scripturl,
						$form_id,
						$txt['customform_add_field']
					),
					'class' => 'righttext',
				],
			],
		];

		//	Call the function to setup the list for the template.
		require_once $this->sourcedir . '/Subs-List.php';
		createList($list);

		//	Call the function to setup the inline permissions for the template.
		require_once $this->sourcedir . '/ManagePermissions.php';
		init_inline_permissions(['custom_forms_' . $form_id]);
		createToken('admin-mp');

		add_integration_function('integrate_sceditor_options', __NAMESPACE__ . '\Integration::sce_options', false);
		require_once $this->sourcedir . '/Subs-Editor.php';
		create_control_richedit(
			[
				'disable_smiley_box' => true,
				'id' => 'output',
				'value' => $data['output'] ?? '',
				'width' => '100%',
			]
		);
		loadCSSFile('customform.css', array('minimize' => true));
		loadJavaScriptFile('sceditor.plugins.customform.js', array('minimize' => true));
		loadJavaScriptFile('customform.js', array('minimize' => true));

		//	Set up the variables needed by the template.
		$context['settings_title'] = sprintf(
			'<a href="%s?action=admin;area=modsettings;sa=customform;">%s</a> -> %s" %s',
			$this->scripturl,
			$txt['customform_generalsettings_heading'],
			$data['title'],
			$txt['customform_form']
		);
		$context['post_url'] = $this->scripturl . '?action=admin;area=modsettings;sa=customform;act=saveform;form_id=' . $form_id;
		$context['page_title'] = $txt['customform_tabheader'];
		$context['default_list'] = 'customform_list_fields';
		$context['sub_template'] = 'customform_GeneralSettings';
		$context['categories'] = $this->listBoards((int) $data['id_board']);

		require_once $this->sourcedir . '/Subs-Editor.php';
		$context['icons'] = getMessageIcons($data['id_board']);
		$setting = file_exists($settings['theme_dir'] . '/images/post/' . $data['icon'] . '.png')
			? 'images_url'
			: 'default_images_url';
		$context['icon_url'] = $settings[$setting] . '/post/' . $data['icon'] . '.png';
		array_unshift(
			$context['icons'],
			[
				'value' => $data['icon'],
				'name' => $txt['current_icon'],
				'url' => $context['icon_url'],
			]
		);

		$config_vars = [
			[
				'text',
				'title',
				'value' => $data['title'],
				'text_label' => $txt['title'],
			],
			[
				'callback',
				'boards',
			],
			[
				'text',
				'template_function',
				'value' => $data['template_function'],
				'text_label' => $txt['customform_template_function'],
				'help' => 'customform_template_function',
			],
			[
				'permissions',
				'custom_forms_' . $form_id,
				'value' => 'custom_forms_' . $form_id,
				'text_label' => $txt['edit_permissions'],
			],
			[
				'text',
				'exit',
				'value' => $data['form_exit'],
				'text_label' => $txt['customform_exit'],
				'help' => 'customform_submit_exit',
			],
			[
				'select',
				'output_type',
				'value' => $data['output_type'],
				'text_label' => $txt['customform_output_type'],
				'subtext' => 'customform_output_type',
				iterator_to_array(
					$this->util->map(
						fn($cn, $key) => [$key ?? $cn, $txt[$this->util->decamelize(strtr($cn, '\\', '_'))]],
						$this->util->find_classes(
							new GlobIterator(
								__DIR__ . '/Output/*.php',
								FilesystemIterator::SKIP_DOTS
							),
							'CustomForm\Output\\',
							'CustomForm\OutputInterface'
						)
					)
				),
			],
			[
				'callback',
				'output',
			],
		];
		loadTemplate('CustomForm');
		loadTemplate('GenericControls');
		loadTemplate('GenericList');
		//	Finally prepare the settings array to be shown by the 'show_settings' template.
		prepareDBSettingContext($config_vars);

		// Two tokens because saving these settings requires both save_inline_permissions and saveDBSettings
		createToken('admin-mp');
		createToken('admin-dbsc');
	}

	public function SaveForm(int $form_id): void
	{
		$this->smcFunc['db_query']('', '
			UPDATE {db_prefix}cf_forms
			SET id_board = {int:id_board},
			icon = {string:icon},
			title = {string:title}, output = {string:output},
			subject = {string:subject},
			form_exit = {string:form_exit},
			template_function = {string:template_function},
			output_type = {string:output_type}
			WHERE id_form = {int:id_form}',
			[
				'id_form' => $form_id,
				'id_board' => $_REQUEST['board_id'],
				'icon' => $_REQUEST['icon'],
				'title' => $_REQUEST['title'],
				'output' => $_REQUEST['output'],
				'subject' => $_REQUEST['subject'],
				'form_exit' => $_REQUEST['exit'],
				'template_function' => $_REQUEST['template_function'],
				'output_type' => $_REQUEST['output_type'],
			]
		);

		//	Update the permissions.
		require_once $this->sourcedir . '/ManagePermissions.php';
		save_inline_permissions(['custom_forms_' . $form_id]);

		redirectexit('action=admin;area=modsettings;sa=customform;act=editform;form_id=' . $form_id);
	}

	public function deleteForm(int $form_id): void
	{
		$this->smcFunc['db_query']('', '
			DELETE
			FROM {db_prefix}cf_forms
			WHERE id_form = {int:id_form}',
			[
				'id_form' => $form_id,
			]
		);
		$this->smcFunc['db_query']('', '
			DELETE
			FROM {db_prefix}permissions
			WHERE permission = {string:permission}',
			[
				'permission' => 'custom_forms_' . $form_id,
			]
		);
		$this->smcFunc['db_query']('', '
			DELETE
			FROM {db_prefix}cf_fields
			WHERE id_form = {int:id_form}',
			[
				'id_form' => $form_id,
			]
		);
		redirectexit('action=admin;area=modsettings;sa=customform');
	}

	public function editField(int $field_id): void
	{
		global $context, $txt;

		$request = $this->smcFunc['db_query']('', '
			SELECT title, type, id_form, text, type_vars
			FROM {db_prefix}cf_fields
			WHERE id_field = {int:id_field}',
			[
				'id_field' => $field_id,
			]
		);

		$data = $this->smcFunc['db_fetch_assoc']($request);
		$this->smcFunc['db_free_result']($request);

		//	No data? Well, show the default settings page then.
		if ($data == [])
			redirectexit('action=admin;area=modsettings;sa=customform');

		//	Get some information about the parent form.
		$request = $this->smcFunc['db_query']('', '
			SELECT title, id_board
			FROM {db_prefix}cf_forms
			WHERE id_form = {int:id_form}',
			[
				'id_form' => $data['id_form'],
			]
		);

		$parent_data = $this->smcFunc['db_fetch_assoc']($request);
		$this->smcFunc['db_free_result']($request);

		$invalid = preg_match('/[^a-zA-Z0-9\-_.]/', $data['title']);

		if ($invalid)
			$context['settings_insert_above'] = sprintf(
				'<div class="errorbox">%s<ul><li>%s</li><li>%s</li></ul></div>',
				$txt['customform_character_warning'],
				sprintf(
					$txt['customform_current_identifier'],
					'<code>' . $this->smcFunc['htmlspecialchars']($data['title']) . '</code>'
				),
				sprintf(
					$txt['customform_suggested_identifier'],
					'<code>' . trim(preg_replace('/[^a-zA-Z0-9\-_.]/', '-', $data['title']), '-') . '</code>'
				)
			);

		$config_vars = [
			[
				'text',
				'field_title',
				'value' => $data['title'],
				'text_label' => $txt['customform_identifier'],
				'help' => 'customform_field_title',
			],
			[
				'callback',
				'field_text',
			],
			[
				'select',
				'field_type',
				'value' => strtr(
					$data['type'],
					[
						'largetextbox' => 'textarea',
						'textbox' => 'text',
						'checkbox' => 'check',
						'selectbox' => 'select',
						'float' => 'text',
						'int' => 'text',
						'radiobox' => 'radio',
						'infobox' => 'info'
					]
				),
				'text_label' => $txt['customform_type'],
				'help' => 'customform_type',
				iterator_to_array(
					$this->util->map(
						fn($cn, $key) => [
							$key === null ? $cn : $this->util->decamelize($key),
							$txt[$this->util->decamelize(strtr($cn, '\\', '_'))],
						],
						$this->util->find_classes(
							new GlobIterator(
								__DIR__ . '/Fields/*.php',
								FilesystemIterator::SKIP_DOTS
							),
							'CustomForm\Fields\\',
							'CustomForm\FieldInterface'
						)
					)
				)
			],
			[
				'text',
				'field_type_vars',
				'value' => $data['type_vars'],
				'text_label' => $txt['customform_type_vars'],
				'help' => 'customform_type_vars',
			],
		];
		add_integration_function('integrate_sceditor_options', __NAMESPACE__ . '\Integration::sce_options2', false);
		require_once $this->sourcedir . '/Subs-Editor.php';
		create_control_richedit(
			[
				'disable_smiley_box' => true,
				'id' => 'field_text',
				'value' => $data['text'] ?? '',
				'width' => '100%',
			]
		);
		loadCSSFile('customform.css', array('minimize' => true));
		loadJavaScriptFile('sceditor.plugins.customform.js', array('minimize' => true));
		addInlineJavaScript('
		textareaLengthCheck(document.getElementById("field_text"), 4096);', true);

		//	Set up the variables needed by the template.
		$context['settings_title'] = sprintf(
			'<a href="%s?action=admin;area=modsettings;sa=customform;">%s</a> -> <a href="%s?action=admin;area=modsettings;sa=customform;form_id=%s;act=editform">"%s" %s</a> -> "%s" %s',
			$this->scripturl,
			$txt['customform_generalsettings_heading'],
			$this->scripturl,
			$data['id_form'],
			$parent_data['title'],
			$txt['customform_form'],
			$data['title'],
			$txt['customform_field']
		);
		$context['post_url'] = sprintf('%s?action=admin;area=modsettings;sa=customform;field_id=%d;act=savefield', $this->scripturl, $field_id);
		$context['page_title'] = $txt['customform_tabheader'];
		$context['sub_template'] = 'show_settings';

		//	Finally prepare the settings array to be shown by the 'show_settings' template.
		prepareDBSettingContext($config_vars);
		createToken('admin-dbsc');
		loadTemplate('CustomForm');
		loadTemplate('GenericControls');
	}

	public function MoveFieldDown(int $field_id): void
	{
		$this->moveField($field_id, 1);
	}

	public function MoveFieldUp(int $field_id): void
	{
		$this->moveField($field_id, -1);
	}

	public function moveField(int $id_field, int $factor): void
	{
		$id_form = $this->getFormFromField($id_field);

		$request = $this->smcFunc['db_query']('', '
			SELECT id_field
			FROM {db_prefix}cf_fields
			WHERE id_form = {int:id_field}
			ORDER BY id_field',
			[
				'id_field' => $id_field,
			]
		);

		$siblings = [];
		$count = 0;
		$field_pos = 0;

		//	Make a list of the siblings
		while ([$db_id_field] = $this->smcFunc['db_fetch_row']($request))
		{
			//	Get the spot of the current field;
			if ($db_id_field == $id_field)
				$field_pos = $count;
			//	Store the necessary information.
			$siblings[] = $db_id_field;
			$count++;
		}

		//	Free the db result.
		$this->smcFunc['db_free_result']($request);

		//	Can we move the field?
		if (
			$count != 0
			&& $siblings != []
			&& $field_pos != 0 && $factor == -1
			&& $field_pos != $count - 1 && $factor == 1
		)
		{
			$replace_id = $siblings[$field_pos + $factor];
			//	Perform the rather hacky updating queries. - They do work, just hackily! ;D
			$this->smcFunc['db_query']('', '
				UPDATE {db_prefix}cf_fields
				SET id_field = \'0\'
				WHERE id_field = {int:field_id}',
				[
					'field_id' => $id_field,
				]
			);
			$this->smcFunc['db_query']('', '
				UPDATE {db_prefix}cf_fields
				SET id_field = {int:field_id}
				WHERE id_field = {int:replace_id}',
				[
					'field_id' => $id_field,
					'replace_id' => $replace_id,
				]
			);
			$this->smcFunc['db_query']('', '
				UPDATE {db_prefix}cf_fields
				SET id_field = {int:replace_id}
				WHERE id_field = \'0\'',
				[
					'replace_id' => $replace_id,
				]
			);
		}

		//	Take us back to the form setting page.
		redirectexit('action=admin;area=modsettings;sa=customform;act=editform;form_id=' . $id_form);
	}

	public function AddForm(): void
	{
		$this->smcFunc['db_insert'](
			'',
			'{db_prefix}cf_forms',
			['id_board' => 'int'],
			['0'],
			['id_form']
		);
		$form_id = $this->smcFunc['db_insert_id']('{db_prefix}cf_forms', 'id_form');

		redirectexit('action=admin;area=modsettings;sa=customform;act=editform;form_id=' . $form_id);
	}

	public function list_CustomForms(): array
	{
		global $txt;

		$permissions = $this->getPermissions();
		$membergroups = $this->getMembergroups();
		$list = [];
		$request = $this->smcFunc['db_query']('', '
			SELECT id_form, title, id_board, name
			FROM {db_prefix}cf_forms
				JOIN {db_prefix}boards USING (Id_board)'
		);

		while ($row = $this->smcFunc['db_fetch_assoc']($request))
		{
			$permissions_string = $txt['admin'];

			if (isset($permissions['custom_forms_' . $row['id_form']]))
				foreach ($permissions['custom_forms_' . $row['id_form']] as $membergroup_id)
					$permissions_string .= ', ' . $membergroups[$membergroup_id];

			$list[] = [
				'title' => $row['title'],
				'board' => $row['id_board'] . ' ("' . ($row['name'] ?? 'Invalid Board') . '")',
				'permissions' => $permissions_string,
				'modify' => sprintf(
					'
			<table width="100%%">
				<tr>
					<td width="50%%" style="text-align:center;">
						<a href="%s?action=admin;area=modsettings;sa=customform;form_id=%s;act=editform">
							(%s)
						</a>
					</td>
					<td width="50%%" style="text-align:center;">
						<a href="%1$s?action=admin;area=modsettings;sa=customform;form_id=%2$s;act=deleteform;" onclick="return confirm(\'%s\')" >
							(%s)
						</a>
					</td>
				</tr>
			</table>',
					$this->scripturl,
					$row['id_form'],
					$txt['customform_edit'],
					$txt['customform_delete_warning'],
					$txt['delete']
				),
			];
		}

		$this->smcFunc['db_free_result']($request);

		return $list;
	}

	public function list_customform_fields($nul0, $nul1, $nul2, $id): array
	{
		global $txt;

		$request = $this->smcFunc['db_query']('', '
			SELECT id_field, title, type, text
			FROM {db_prefix}cf_fields
			WHERE id_form = {int:id_form}
			ORDER BY id_field',
			[
				'id_form' => $id,
			]
		);

		$data = [];

		while ($row = $this->smcFunc['db_fetch_assoc']($request))
			$data[] = $row;

		$list = [];
		$i = 1;
		$end = count($data);
		addJavaScriptVar(
			'customformFields',
			array_column($data, 'title'),
			true
		);

		$result = iterator_to_array(
			$this->util->map(
				fn($cn, $key) => [
					$key === null ? $cn : $this->util->decamelize($key),
					$txt[$this->util->decamelize(strtr($cn, '\\', '_'))],
				],
				$this->util->find_classes(
					new GlobIterator(
						__DIR__ . '/Fields/*.php',
						FilesystemIterator::SKIP_DOTS
					),
					'CustomForm\Fields\\',
					'CustomForm\FieldInterface'
				)
			)
		);

		foreach ($data as $field)
		{
			$type = strtr(
				$field['type'],
				[
					'largetextbox' => 'textarea',
					'textbox' => 'text',
					'checkbox' => 'check',
					'selectbox' => 'select',
					'float' => 'text',
					'int' => 'text',
					'radiobox' => 'radio',
					'infobox' => 'info'
				]
			);
			$list[] = [
				'title' => $field['title'],
				'text' => $field['text'],
				'type' => $result[$type] ?? $type,
				'modify' => sprintf(
					'
			<table width="100%%">
				<tr>
					<td width="25%%" style="text-align:center;">
						<a href="%s?action=admin;area=modsettings;sa=customform;field_id=%s;act=movefieldup;">
							%s
						</a>
					</td>
					<td width="25%%" style="text-align:center;">
						<a href="%1$s?action=admin;area=modsettings;sa=customform;field_id=%2$s;act=movefielddown;" >
							%s
						</a>
					</td>
					<td width="50%%" style="text-align:center;">
						<a href="%1$s?action=admin;area=modsettings;sa=customform;field_id=%2$s;act=editfield">
							(%s)
						</a>
					</td>
					<td width="50%%" style="text-align:center;">
						<a href="%1$s?action=admin;area=modsettings;sa=customform;field_id=%2$s;act=deletefield" onclick="return confirm(\'%s\')" >
							(%s)
						</a>
					</td>
				</tr>
			</table>',
					$this->scripturl,
					$field['id_field'],
					($i != 1) ? '(' . $txt['customform_moveup'] . ')' : '',
					($i != $end) ? '(' . $txt['customform_movedown'] . ')' : '',
					$txt['customform_edit'],
					$txt['customform_delete_warning'],
					$txt['delete']
				),
			];
			$i++;
		}
		$this->smcFunc['db_free_result']($request);

		return $list;
	}

	public function getPermissions()
	{
		$permissions = [];
		$request = $this->smcFunc['db_query']('', '
			SELECT permission, id_group
			FROM {db_prefix}permissions
			WHERE permission
			LIKE \'custom_forms_%\''
		);

		while ($row = $this->smcFunc['db_fetch_assoc']($request))
			$permissions[$row['permission']][] = $row['id_group'];
		$this->smcFunc['db_free_result']($request);

		return $permissions;
	}

	public function getMembergroups()
	{
		global $modSettings, $txt;

		$membergroups = [-1 => $txt['guests'], 0 => $txt['users']];
		$request = $this->smcFunc['db_query']('', '
			SELECT id_group, group_name
			FROM {db_prefix}membergroups' . (!empty($modSettings['permission_enable_postgroups']) ? '' : '
			WHERE min_posts = -1')
		);

		while ($row = $this->smcFunc['db_fetch_assoc']($request))
			$membergroups[$row['id_group']] = $row['group_name'];
		$this->smcFunc['db_free_result']($request);

		return $membergroups;
	}

	public function AddField(int $form_id): void
	{
		$this->smcFunc['db_insert'](
			'',
			'{db_prefix}cf_fields',
			['id_form' => 'int'],
			[$form_id],
			['id_field']
		);
		$field_id = $this->smcFunc['db_insert_id']('{db_prefix}cf_fields', 'id_field');

		redirectexit('action=admin;area=modsettings;sa=customform;act=editfield;field_id=' . $field_id);
	}

	public function DeleteField(int $id_field): void
	{
		$id_form = $this->getFormFromField($id_field);

		$this->smcFunc['db_query']('', '
			DELETE
			FROM {db_prefix}cf_fields
			WHERE id_field = {int:id_field}',
			[
				'id_field' => $id_field,
			]
		);
		redirectexit('action=admin;area=modsettings;sa=customform;act=editform;form_id=' . $id_form);
	}

	public function SaveField(int $id_field): void
	{
		$id_form = $this->getFormFromField($id_field);

		$this->smcFunc['db_query']('', '
			UPDATE {db_prefix}cf_fields
			SET title = {string:title}, text = {string:text},
			type = {string:type}, type_vars = {string:type_vars}
			WHERE id_field = {int:id_field}',
			[
				'id_field' => $id_field,
				'title' => $_REQUEST['field_title'],
				'text' => $_REQUEST['field_text'],
				'type' => $_REQUEST['field_type'],
				'type_vars' => $_REQUEST['field_type_vars'],
			]
		);
		redirectexit('action=admin;area=modsettings;sa=customform;act=editform;form_id=' . $id_form);
	}

	public function listBoards(int $id_board): array
	{
		$request = $this->smcFunc['db_query']('', '
			SELECT id_board, b.name, child_level, c.name AS cat_name, id_cat
			FROM {db_prefix}boards AS b
				LEFT JOIN {db_prefix}categories AS c USING (id_cat)
			ORDER BY board_order'
		);
		$boards = [];

		while ($row = $this->smcFunc['db_fetch_assoc']($request))
		{
			if (!isset($boards[$row['id_cat']]))
				$boards[$row['id_cat']] = [
					'name' => strip_tags($row['cat_name']),
					'boards' => [],
				];

			$boards[$row['id_cat']]['boards'][$row['id_board']] = [
				'name' => strip_tags($row['name']),
				'child_level' => $row['child_level'],
				'selected' => $row['id_board'] == $id_board
			];
		}
		$this->smcFunc['db_free_result']($request);

		return $boards;
	}

	public function getFormFromField(int $field_id): int
	{
		$request = $this->smcFunc['db_query']('', '
			SELECT id_form
			FROM {db_prefix}cf_fields
			WHERE id_field = {int:id_field}',
			[
				'id_field' => $field_id,
			]
		);

		[$id_form] = $this->smcFunc['db_fetch_row']($request);
		$this->smcFunc['db_free_result']($request);

		return (int) $id_form;
	}
}
