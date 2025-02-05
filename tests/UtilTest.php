<?php

declare(strict_types=1);

use CustomForm\Util;

class UtilTest extends PHPUnit\Framework\TestCase
{
	private Util $util;

	protected function setUp(): void
	{
		global $context;

		// Initialize a mock global context.
		$context = [
			'meta_tags' => [],
		];

		$this->util = new Util();
	}

	/**
	 * @dataProvider decamelizeProvider
	 */
	public function testDecamelize(string $input, string $expected): void
	{
		$this->assertEquals($expected, $this->util->decamelize($input));
	}

	public function decamelizeProvider(): array
	{
		return [
			['helloWorld', 'hello_world'],
			['TestCaseString', 'test_case_string'],
			['example123', 'example123'],
			['JSONParser', 'json_parser'],
		];
	}

	/**
	 * @dataProvider replaceProvider
	 */
	public function testReplace(string $test, string $expected): void
	{
		$result = $this->util->replaceVars($test, []);
		$this->assertSame($expected, $result);
	}

	public function replaceProvider(): array
	{
		return [
			['abc{{def}}ghi', 'abcdefghi'],
			['abc{{def }}ghi', 'abcdefghi'],
			['abc{{def  }}ghi', 'abcdefghi'],
			['abc{{ def}}ghi', 'abcdefghi'],
			['abc{{  def}}ghi', 'abcdefghi'],
			['abc{{ def }}ghi', 'abcdefghi'],
			['abc{{  def  }}ghi', 'abcdefghi'],
			['abc{def}ghi', 'abcdefghi'],
			['abc{def }ghi', 'abcdefghi'],
			['abc{def  }ghi', 'abcdefghi'],
			['abc{ def}ghi', 'abcdefghi'],
			['abc{  def}ghi', 'abcdefghi'],
			['abc{ def }ghi', 'abcdefghi'],
			['abc{  def  }ghi', 'abcdefghi'],
		];
	}

	public function testReplaceVar(): void
	{
		$this->assertSame(
			'Hello world',
			$this->util->replaceVars(
				'Hello {{ term }}',
				['term' => 'world'],
			),
		);
	}

	/**
	 * @dataProvider replaceVarsProvider
	 */
	public function testReplaceVars(string $template, array $variables, string $expected): void
	{
		$this->assertEquals($expected, $this->util->replaceVars($template, $variables));
	}

	public function replaceVarsProvider(): array
	{
		return [
			['Hello {{name}}, welcome to {{place}}!', ['name' => 'John', 'place' => 'Earth'], 'Hello John, welcome to Earth!'],
			['Hello {{name}}, welcome to {{unknown}}!', ['name' => 'John'], 'Hello John, welcome to unknown!'],
			['{{greeting}} {{name}}!', ['greeting' => 'Hi', 'name' => 'Jane'], 'Hi Jane!'],
			['{{missing}} value', [], 'missing value'],
		];
	}

	/**
	 * @test
	 */
	public function findClassesFindsClassesImplementingInterface(): void
	{
		$result = $this->util->find_classes(
			new \GlobIterator(
				__DIR__ . '/EnvisionPortal/Modules/*.php',
				\FilesystemIterator::SKIP_DOTS,
			),
			'CustomForm\Fields\\',
			CustomForm\FieldInterface::class,
		);

		$this->assertIsIterable($result);
		$this->assertContainsOnlyInstancesOf(CustomForm\FieldInterface::class, $result);
	}

	public function mapDataProvider(): array
	{
		return [
			'basic_transformation' => [
				[1, 2, 3], // Input
				fn($value, $key) => [$key, $value * 2], // Callback
				[0 => 2, 1 => 4, 2 => 6], // Expected output
			],
			'key_transformation' => [
				['a' => 1, 'b' => 2],
				fn($value, $key) => [strtoupper($key), $value + 10],
				['A' => 11, 'B' => 12],
			],
			'empty_input' => [
				[],
				fn($value, $key) => [$key, $value * 2],
				[],
			],
			'complex_transformation' => [
				['x' => [1, 2], 'y' => [3, 4]],
				fn($value, $key) => [$key . '_transformed', array_sum($value)],
				['x_transformed' => 3, 'y_transformed' => 7],
			],
		];
	}

	/**
	 * @dataProvider mapDataProvider
	 */
	public function testMap(array $input, callable $callback, array $expected): void
	{
		$result = $this->util->map($callback, $input);
		$this->assertIsIterable($result);
		$result = iterator_to_array($result);

		$this->assertSame($expected, $result);
	}
}
