<?php

$finder = PhpCsFixer\Finder::create()
	->in(__DIR__ . '/src')
	->in(__DIR__ . '/tests');

$config = new PhpCsFixer\Config();
return $config->setRules(
	[
		'@PER-CS2.0' => true,

		// PSR12 overrides.
		'no_closing_tag' => false,
		'no_break_comment' => false,  // A bit buggy with comments.
		'statement_indentation' => false, // A bit buggy with comments.

		// Array notation.
		'array_syntax' => ['syntax' => 'short'],
		'normalize_index_brace' => true,
		'whitespace_after_comma_in_array' => true,

		// Basic.
		'no_trailing_comma_in_singleline' => true,

		// Casing.
		'class_reference_name_casing' => true,

		// Cast notation.
		'cast_spaces' => ['space' => 'single'],

		// Control structure.
		'include' => true,
		'no_superfluous_elseif' => true,
		'no_useless_else' => true,
		'simplified_if_return' => true,
		'trailing_comma_in_multiline' => [
			'after_heredoc' => true,
			'elements' => [
				'arguments',
				'arrays',
				'match',
				'parameters',
			],
		],

		// Function notation.
		'lambda_not_used_import' => true,
		'nullable_type_declaration_for_default_null_value' => true,

		// Import.
		'no_unused_imports' => true,
		'ordered_imports' => [
			'imports_order' => [
				'class',
				'function',
				'const',
			],
			'sort_algorithm' => 'alpha',
		],

		// Language construct.
		'combine_consecutive_issets' => true,
		'combine_consecutive_unsets' => true,
		'nullable_type_declaration' => ['syntax' => 'question_mark'],

		// Namespace notation.
		'no_leading_namespace_whitespace' => true,

		// Operator.
		'concat_space' => ['spacing' => 'one'],
		'operator_linebreak' => [
			'only_booleans' => true,
			'position' => 'beginning',
		],
		'standardize_not_equals' => true,
		'ternary_to_null_coalescing' => true,

		// PHPDoc.
		'phpdoc_indent' => true,
		'phpdoc_line_span' => [
			'const' => 'multi',
			'property' => 'single',
			'method' => 'multi',
		],
		'phpdoc_no_access' => true,
		'phpdoc_no_useless_inheritdoc' => true,
		'phpdoc_order' => [
			'order' => [
				'param',
				'throws',
				'return',
			],
		],
		'phpdoc_no_empty_return' => true,
		'phpdoc_param_order' => true,
		'phpdoc_scalar' => [
			'types' => [
				'boolean',
				'callback',
				'double',
				'integer',
				'real',
				'str',
			],
		],
		'phpdoc_to_comment' => [
			'ignored_tags' => ['todo'],
		],
		'phpdoc_trim' => true,
		'phpdoc_trim_consecutive_blank_line_separation' => true,
		'phpdoc_types' => [
			'groups' => ['alias', 'meta', 'simple'],
		],
		'phpdoc_var_without_name' => true,

		// Return notation.
		'no_useless_return' => true,
		'simplified_null_return' => true,

		// Semicolon.
		'multiline_whitespace_before_semicolons' => true,
		'no_empty_statement' => true,
		'no_singleline_whitespace_before_semicolons' => true,

		// String notation.
		'explicit_string_variable' => true,
		'simple_to_complex_string_variable' => true,
		'single_quote' => true,

		// Whitespace.
		'array_indentation' => true,
		'blank_line_before_statement' => [
			'statements' => [
				'case',
				'declare',
				'default',
				'do',
				'exit',
				'for',
				'foreach',
				'goto',
				'if',
				'include',
				'include_once',
				'require',
				'require_once',
				'return',
				'switch',
				'throw',
				'try',
				'while',
				'yield',
				'yield_from',
			],
		],
		'heredoc_indentation' => ['indentation' => 'start_plus_one'],
		'method_chaining_indentation' => true,
		'no_spaces_around_offset' => [
			'positions' => ['inside', 'outside'],
		],
		'type_declaration_spaces' => [
			'elements' => ['function', 'property'],
		],
	])
	->setIndent("\t")
	->setFinder($finder);

?>