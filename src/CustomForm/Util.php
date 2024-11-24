<?php

namespace CustomForm;

use FilesystemIterator;
use Generator;

class Util
{
	public function decamelize(string $string): string
	{
		return strtolower(preg_replace(['/([a-z0-9])([A-Z])/', '/([^_])([A-Z][a-z])/'], '$1_$2', $string));
	}

	public function find_integrated_classes(string $interface): Generator
	{
		if (count($results = call_integration_hook('integrate_customform_classlist')) > 0)
			foreach ($results as $classlist)
				foreach ($classlist as $fqcn)
					if (class_exists($fqcn) && is_subclass_of($fqcn, $interface, true))
						yield null => $fqcn;
	}

	/**
	 * Similar to array_map, but maps key-value pairs (tuples).
	 *
	 * Applies the callback to the elements of the given iterable.
	 * Original values (and keys) are lost during transformation!
	 *
	 * @param callable $callback This must return a list with two
	 *                           elements; the first one becomes the key
	 *                           and the second one becomes the value.
	 * @param iterable $iterator An iterable to run through $callback.
	 *
	 * @return Generator
	 */
	public function map(callable $callback, iterable $iterator): Generator
	{
		foreach ($iterator as $k => $v)
		{
			[$key, $val] = call_user_func($callback, $v, $k);

			yield $key => $val;
		}
	}

	public function find_classes(FilesystemIterator $iterator, string $ns, string $interface): Generator
	{
		foreach ($iterator as $file_info)
			if (class_exists($fqcn = $ns . $file_info->getBasename('.php')) && is_subclass_of($fqcn, $interface, true))
				yield $file_info->getBasename('.php') => $fqcn;

		yield from $this->find_integrated_classes($interface);
	}

	/**
	 * Replaces placeholder variables in a given text with corresponding values from an array.
	 *
	 * This function searches for placeholders in the text formatted as `{{key}}` or `{key}`.
	 * It replaces each placeholder with the corresponding value from the provided array.
	 * If a key is not found in the array, the placeholder key itself is retained in the text.
	 *
	 * @param string $text  The input text containing placeholders to be replaced.
	 * @param array $array  An associative array where keys are placeholder names and values are their replacements.
	 *
	 * @return string The text with placeholders replaced by their corresponding values.
	 */
	public function replaceVars(string $text, array $array): string
	{
		return preg_replace_callback(
			'~{{1,2}\s*?([a-zA-Z0-9\-_.]+)\s*?}{1,2}~',
			fn($matches) => $array[$matches[1]] ?? $matches[1],
			$text
		);
	}

	/**
	 * Adds or updates a meta tag in the global context.
	 *
	 * This function searches for a meta tag with the specified key in the global
	 * `$context['meta_tags']` array. If found, it updates the content. If not found,
	 * it adds a new meta tag with the specified key and value.
	 *
	 * @param string $key   The name of the meta tag to set (e.g., "description").
	 * @param string $value The content of the meta tag.
	 */
	public function setMetaTag(string $key, string $value): void
	{
		global $context;

		$found = false;

		foreach ($context['meta_tags'] as $i => $m)
			if (isset($m['name']) && $m['name'] == $key)
			{
				$context['meta_tags'][$i]['content'] = $value;
				$found = true;
			}

		if (!$found)
			$context['meta_tags'][] = ['name' => $key, 'content' => $value];
	}

	/**
	 * Adds or updates an Open Graph meta property in the global context.
	 *
	 * This function searches for an Open Graph meta property (e.g., `og:title`)
	 * in the global `$context['meta_tags']` array. If found, it updates the content.
	 * If not found, it adds a new meta property with the specified key and value.
	 *
	 * @param string $key   The Open Graph property key (without the "og:" prefix).
	 * @param string $value The content of the Open Graph property.
	 */
	public function setMetaProperty(string $key, string $value): void
	{
		global $context;

		$found = false;

		foreach ($context['meta_tags'] as $i => $m)
			if (isset($m['property']) && $m['property'] == 'og:' . $key)
			{
				$context['meta_tags'][$i]['content'] = $value;
				$found = true;
			}

		if (!$found)
			$context['meta_tags'][] = ['property' => 'og:' . $key, 'content' => $value];
	}
}