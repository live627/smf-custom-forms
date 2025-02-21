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

use SMF\{
	ActionInterface,
	ActionTrait,
	Config,
	ErrorHandler,
	Lang,
	Parser,
	Routable,
	Slug,
	Theme,
	User,
	Utils,
	Verifier
};

class CustomForm implements ActionInterface, Routable
{
	use ActionTrait;

	private Util $util;

	/****************
	 * Public methods
	 ****************/

	/**
	 * Dispatcher to whichever sub-action method is necessary.
	 */
	public function execute(): void
	{
		$call = match ($_GET['sa'] ?? '') {
			'thankyou' => 'ThankYou',
			'viewform' => 'ViewForm',
			default => 'ListForms',
		};

		if (isset($_REQUEST['thankyou'])) {
			$call = 'ThankYou';
		} elseif (isset($_REQUEST['n'])) {
			$call = 'ViewForm';
		}

		call_user_func([$this, $call]);
	}

	public function ListForms(): void
	{
		if (!User::$me->allowedTo('customform_view_perms')) {
			Utils::redirectexit();
		}

		Lang::load('CustomForm');
		Theme::loadTemplate('CustomForm');
		Theme::loadCSSFile('customform.css', ['minimize' => true]);

		Utils::$context['forms'] = [];
		$entries = Form::fetchMany(null, false);

		foreach ($entries as $form) {
			if (User::$me->allowedTo('custom_forms_' . $form->id)) {
				Utils::$context['forms'][] = [$form->id, $form->title, ''];
			}

			// Create the slug for this form.  Do this here to prevent
			// extra queries when building the route.
			Slug::create($form->title, 'form', $form->id);
		}

		Utils::$context['template_layers'][] = 'forms';
		Utils::$context['page_title'] = !empty(Config::$modSettings['customform_view_title'])
			? Config::$modSettings['customform_view_title']
			: Lang::$txt['customform_tabheader'];
		Utils::$context['linktree'][] = [
			'url' => Config::$scripturl . '?action=form',
			'name' => Utils::$context['page_title'],
		];
		Utils::$context['page_message'] = Config::$modSettings['customform_view_text'] ?? '';
		Utils::$context['sub_template'] = 'forms';
		Utils::$context['meta_description'] = Utils::htmlspecialchars(Utils::$context['page_message']);
		$this->util->setMetaProperty('type', 'website');
	}

	public function ThankYou(): void
	{
		Utils::$context['page_title'] = !empty(Config::$modSettings['customform_view_title'])
			? Config::$modSettings['customform_view_title']
			: Lang::$txt['customform_tabheader'];
		Utils::$context['linktree'][] = [
			'url' => Config::$scripturl . '?action=form',
			'name' => Utils::$context['page_title'],
		];
		Utils::$context['robot_no_index'] = true;
		Utils::$context['sub_template'] = 'ThankYou';
		Theme::loadTemplate('CustomForm');
		Lang::load('CustomForm');
	}

	public function ViewForm(): void
	{
		if (isset($_REQUEST['form_id']) && !ctype_digit($_REQUEST['form_id'])) {
			ErrorHandler::fatalLang('no_access', false);
		}

		$form_id = (int) ($_REQUEST['n'] ?? 0);

		if (!User::$me->allowedTo('custom_forms_' . $form_id)) {
			Utils::redirectexit('action=form');
		}

		/* @var Form $form_data */
		if (!($form_data = Form::load($form_id))) {
			Utils::redirectexit('action=form;');
		}

		$vars = [
			'yes' => Lang::$txt['yes'],
			'no' => Lang::$txt['no'],
		];
		$post_errors = [];

		if (isset($_POST['n'])) {
			$post_errors = $this->validateInput();

			foreach ($form_data->fields as $field) {
				if (!$field->obj->validate()) {
					$post_errors['id_field_' . $field->id] = $field->obj->getError();

					continue;
				}

				// Add this field's value to the list of variables for the output post.
				$vars[$field->title] = $field->obj->getValue();
			}

			if ($post_errors === []) {
				$subject = $this->util->replaceVars($form_data->subject, $vars);
				$output = $this->util->replaceVars($form_data->output, $vars);

				$output_type = $form_data->getOutputInstance();

				if (!$output_type) {
					ErrorHandler::fatal(
						sprintf(
							'Output type "%s" not found for form "%s" at ID #%s.',
							Utils::htmlspecialchars($form_data->output_type),
							Utils::htmlspecialchars($form_data->title),
							$form_data->id,
						),
						false,
					);
				}
				$output_type->send($subject, $output, $form_data);

				$exit = match ($form_data->form_exit) {
					'board', '' => 'board=' . $form_data->board_id,
					'forum' => '',
					'form' => 'action=form',
					'thanks' => 'action=form;thankyou',
					default => $form_data->form_exit,
				};
				Utils::redirectexit($exit);
			}
		}

		$this->prepareContext($post_errors, $form_data);
	}

