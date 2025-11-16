<?php

declare(strict_types=1);

/**
 * @package   Custom Form mod
 * @version   4.1.1
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2014, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace CustomForm;

class CustomForm
{
	private string $scripturl;
	private string $sourcedir;
	private array $smcFunc;
	private Util $util;

	public function __construct(string $sa)
	{
		global $scripturl, $sourcedir, $smcFunc;

		$this->scripturl = $scripturl;
		$this->sourcedir = $sourcedir;
		$this->smcFunc = $smcFunc;
		$this->util = new Util;

		$call = match ($sa)
		{
			'thankyou' => 'ThankYou',
			'viewform' => 'ViewForm',
			default => 'ListForms',
		};

		if (isset($_REQUEST['thankyou']))
			$call = 'ThankYou';
		elseif (isset($_REQUEST['n']))
			$call = 'ViewForm';

		call_user_func([$this, $call]);
	}

	/**
	 * Static constructor / factory
	 */
	public static function create(): CustomForm
	{
		return new self($_GET['sa'] ?? '');
	}

	public function require_verification(): bool
	{
		global $modSettings, $user_info;

		return $user_info['is_guest'] || !$user_info['is_mod'] && !$user_info['is_admin'] && !empty($modSettings['posts_require_captcha']) && ($user_info['posts'] < $modSettings['posts_require_captcha']);
	}

	public function ListForms(): void
	{
		global $context, $modSettings, $txt;

		if (!allowedTo('customform_view_perms'))
			redirectexit();

		loadLanguage('CustomForm');
		loadTemplate('CustomForm');
		loadCSSFile('customform.css', array('minimize' => true));

		$request = $this->smcFunc['db_query']('', '
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
				)'
		);
		$context['forms'] = [];

		while ([$id_form, $title] = $this->smcFunc['db_fetch_row']($request))
			if (allowedTo('custom_forms_' . $id_form))
				$context['forms'][] = [$id_form, $title, ''];
		$this->smcFunc['db_free_result']($request);

		$context['template_layers'][] = 'forms';
		$context['page_title'] = !empty($modSettings['customform_view_title'])
			? $modSettings['customform_view_title']
			: $txt['customform_tabheader'];
		$context['linktree'][] = [
			'url' => $this->scripturl . '?action=form',
			'name' => $context['page_title'],
		];
		$context['page_message'] = $modSettings['customform_view_text'] ?? '';
		$context['sub_template'] = 'forms';
		$context['meta_description'] = $this->smcFunc['htmlspecialchars']($context['page_message']);
		$this->util->setMetaProperty('type', 'website');
	}

	private function ThankYou(): void
	{
		global $context, $modSettings, $txt;

		$context['page_title'] = !empty($modSettings['customform_view_title'])
			? $modSettings['customform_view_title']
			: $txt['customform_tabheader'];
		$context['linktree'][] = [
			'url' => $this->scripturl . '?action=form',
			'name' => $context['page_title'],
		];
		$context['robot_no_index'] = true;
		$context['sub_template'] = 'ThankYou';
		loadTemplate('CustomForm');
		loadLanguage('CustomForm');
	}

	private function validateInput(): array
	{
		$post_errors = [];

		if (($sc_error = checkSession('post', '', false)) != '')
			$post_errors[] = [['error_' . $sc_error, '']];

		if ($this->require_verification())
		{
			require_once $this->sourcedir . '/Subs-Editor.php';
			$verificationOptions = [
				'id' => 'customform',
			];

			if (($verification_errors = create_control_verification($verificationOptions, true)) !== true)
				foreach ($verification_errors as $verification_error)
					$post_errors[] = ['error_' . $verification_error, ''];
		}

		return $post_errors;
	}

	private function ViewForm(): void
	{
		global $user_info;

		if (isset($_REQUEST['form_id']) && !ctype_digit($_REQUEST['form_id']))
			fatal_lang_error('no_access', false);

		$form_id = (int) ($_REQUEST['n'] ?? 0);

		if (!allowedTo('custom_forms_' . $form_id))
			redirectexit('action=form');

		//	Get the data about the current form.
		$request = $this->smcFunc['db_query']('', '
			SELECT
				id_form, title, output, subject, id_board,
				icon, form_exit, template_function, output_type
			FROM {db_prefix}cf_forms AS f
			WHERE title != \'\'
				AND EXISTS
				(
					SELECT 1
					FROM {db_prefix}cf_fields AS d
					WHERE d.id_form = f.id_form
						AND title != \'\'
						AND text != \'\'
						AND type != \'\'
				)
				AND id_form = {int:id}',
			[
				'id' => $form_id,
			]
		);

		//	Did we get some form data? If not then redirect the user to the form view page.
		if (!($form_data = $this->smcFunc['db_fetch_assoc']($request)))
			redirectexit('action=form;');

		$this->smcFunc['db_free_result']($request);

		$request = $this->smcFunc['db_query']('', '
			SELECT id_field, title, text, type, type_vars
			FROM {db_prefix}cf_fields
			WHERE id_form = {int:id}
			AND title != \'\'
			AND text != \'\'
			AND type != \'\'
			ORDER BY id_field',
			[
				'id' => $form_id,
			]
		);

		$vars = [];
		$fields = [];
		$post_errors = [];

		while ($row = $this->smcFunc['db_fetch_assoc']($request))
		{
			$row['type'] = strtr(
				$row['type'],
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
			$class_name = 'CustomForm\Fields\\' . ucfirst($row['type']);

			if (!class_exists($class_name))
				fatal_error(
					sprintf(
						'Param "%s" not found for field "%s" at ID #%s.',
						$row['type'],
						$this->smcFunc['htmlspecialchars']($row['text']),
						$row['id_field']
					),
					false
				);

			$row['obj'] = new $class_name($row, $_POST['CustomFormField'][$row['id_field']] ?? '');
			$fields[] = $row;
		}

		$this->smcFunc['db_free_result']($request);

		if (isset($_POST['n']))
		{
			$post_errors = $this->validateInput();

			foreach ($fields as $field)
			{
				if (!$field['obj']->validate())
				{
					$post_errors['id_field_' . $field['id_field']] = $field['obj']->getError();

					continue;
				}

				// Add this field's value to the list of variables for the output post.
				$vars[$field['title']] = $field['obj']->getValue();
			}

			if ($post_errors === [])
			{
				$subject = $this->util->replaceVars($form_data['subject'], $vars);
				$output = $this->util->replaceVars($form_data['output'], $vars);

				$class_name = str_contains($form_data['output_type'], '\\')
					? $form_data['output_type']
					: 'CustomForm\Output\\' . $form_data['output_type'];

				if (!class_exists($class_name))
					fatal_error(
						sprintf(
							'Output type "%s" not found for form "%s" at ID #%s.',
							$this->smcFunc['htmlspecialchars']($form_data['output_type']),
							$this->smcFunc['htmlspecialchars']($form_data['title']),
							$form_data['id_form']
						),
						false
					);

				$output_type = new $class_name;
				$output_type->send($subject, $output, $form_data);

				$exit = match ($form_data['form_exit'])
				{
					'board', '' => 'board=' . $form_data['id_board'],
					'forum' => '',
					'form' => 'action=form',
					'thanks' => 'action=form;thankyou',
					default => $form_data['form_exit'],
				};
				redirectexit($exit);
			}
		}

		$this->prepareContext($post_errors, $form_data, $fields);
	}

	private function prepareContext(array $post_errors, array $form_data, array $fields): void
	{
		global $context, $modSettings, $txt;

		loadLanguage('CustomForm+Post+Errors');
		$context['fields'] = [];
		$context['page_title'] = $form_data['title'];
		$context['form_id'] = $form_data['id_form'];
		$context['failed_form_submit'] = $post_errors != [];

		if ($post_errors != [])
		{
			$context['post_errors'] = $post_errors;
			$context['template_layers'][] = 'errors';
		}

		loadTemplate('CustomFormUserland', null, false);
		loadTemplate('CustomForm');
		loadCSSFile('customform.css', array('minimize' => true));
		$template_function = 'template_' . $form_data['template_function'];
		$template = function_exists($template_function)
			? $form_data['template_function']
			: 'form';
		$context['sub_template'] = $template;
		$context['template_layers'][] = function_exists($template_function . '_above') && function_exists(
			$template_function . '_below'
		) ? $template : 'form';
		$context['linktree'][] = [
			'url' => $this->scripturl . '?action=form',
			'name' => !empty($modSettings['customform_view_title'])
				? $modSettings['customform_view_title']
				: $txt['customform_tabheader'],
		];
		$context['linktree'][] = [
			'url' => $this->scripturl . '?action=form;n=' . $form_data['id_form'],
			'name' => $form_data['title'],
		];
		$context['meta_description'] = $this->smcFunc['htmlspecialchars']($form_data['title']);
		$this->util->setMetaProperty('type', 'website');

		foreach ($fields as $field)
		{
			$field['obj']->setHtml();

			$context['fields'][$field['title']] = [
				'text' => parse_bbc($this->smcFunc['htmlspecialchars']($field['text'])),
				'type' => $field['type'],
				'html' => $field['obj']->getInputHtml(),
				'required' => $field['obj']->isRequired(),
				'failed' => isset($post_errors['id_field' . $field['id_field']]),
			];
		}

		$context['require_verification'] = $this->require_verification();

		if ($context['require_verification'])
		{
			require_once $this->sourcedir . '/Subs-Editor.php';
			$verificationOptions = [
				'id' => 'customform',
			];
			$context['visual_verification'] = create_control_verification($verificationOptions);
		}
	}
}