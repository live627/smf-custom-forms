<?php

$finder = PhpCsFixer\Finder::create()
	->in(__DIR__ . '/src')
	->in(__DIR__ . '/tests');

$config = new PhpCsFixer\Config();
return $config->setRules(
	[
		'@PHP71Migration:risky' => true,
		'@PHPUnit60Migration:risky' => true,
		'indentation_type' => true,
		'array_indentation' => true,
		'array_syntax' => ['syntax' => 'short'],
		'blank_line_after_opening_tag' => true,
		'concat_space' => ['spacing' => 'one'],
		'class_attributes_separation' => [
			'elements' =>
				['const' => 'one', 'method' => 'one', 'property' => 'one'],
		],
		'declare_strict_types' => true,
		'increment_style' => ['style' => 'post'],
		'list_syntax' => ['syntax' => 'short'],
		'blank_line_after_namespace' => true,
		'blank_line_before_statement' => [
			'statements' => [
				'case',
				'continue',
				'declare',
				'default',
				'do',
				'for',
				'foreach',
				'if',
				'return',
				'switch',
				'throw',
				'try',
				'while',
				'yield',
				'yield_from',
			],
		],
		'cast_spaces' => true,
		'method_chaining_indentation' => true,
		'modernize_types_casting' => true,
		'multiline_whitespace_before_semicolons' => true,
		'no_superfluous_elseif' => true,
		'no_superfluous_phpdoc_tags' => true,
		'no_useless_else' => true,
		'no_useless_return' => true,
		'ordered_imports' => true,
		'phpdoc_align' => false,
		'phpdoc_order' => true,
		'php_unit_construct' => true,
		'php_unit_dedicate_assert' => true,
		'return_assignment' => true,
		'single_blank_line_at_eof' => true,
		'single_line_comment_style' => true,
		'ternary_to_null_coalescing' => true,
		'void_return' => true,
		'elseif' => true,
		'encoding' => true,
		'line_ending' => true,
		'lowercase_cast' => true,
		'native_constant_invocation' => false,
		'blank_line_after_namespace' => true,
		'blank_line_after_opening_tag' => true,
		'full_opening_tag' => true,
		'no_closing_tag' => true,
		'no_trailing_whitespace' => true,
		'no_trailing_whitespace_in_comment' => true,
		'single_blank_line_at_eof' => false,
		'no_extra_blank_lines' => [
			'tokens' => [
				'break',
				'continue',
				'curly_brace_block',
				'extra',
				'parenthesis_brace_block',
				'square_brace_block',
				'switch',
				'throw',
				'use',
				'use_trait',
			],
		],
		'yoda_style' => ['equal' => false, 'identical' => false, 'less_and_greater' => false],
		'php_unit_test_case_static_method_calls' => [
			'call_type' => 'this',
		],
		'php_unit_method_casing' => true,
		'php_unit_dedicate_assert' => [
			'target' => 'newest',
		],
	]
)
	->setIndent("\t")
	->setLineEnding("\n")
	->setFinder($finder)
	->setUsingCache(true)
	->setRiskyAllowed(true);