	private function prepareContext(array $post_errors, Form $form_data): void
	{
		Lang::load('CustomForm+Post+Errors');
		Utils::$context['fields'] = [];
		Utils::$context['page_title'] = $form_data->title;
		Utils::$context['form_id'] = $form_data->id;
		Utils::$context['failed_form_submit'] = $post_errors != [];

		if ($post_errors != []) {
			Utils::$context['post_errors'] = $post_errors;
			Utils::$context['template_layers'][] = 'errors';
		}
		Theme::loadTemplate('CustomFormUserland', '', false);
		Theme::loadTemplate('CustomForm');
		Theme::loadCSSFile('customform.css', ['minimize' => true]);
		$template_function = 'template_' . $form_data->template_function;
		$template = function_exists($template_function)
			? $form_data->template_function
			: 'form';
		Utils::$context['sub_template'] = $template;
		Utils::$context['template_layers'][] = function_exists($template_function . '_above') && function_exists(
			$template_function . '_below',
		) ? $template : 'form';
		Utils::$context['linktree'][] = [
			'url' => Config::$scripturl . '?action=form',
			'name' => !empty(Config::$modSettings['customform_view_title'])
				? Config::$modSettings['customform_view_title']
				: Lang::$txt['customform_tabheader'],
		];
		Utils::$context['linktree'][] = [
			'url' => Config::$scripturl . '?action=form;n=' . $form_data->id,
			'name' => $form_data->title,
		];
		Utils::$context['meta_description'] = Utils::htmlspecialchars($form_data->title);
		$this->util->setMetaProperty('type', 'website');

		foreach ($form_data->fields as $field) {
			if (!$field->obj) {
				fatal_error(
					sprintf(
						'Param "%s" not found for field "%s" at ID #%s.',
						$field->type,
						Utils::htmlspecialchars($field->title),
						$field->id,
					),
					false,
				);
			}

			$field->obj->setHtml();

			Utils::$context['fields'][$field->title] = [
				'text' => Parser::transform(Utils::htmlspecialchars($field->text)),
				'type' => $field->type,
				'html' => $field->obj->getInputHtml(),
				'required' => $field->obj->isRequired(),
				'failed' => isset($post_errors['id_field' . $field->id]),
			];
		}

		Utils::$context['require_verification'] = $this->require_verification();

		if (Utils::$context['require_verification']) {
			$verificationOptions = [
				'id' => 'customform',
			];
			Utils::$context['visual_verification'] = Verifier::create($verificationOptions);
		}
	}

	/***********************
	 * Public static methods
	 ***********************/

	/**
	 * Builds a routing path based on URL query parameters.
	 *
	 * @param array $params URL query parameters.
	 *
	 * @return array Contains two elements: ['route' => [], 'params' => []].
	 *    The 'route' element contains the routing path. The 'params' element
	 *    contains any $params that weren't incorporated into the route.
	 */
	public static function buildRoute(array $params): array
	{
		$route = [$params['action']];
		unset($params['action']);

		if (isset($params['n'])) {
			if (isset(Slug::$known['form'][(int) $params['n']])) {
				$slug = (string) Slug::$known['form'][(int) $params['n']];
			} elseif (($slug = Slug::getCached('form', (int) $params['n'])) === '') {
				$form = Form::load((int) $params['n']);

				if ($form instanceof Form) {
					$slug = (string) new Slug($form->title, 'form', $form->id);
				} else {
					$slug = '';
				}
			}

			$route[] = $slug . (str_ends_with($slug, '-' . $params['n']) ? '' : ($slug !== '' ? '-' : '') . $params['n']);

			unset($params['n']);
		}

		return [
			'route' => $route,
			'params' => $params,
		];
	}

	/**
	 * Parses a route to get URL query parameters.
	 *
	 * @param array $route Array of routing path components.
	 * @param array $params Any existing URL query parameters.
	 *
	 * @return array URL query parameters
	 */
	public static function parseRoute(array $route, array $params = []): array
	{
		$params['action'] = array_shift($route);

		if ($route !== []) {
			preg_match('/^(\X*?)(\d+)$/u', array_shift($route), $matches);

			$params['n'] = $matches[2];

			Slug::setRequested(rtrim($matches[1], '-'), 'form', (int) $params['n']);
		}

		return $params;
	}

	/******************
	 * Internal methods
	 ******************/

	/**
	 * Constructor. Protected to force instantiation via self::load().
	 */
	protected function __construct() {}

	private function require_verification(): bool
	{
		return User::$me->is_guest || !User::$me->is_mod && !User::$me->is_admin && !empty(Config::$modSettings['posts_require_captcha']) && (User::$me->posts < Config::$modSettings['posts_require_captcha']);
	}

	private function validateInput(): array
	{
		$post_errors = [];

		if (($sc_error = User::$me->checkSession('post', '', false)) != '') {
			$post_errors[] = [
				[
					'error_' . $sc_error,
					'',
				],
			];
		}

		if ($this->require_verification()) {
			$verificationOptions = [
				'id' => 'customform',
			];

			if (($verification_errors = Verifier::create($verificationOptions, true)) !== true) {
				foreach ($verification_errors as $verification_error) {
					$post_errors[] = [
						'error_' . $verification_error,
						'',
					];
				}
			}
		}

		return $post_errors;
	}
}
