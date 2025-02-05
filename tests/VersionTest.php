<?php

declare(strict_types=1);

use CustomForm\Version;
use PHPUnit\Framework\TestCase;

class VersionTest extends TestCase
{
	public function testGetters(): void
	{
		$version = new Version(
			major: 2,
			minor: 3,
			patch: 4,
			preReleaseType: 'alpha',
			preReleaseMajor: 1,
			preReleaseMinor: 2,
		);

		$this->assertSame(2, $version->getMajor());
		$this->assertSame(3, $version->getMinor());
		$this->assertSame(4, $version->getPatch());
		$this->assertSame('alpha', $version->getPreReleaseType());
		$this->assertSame(1, $version->getPreReleaseMajor());
		$this->assertSame(2, $version->getPreReleaseMinor());
	}

	public function testGettersWithDefaults(): void
	{
		$version = new Version(1);

		$this->assertSame(1, $version->getMajor());
		$this->assertSame(0, $version->getMinor());
		$this->assertSame(0, $version->getPatch());
		$this->assertEquals('stable', $version->getPreReleaseType());
		$this->assertSame(0, $version->getPreReleaseMajor());
		$this->assertSame(0, $version->getPreReleaseMinor());
	}

	public function testJsonSerialization(): void
	{
		$version = new Version(
			major: 2,
			minor: 3,
			patch: 4,
			preReleaseType: 'alpha',
			preReleaseMajor: 1,
			preReleaseMinor: 2,
		);
		$this->assertInstanceOf(JsonSerializable::class, $version);

		$expectedJson = json_encode([
			'major' => 2,
			'minor' => 3,
			'patch' => 4,
			'preReleaseType' => 'alpha',
			'preReleaseMajor' => 1,
			'preReleaseMinor' => 2,
		]);
		$this->assertJsonStringEqualsJsonString($expectedJson, json_encode($version));
	}

	/**
	 * Data provider for testCompareTo.
	 */
	public function compareToDataProvider(): array
	{
		return [
			'major version higher' => ['2.0.0', '1.9.9'],
			'minor version higher' => ['1.5.0', '1.4.9'],
			'patch version higher' => ['1.0.1', '1.0.0'],
			'pre-release type comparison (alpha < beta)' => ['1.0.0-beta', '1.0.0-alpha'],
			'pre-release version higher' => ['1.0.0-beta.2', '1.0.0-beta.1'],
			'stable is higher than pre-release' => ['1.0.0', '1.0.0-beta'],
			'stable with higher patch is higher than pre-release' => ['1.0.1', '1.0.0-beta'],
			'major with pre-release' => ['2.0.0-alpha', '1.9.9'],
			'higher major over dev' => ['1.1.0', '1.0.0-dev'],
			'stable higher than dev' => ['1.0.0', '1.0.0-dev'],
			'pre-release version comparison' => ['1.0.0-beta.2', '1.0.0-beta'],
			'higher dev version' => ['1.0.0-dev.2', '1.0.0-dev.1'],
			'different types with dev' => ['1.0.0-beta', '1.0.0-dev'],
			'higher major with dev' => ['2.0.0-dev', '1.9.9'],
			'major version with pre-release higher' => ['2.0.0-alpha', '1.9.9'],
			'higher pre-release major version' => ['2.0.0-alpha.2', '2.0.0-alpha.1'],
			'stable vs pre-release with higher minor' => ['1.2.0', '1.1.9-beta'],
			'stable vs higher dev minor' => ['1.2.0', '1.1.9-dev'],
			'higher pre-release patch' => ['1.0.0-beta.1.2', '1.0.0-beta.1.1'],
			'multiple levels with stable dominance' => ['1.0.0', '0.9.9-alpha.9'],
			'higher patch vs alpha' => ['1.0.1', '1.0.0-alpha'],
		];
	}

	/**
	 * Test Version::compareTo with extensive cases.
	 *
	 * @dataProvider compareToDataProvider
	 */
	public function testCompareTo(string $version1, string $version2): void
	{
		$v1 = Version::fromString($version1);
		$v2 = Version::fromString($version2);

		$this->assertSame(1, $v1->compareTo($v2), 'positive assertion');
		$this->assertSame(0, $v1->compareTo($v1), 'equal assertion');
		$this->assertSame(-1, $v2->compareTo($v1), 'negative assertion');
	}

