<?php

declare(strict_types=1);

/**
 * @package   Custom Form mod
 * @version   4.1.0
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2014, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace CustomForm;

use SMF\
{Actions\Admin\ACP,
	Actions\Admin\Permissions,
	Board,
	Config,
	Db\DatabaseApi as Db,
	Editor,
	Group,
	IntegrationHook,
	ItemList,
	Lang,
	SecurityToken,
	Theme,
	User,
	Utils};

class ManageCustomForm
{
	public const ADDFORM = 'addform';
	public const DELETEFORM = 'deleteform';
	public const EDITFORM = 'editform';
	public const SAVEFORM = 'saveform';
	public const ADDFIELD = 'addfield';
	public const EDITFIELD = 'editfield';
	public const DELETEFIELD = 'deletefield';
	public const MOVEFIELDDOWN = 'movefielddown';
	public const MOVEFIELDUP = 'movefieldup';
	public const SAVEFIELD = 'savefield';
	public const VERSION = '4.1.0';

	private Util $util;

	public function __construct(string $act)
	{
		User::$me->isAllowedTo('admin_forum');

		$form_id = (int) ($_GET['form_id'] ?? 0);
		$field_id = (int) ($_GET['field_id'] ?? 0);

		$call = match ($act) {
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
		$this->util = new Util();
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
		return [
			['permissions', 'customform_view_perms', 'subtext' => Lang::$txt['customform_view_perms_desc']],
			['text', 'customform_view_title', 'subtext' => Lang::$txt['customform_view_title_desc']],
			['large_text', 'customform_view_text', 'subtext' => Lang::$txt['customform_view_text_desc']],
		];
	}

	public function ModifySettings(): void
	{
		$config_vars = self::getConfigVars();

		if (isset($_GET['update'])) {
			User::$me->checkSession();
			ACP::saveDBSettings($config_vars);
			Utils::redirectexit('action=admin;area=modsettings;sa=customform');
		}

		$list = [
			'id' => 'customform_list',
			'title' => Lang::$txt['customform_tabheader'],
			'no_items_label' => Lang::$txt['customform_list_noelements'],
			'get_items' => [
				'function' => [$this, 'list_CustomForms'],
			],
			'columns' => [
				'title' => [
					'header' => [
						'value' => Lang::$txt['title'],
					],
					'data' => [
						'db_htmlsafe' => 'title',
					],
				],
				'board' => [
					'header' => [
						'value' => Lang::$txt['customform_board'],
					],
					'data' => [
						'db' => 'board',
					],
				],
				'permissions' => [
					'header' => [
						'value' => Lang::$txt['edit_permissions'],
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
						Config::$scripturl,
						Lang::$txt['customform_add_form'],
					),
					'class' => 'righttext',
				],
			],
		];

		ItemList::load($list);

		//	Set up the variables needed by the template.
		Utils::$context['settings_title'] = Lang::$txt['customform_generalsettings_heading'];
		Utils::$context['page_title'] = Lang::$txt['customform_tabheader'];
		Utils::$context['default_list'] = 'customform_list';
		Utils::$context['post_url'] = Config::$scripturl . '?action=admin;area=modsettings;sa=customform;update';
		Theme::loadTemplate('CustomForm');
		Theme::loadJavaScriptFile('customform.js', ['minimize' => true]);
		Theme::addInlineJavaScript('
		textareaLengthCheck(document.getElementById("customform_view_text"), 320);', true);
		Utils::$context['sub_template'] = 'customform_GeneralSettings';
		ACP::prepareDBSettingContext($config_vars);

		// Two tokens because saving these settings requires both save_inline_permissions and saveDBSettings
		SecurityToken::create('admin-mp');
		SecurityToken::create('admin-dbsc');

		$request = Db::$db->query(
			'',
			'
			SELECT data
			FROM {db_prefix}admin_info_files
			WHERE filename = \'customform/versions.json\'',
		);

		[$data] = Db::$db->fetch_row($request);
		Db::$db->free_result($request);

		if ($data !== null) {
			$data = json_decode($data, true);
			$v1 = Version::fromString(self::VERSION);
			$v2 = Version::fromString($data[0]['version']['string']);

			if ($data !== null && $v1->getMajor() === $v2->getMajor() && $v1->compareTo($v2) === -1) {
				$changes = '';

				foreach ($data[1]['changes'] as $type => $change) {
					$changes .= '<h4>' . $type . '</h4><ul><li>&emsp;' . implode('</li><li>&emsp;', $change) . '</li></ul>';
				}

				Utils::$context['settings_insert_above'] = sprintf(
					'<div class="noticebox" style="overflow:auto; max-height: 11em;"><h3 id="update_title">%s</h3><div class="padding"><a class="button floatnone" href="%s?action=admin;area=packages;pgdownload;auto;package=%s;%s=%s">%s</a>&emsp;<a class="button floatnone" href="%2$s?action=admin;area=modsettings;sa=customform;act=delay">%s</a></div>%s</div>',
					Lang::$txt['customform_update_available'],
					Config::$scripturl,
					sprintf(
						'https://github.com/live627/smf-custom-forms/releases/download/v%s/custom-forms_%d-%d-%d.tgz',
						$data[0]['version']['string'],
						$data[0]['version']['major'],
						$data[0]['version']['minor'],
						$data[0]['version']['patch'],
					),
					Utils::$context['session_var'],
					Utils::$context['session_id'],
					sprintf(Lang::$txt['customform_update_action'], $data[0]['version']['string']),
					Lang::$txt['customform_update_later'],
					$changes,
				);
			}
		}
	}

	public function Delay(): void
	{
		Db::$db->query(
			'',
			'
			UPDATE {db_prefix}admin_info_files
			SET data = \'\'
			WHERE filename = \'customform/versions.json\'',
		);

		Utils::redirectexit('action=admin;area=modsettings;sa=customform');
	}

	public function EditForm(int $form_id): void
	{
		/* @var Form $form */
		$form = iterator_to_array(Form::fetchManyForAdmin([$form_id], false))[0];

		if ($form == []) {
			Utils::redirectexit('action=admin;area=modsettings;sa=customform');
		}

		Utils::$context['custom_form_settings'] = [
			'permissions' => 'custom_forms_' . $form_id,
			'form_board_id' => $form->board_id,
			'icon' => $form->icon,
			'form_title' => $form->title,
			'subject' => $form->subject,
			'form_exit' => $form->form_exit,
			'output' => $form->output,
			'template_function' => $form->template_function,
		];

		//	Create the list of fields.
		$list = [
			'id' => 'customform_list_fields',
			'title' => Lang::$txt['customform_listheading_fields'],
			'no_items_label' => Lang::$txt['customform_list_noelements'],
			'get_items' => [
				'function' => [$this, 'list_customform_fields'],
				'params' => [$form_id],
			],
			'columns' => [
				'title' => [
					'header' => [
						'value' => Lang::$txt['customform_identifier'],
					],
					'data' => [
						'db_htmlsafe' => 'title',
					],
				],
				'text' => [
					'header' => [
						'value' => Lang::$txt['customform_text'],
					],
					'data' => [
						'db_htmlsafe' => 'text',
					],
				],
				'type' => [
					'header' => [
						'value' => Lang::$txt['customform_type'],
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
						Config::$scripturl,
						$form_id,
						Lang::$txt['customform_add_field'],
					),
					'class' => 'righttext',
				],
			],
		];

		ItemList::load($list);
		Permissions::init_inline_permissions(['custom_forms_' . $form_id]);
		IntegrationHook::add('integrate_sceditor_options', __NAMESPACE__ . '\Integration::sce_options', false);
		Editor::load(
			[
				'disable_smiley_box' => true,
				'id' => 'output',
				'value' => $form->output ?? '',
				'width' => '100%',
			],
		);
		Theme::loadCSSFile('customformadmin.css', ['minimize' => true]);
		Theme::loadJavaScriptFile('sceditor.plugins.customform.js', ['minimize' => true]);
		Theme::loadJavaScriptFile('customform.js', ['minimize' => true]);

		//	Set up the variables needed by the template.
		Utils::$context['settings_title'] = sprintf(
			'<a href="%s?action=admin;area=modsettings;sa=customform;">%s</a> -> %s" %s',
			Config::$scripturl,
			Lang::$txt['customform_generalsettings_heading'],
			$form->title,
			Lang::$txt['customform_form'],
		);
		Utils::$context['post_url'] = Config::$scripturl . '?action=admin;area=modsettings;sa=customform;act=saveform;form_id=' . $form_id;
		Utils::$context['page_title'] = Lang::$txt['customform_tabheader'];
		Utils::$context['default_list'] = 'customform_list_fields';
		Utils::$context['sub_template'] = 'customform_GeneralSettings';
		Utils::$context['categories'] = $this->listBoards($form->board_id);

		Utils::$context['icons'] = Editor::getMessageIcons($form->board_id);
		$setting = file_exists(Theme::$current->settings['theme_dir'] . '/images/post/' . $form->icon . '.png')
			? 'images_url'
			: 'default_images_url';
		Utils::$context['icon_url'] = Theme::$current->settings[$setting] . '/post/' . $form->icon . '.png';
		array_unshift(
			Utils::$context['icons'],
			[
				'value' => $form->icon,
				'name' => Lang::$txt['current_icon'],
				'url' => Utils::$context['icon_url'],
			],
		);

		$config_vars = [
			[
				'text',
				'title',
				'value' => $form->title,
				'text_label' => Lang::$txt['title'],
			],
			[
				'callback',
				'boards',
			],
			[
				'text',
				'template_function',
				'value' => $form->template_function,
				'text_label' => Lang::$txt['customform_template_function'],
				'help' => 'customform_template_function',
			],
			[
				'permissions',
				'custom_forms_' . $form_id,
				'value' => 'custom_forms_' . $form_id,
				'text_label' => Lang::$txt['edit_permissions'],
			],
			[
				'text',
				'exit',
				'value' => $form->form_exit,
				'text_label' => Lang::$txt['customform_exit'],
				'help' => 'customform_submit_exit',
			],
			[
				'select',
				'output_type',
				'value' => $form->output_type,
				'text_label' => Lang::$txt['customform_output_type'],
				'subtext' => Lang::$txt['customform_output_type_desc'],
				iterator_to_array(
					$this->util->map(
						fn($cn, $key) => [$key ?? $cn, Lang::$txt[$this->util->decamelize(strtr($cn, '\\', '_'))]],
						$this->util->find_classes(
							new \GlobIterator(
								__DIR__ . '/Output/*.php',
								\FilesystemIterator::SKIP_DOTS,
							),
							'CustomForm\Output\\',
							OutputInterface::class,
						),
					),
				),
			],
			[
				'callback',
				'output',
			],
		];
		Theme::loadTemplate('CustomForm');
		Theme::loadTemplate('GenericControls');
		Theme::loadTemplate('GenericList');
		ACP::prepareDBSettingContext($config_vars);
	}

	public function SaveForm(int $form_id): void
	{
		Db::$db->query(
			'',
			'
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
			],
		);

		Permissions::save_inline_permissions(['custom_forms_' . $form_id]);

		Utils::redirectexit('action=admin;area=modsettings;sa=customform;act=editform;form_id=' . $form_id);
	}

	public function deleteForm(int $form_id): void
	{
		Db::$db->query(
			'',
			'
			DELETE
			FROM {db_prefix}cf_forms
			WHERE id_form = {int:id_form}',
			[
				'id_form' => $form_id,
			],
		);
		Db::$db->query(
			'',
			'
			DELETE
			FROM {db_prefix}permissions
			WHERE permission = {string:permission}',
			[
				'permission' => 'custom_forms_' . $form_id,
			],
		);
		Db::$db->query(
			'',
			'
			DELETE
			FROM {db_prefix}cf_fields
			WHERE id_form = {int:id_form}',
			[
				'id_form' => $form_id,
			],
		);
		Utils::redirectexit('action=admin;area=modsettings;sa=customform');
	}

	public function editField(int $field_id): void
	{
		/* @var Field $field */
		$field = iterator_to_array(Field::fetchManyForAdmin([$field_id]))[0];

		if ($field == []) {
			Utils::redirectexit('action=admin;area=modsettings;sa=customform');
		}
		/* @var Form $form */
		$form = iterator_to_array(Form::fetchManyForAdmin([$field->form_id], false))[0];

		$invalid = preg_match('/[^a-zA-Z0-9\-_.]/', $field->title);

		if ($invalid) {
			Utils::$context['settings_insert_above'] = sprintf(
				'<div class="errorbox">%s<ul><li>%s</li><li>%s</li></ul></div>',
				Lang::$txt['customform_character_warning'],
				sprintf(
					Lang::$txt['customform_current_identifier'],
					'<code>' . Utils::htmlspecialchars($field->title) . '</code>',
				),
				sprintf(
					Lang::$txt['customform_suggested_identifier'],
					'<code>' . trim(preg_replace('/[^a-zA-Z0-9\-_.]/', '-', $field->title), '-') . '</code>',
				),
			);
		}

		$config_vars = [
			[
				'text',
				'field_title',
				'value' => $field->title,
				'text_label' => Lang::$txt['customform_identifier'],
				'help' => 'customform_field_title',
			],
			[
				'select',
				'field_type',
				'value' => strtr(
					$field->type,
					[
						'largetextbox' => 'textarea',
						'textbox' => 'text',
						'checkbox' => 'check',
						'selectbox' => 'select',
						'float' => 'text',
						'int' => 'text',
						'radiobox' => 'radio',
						'infobox' => 'info',
					],
				),
				'text_label' => Lang::$txt['customform_type'],
				'help' => 'customform_type',
				iterator_to_array(
					$this->util->map(
						fn($cn, $key) => [
							$key === null ? $cn : $this->util->decamelize($key),
							Lang::$txt[$this->util->decamelize(strtr($cn, '\\', '_'))],
						],
						$this->util->find_classes(
							new \GlobIterator(
								__DIR__ . '/Fields/*.php',
								\FilesystemIterator::SKIP_DOTS,
							),
							'CustomForm\Fields\\',
							FieldInterface::class,
						),
					),
				),
			],
			[
				'large_text',
				'field_type_vars',
				'value' => $field->type_vars,
				'text_label' => Lang::$txt['customform_type_vars'],
				'help' => 'customform_type_vars',
			],
			[
				'callback',
				'field_text',
			],
		];
		IntegrationHook::add('integrate_sceditor_options', __NAMESPACE__ . '\Integration::sce_options2', false);
		Editor::load(
			[
				'disable_smiley_box' => true,
				'id' => 'field_text',
				'value' => $field->text ?? '',
				'width' => '100%',
			],
		);
		Theme::loadCSSFile('customformadmin.css', ['minimize' => true]);
		Theme::loadJavaScriptFile('sceditor.plugins.customform.js', ['minimize' => true]);
		Theme::addInlineJavaScript('
		textareaLengthCheck(document.getElementById("field_text"), 4096);', true);

		//	Set up the variables needed by the template.
		Utils::$context['settings_title'] = sprintf(
			'<a href="%s?action=admin;area=modsettings;sa=customform;">%s</a> -> <a href="%s?action=admin;area=modsettings;sa=customform;form_id=%s;act=editform">"%s" %s</a> -> "%s" %s',
			Config::$scripturl,
			Lang::$txt['customform_generalsettings_heading'],
			Config::$scripturl,
			$field->id,
			$form->title,
			Lang::$txt['customform_form'],
			$field->title,
			Lang::$txt['customform_field'],
		);
		Utils::$context['post_url'] = sprintf('%s?action=admin;area=modsettings;sa=customform;field_id=%d;act=savefield', Config::$scripturl, $field_id);
		Utils::$context['page_title'] = Lang::$txt['customform_tabheader'];
		Utils::$context['sub_template'] = 'show_settings';

		ACP::prepareDBSettingContext($config_vars);
		Theme::loadTemplate('CustomForm');
		Theme::loadTemplate('GenericControls');
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

		$request = Db::$db->query(
			'',
			'
			SELECT id_field
			FROM {db_prefix}cf_fields
			WHERE id_form = {int:id_field}
			ORDER BY id_field',
			[
				'id_field' => $id_field,
			],
		);

		$siblings = [];
		$count = 0;
		$field_pos = 0;

		//	Make a list of the siblings
		while ([$db_id_field] = Db::$db->fetch_row($request)) {
			//	Get the spot of the current field;
			if ($db_id_field == $id_field) {
				$field_pos = $count;
			}
			//	Store the necessary information.
			$siblings[] = $db_id_field;
			$count++;
		}

		//	Free the db result.
		Db::$db->free_result($request);

		//	Can we move the field?
		if (
			$count != 0
			&& $siblings != []
			&& $field_pos != 0 && $factor == -1
			&& $field_pos != $count - 1 && $factor == 1
		) {
			$replace_id = $siblings[$field_pos + $factor];
			//	Perform the rather hacky updating queries. - They do work, just hackily! ;D
			Db::$db->query(
				'',
				'
				UPDATE {db_prefix}cf_fields
				SET id_field = \'0\'
				WHERE id_field = {int:field_id}',
				[
					'field_id' => $id_field,
				],
			);
			Db::$db->query(
				'',
				'
				UPDATE {db_prefix}cf_fields
				SET id_field = {int:field_id}
				WHERE id_field = {int:replace_id}',
				[
					'field_id' => $id_field,
					'replace_id' => $replace_id,
				],
			);
			Db::$db->query(
				'',
				'
				UPDATE {db_prefix}cf_fields
				SET id_field = {int:replace_id}
				WHERE id_field = \'0\'',
				[
					'replace_id' => $replace_id,
				],
			);
		}

		//	Take us back to the form setting page.
		Utils::redirectexit('action=admin;area=modsettings;sa=customform;act=editform;form_id=' . $id_form);
	}

	public function AddForm(): void
	{
		Db::$db->insert(
			'',
			'{db_prefix}cf_forms',
			['id_board' => 'int', 'title' => 'string', 'icon' => 'string', 'output' => 'string', 'subject' => 'string', 'form_exit' => 'string', 'template_function' => 'string', 'output_type' => 'string'],
			['0', '', '', '', '', '', '', ''],
			['id_form'],
		);
		$form_id = Db::$db->insert_id('{db_prefix}cf_forms', 'id_form');

		Utils::redirectexit('action=admin;area=modsettings;sa=customform;act=editform;form_id=' . $form_id);
	}

	public function list_CustomForms(): array
	{
		Group::loadSimple(
			Group::LOAD_NORMAL | (int) !empty(Config::$modSettings['permission_enable_postgroups']),
		);
		$list = [];
		$permissions_check = [];
		$entries = Form::fetchManyForAdmin(null, false);

		foreach ($entries as $form) {
			$permissions_check[] = 'custom_forms_' . $form->id;
		}

		$member_groups = User::groupsAllowedTo($permissions_check, null, false);
		Board::load([], ['selects' => ['b.id_board', 'b.name']]);

		/** @var Form $form */
		foreach ($entries as $form) {
			$permissions_string = Lang::$txt['admin'];

			if (isset($member_groups['custom_forms_' . $form->id])) {
				foreach ($member_groups['custom_forms_' . $form->id]['allowed'] as $group_id) {
					$permissions_string .= ', ' . Group::$loaded[$group_id]->name;
				}
			}

			$list[] = [
				'title' => $form->title,
				'board' => $form->board_id . ' ("' . (Board::$loaded[$form->board_id]->name ?? 'Invalid Board') . '")',
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
					Config::$scripturl,
					$form->id,
					Lang::$txt['customform_edit'],
					Lang::$txt['customform_delete_warning'],
					Lang::$txt['delete'],
				),
			];
		}

		return $list;
	}

	public function list_customform_fields($nul0, $nul1, $nul2, $id): array
	{
		$request = Db::$db->query(
			'',
			'
			SELECT id_field, title, type, text
			FROM {db_prefix}cf_fields
			WHERE id_form = {int:id_form}
			ORDER BY id_field',
			[
				'id_form' => $id,
			],
		);

		$data = [];

		while ($row = Db::$db->fetch_assoc($request)) {
			$data[] = $row;
		}

		$list = [];
		$i = 1;
		$end = count($data);
		Theme::addJavaScriptVar(
			'customformFields',
			array_column($data, 'title'),
			true,
		);

		$result = iterator_to_array(
			$this->util->map(
				fn($cn, $key) => [
					$key === null ? $cn : $this->util->decamelize($key),
					Lang::$txt[$this->util->decamelize(strtr($cn, '\\', '_'))],
				],
				$this->util->find_classes(
					new \GlobIterator(
						__DIR__ . '/Fields/*.php',
						\FilesystemIterator::SKIP_DOTS,
					),
					'CustomForm\Fields\\',
					FieldInterface::class,
				),
			),
		);

		foreach ($data as $field) {
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
					'infobox' => 'info',
				],
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
					Config::$scripturl,
					$field['id_field'],
					($i != 1) ? '(' . Lang::$txt['customform_moveup'] . ')' : '',
					($i != $end) ? '(' . Lang::$txt['customform_movedown'] . ')' : '',
					Lang::$txt['customform_edit'],
					Lang::$txt['customform_delete_warning'],
					Lang::$txt['delete'],
				),
			];
			$i++;
		}
		Db::$db->free_result($request);

		return $list;
	}

	public function AddField(int $form_id): void
	{
		Db::$db->insert(
			'',
			'{db_prefix}cf_fields',
			['id_form' => 'int', 'title' => 'string', 'type' => 'string', 'text' => 'string', 'type_vars' => 'string'],
			[$form_id, '', '', '', ''],
			['id_field'],
		);
		$field_id = Db::$db->insert_id('{db_prefix}cf_fields', 'id_field');

		Utils::redirectexit('action=admin;area=modsettings;sa=customform;act=editfield;field_id=' . $field_id);
	}

	public function DeleteField(int $id_field): void
	{
		$id_form = $this->getFormFromField($id_field);

		Db::$db->query(
			'',
			'
			DELETE
			FROM {db_prefix}cf_fields
			WHERE id_field = {int:id_field}',
			[
				'id_field' => $id_field,
			],
		);
		Utils::redirectexit('action=admin;area=modsettings;sa=customform;act=editform;form_id=' . $id_form);
	}

	public function SaveField(int $id_field): void
	{
		$id_form = $this->getFormFromField($id_field);

		Db::$db->query(
			'',
			'
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
			],
		);
		Utils::redirectexit('action=admin;area=modsettings;sa=customform;act=editform;form_id=' . $id_form);
	}

	public function listBoards(int $id_board): array
	{
		$request = Db::$db->query(
			'',
			'
			SELECT id_board, b.name, child_level, c.name AS cat_name, id_cat
			FROM {db_prefix}boards AS b
				LEFT JOIN {db_prefix}categories AS c USING (id_cat)
			ORDER BY board_order',
		);
		$boards = [];

		while ($row = Db::$db->fetch_assoc($request)) {
			if (!isset($boards[$row['id_cat']])) {
				$boards[$row['id_cat']] = [
					'name' => strip_tags($row['cat_name']),
					'boards' => [],
				];
			}

			$boards[$row['id_cat']]['boards'][$row['id_board']] = [
				'name' => strip_tags($row['name']),
				'child_level' => $row['child_level'],
				'selected' => $row['id_board'] == $id_board,
			];
		}
		Db::$db->free_result($request);

		return $boards;
	}

	public function getFormFromField(int $field_id): int
	{
		$request = Db::$db->query(
			'',
			'
			SELECT id_form
			FROM {db_prefix}cf_fields
			WHERE id_field = {int:id_field}',
			[
				'id_field' => $field_id,
			],
		);

		[$id_form] = Db::$db->fetch_row($request);
		Db::$db->free_result($request);

		return (int) $id_form;
	}
}
