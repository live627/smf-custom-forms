<?php

namespace CustomForm;

use Generator;
use IteratorAggregate;

class CachedGenerator implements IteratorAggregate
{
	protected array $cache = [];

	public function __construct(protected $generator = null)
	{
	}

	public function getIterator(): Generator
	{
		foreach ($this->cache as $item)
			yield $item;

		while ($this->generator->valid())
		{
			$this->cache[] = $current = $this->generator->current();
			$this->generator->next();
			yield $current;
		}
	}
}