	/**
	 * Data provider for testIsCompatibleWith.
	 */
	public static function isCompatibleWithDataProvider(): array
	{
		return [
			// Simple exact matches
			['1.0.0', '1.0.0'],
			['1.2.3', '1.2.3'],
			['2.0.0', '2.0.0'],

			// Matching with wildcards
			['1.0.0', '1.*'],
			['1.2.3', '1.*'],
			['2.0.0', '2.*'],
			['1.2.3', '1.2.*'],
			['1.2.3', '1.2.3.*'],

			// Matching ranges
			['1.0.0', '1.0.0-1.0.3'],
			['1.0.2', '1.0.0-1.0.3'],
			['1.0.3', '1.0.0-1.0.3'],
			['1.2.0', '1.2.0-1.2.5'],

			// Pre-release compatibility
			['1.0.0alpha', '1.0.0alpha'],
			['1.0.0beta', '1.0.0beta'],
			['1.0.0rc', '1.0.0rc'],

			// Mixed pre-release and stable ranges
			['1.0.0', '1.0.0-1.0.1'],
			['1.0.0alpha', '1.0dev-1.0.1'],
			['1.0.0beta', '1.0dev-1.0.1'],
			['1.0.0alpha', '1.*'],
			['1.0.0beta', '1.*'],

			// Development versions
			['1.0.0dev', '1.0.0dev'],
			['1.0.0dev', '1.*'],

			// Compatibility with "all"
			['1.0.0', 'all'],
			['2.3.4', 'all'],
			['0.0.1', 'all'],
		];
	}

	/**
	 * Test Version::isCompatibleWith with multiple cases.
	 *
	 * @dataProvider isCompatibleWithDataProvider
	 */
	public function testIsCompatibleWith(string $version, string $range): void
	{
		$v = Version::fromString($version);

		$this->assertTrue($v->isCompatibleWith($range));
	}

	/**
	 * Data provider for testIsCompatibleWith.
	 */
	public static function isNotCompatibleWithDataProvider(): array
	{
		return [
			// Simple non-matches
			['1.0.0', '1.0.1'],
			['1.2.0', '1.3.0'],
			['2.0.0', '3.0.0'],

			// Non-matching wildcards
			['2.0.0', '1.*'],
			['1.3.0', '1.2.*'],
			['1.2.4', '1.2.3.*'],

			// Non-matching ranges
			['1.0.4', '1.0.0-1.0.3'],
			['1.1.0', '1.0.0-1.0.3'],
			['1.2.6', '1.2.0-1.2.5'],

			// Pre-release incompatibilities
			['1.0.0beta', '1.0.0alpha'],
			['1.0.0rc', '1.0.0beta'],
			['1.0.0', '1.0.0rc'],

			// Mixed pre-release and stable ranges
			['1.0.0alpha', '1.0.0-1.0.1'],
			['1.0.0beta', '1.0.0-1.0.1'],

			// Development versions
			['1.0.0dev', '1.0.0'],
			['1.0.0', '1.0.0dev'],
		];
	}

	/**
	 * Test Version::isCompatibleWith with multiple cases.
	 *
	 * @dataProvider isNotCompatibleWithDataProvider
	 */
	public function testIsNotCompatibleWith(string $version, string $range): void
	{
		$v = Version::fromString($version);

		$this->assertFalse($v->isCompatibleWith($range));
	}

	/**
	 * Data provider for testFindHighestCompatible.
	 */
	public function findHighestCompatibleDataProvider(): array
	{
		return [
			['1.4.0', '1.0, 1.2-1.5', '1.5.0'],            // Finds highest in range
			['1.4.0', '1.4.0', '1.4.0'],                   // Exact match
			['1.4.0', '2.0-2.5', false],                   // No match
			['2.0.0', '1.0, 2.*', '2.999.0'],              // Wildcard highest
		];
	}

	/**
	 * Test Version::findHighestCompatible with multiple cases.
	 *
	 * @dataProvider findHighestCompatibleDataProvider
	 */
	public function testFindHighestCompatible(string $currentVersion, string $versions, string|bool $expected): void
	{
		$this->assertSame($expected, Version::findHighestCompatible($versions, $currentVersion));
	}
}
