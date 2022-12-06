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
}