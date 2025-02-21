<?php

declare(strict_types=1);

namespace CustomForm;

/**
 * Handles version management, comparisons, and compatibility checks.
 * Supports semantic versioning with pre-release and development versions.
 *
 * Examples of version strings supported:
 * - "1.2.3"
 * - "1.2.3-alpha.1"
 * - "2.0.0-beta"
 * - "2.0.0-rc.2"
 * - "3.0.0-dev"
 */
class Version implements \JsonSerializable
{
	/** Major version number. */
	private int $major;

	/** Minor version number. */
	private int $minor;

	/** Patch version number. */
	private int $patch;

	/** Pre-release type (e.g., 'alpha', 'beta', 'rc'). */
	private string $preReleaseType;

	/** Major version number for the pre-release type. */
	private int $preReleaseMajor;

	/** Minor version number for the pre-release type. */
	private int $preReleaseMinor;

	/**
	 * Constructor for the Version class.
	 *
	 * @param int    $major           Major version number.
	 * @param int    $minor           Minor version number.
	 * @param int    $patch           Patch version number.
	 * @param string $preReleaseType  Pre-release type (e.g., 'alpha', 'beta', 'rc').
	 * @param int    $preReleaseMajor Major version for the pre-release type.
	 * @param int    $preReleaseMinor Minor version for the pre-release type.
	 */
	public function __construct(
		int $major,
		int $minor = 0,
		int $patch = 0,
		string $preReleaseType = 'stable',
		int $preReleaseMajor = 0,
		int $preReleaseMinor = 0,
	) {
		$this->major = $major;
		$this->minor = $minor;
		$this->patch = $patch;
		$this->preReleaseType = $preReleaseType;
		$this->preReleaseMajor = $preReleaseMajor;
		$this->preReleaseMinor = $preReleaseMinor;
	}

	/**
	 * Gets the major version number.
	 *
	 * @return int Major version number.
	 */
	public function getMajor(): int
	{
		return $this->major;
	}

	/**
	 * Gets the minor version number.
	 *
	 * @return int Minor version number.
	 */
	public function getMinor(): int
	{
		return $this->minor;
	}

	/**
	 * Gets the patch version number.
	 *
	 * @return int Patch version number.
	 */
	public function getPatch(): int
	{
		return $this->patch;
	}

	/**
	 * Gets the pre-release type.
	 *
	 * @return string|null Pre-release type, or null if not a pre-release.
	 */
	public function getPreReleaseType(): ?string
	{
		return $this->preReleaseType;
	}

	/**
	 * Gets the major version number for the pre-release type.
	 *
	 * @return int Pre-release major version number.
	 */
	public function getPreReleaseMajor(): int
	{
		return $this->preReleaseMajor;
	}

	/**
	 * Gets the minor version number for the pre-release type.
	 *
	 * @return int Pre-release minor version number.
	 */
	public function getPreReleaseMinor(): int
	{
		return $this->preReleaseMinor;
	}

	/**
	 * Parses a version string into a Version object.
	 *
	 * @param string $versionString A semantic version string (e.g., "1.2.3-beta.1").
	 * @return Version A new Version instance parsed from the string.
	 */
	public static function fromString(string $versionString): Version
	{
		preg_match(
			'/^(\d+)(?:\.(\d+))?(?:\.(\d+))?(?:-?(alpha|beta|rc|dev)?\.?(\d*)\.?(\d*)?)?$/i',
			$versionString,
			$matches,
			PREG_UNMATCHED_AS_NULL,
		);

		return new self(
			(int) ($matches[1] ?? 0),
			(int) ($matches[2] ?? 0),
			(int) ($matches[3] ?? 0),
			$matches[4] ?? 'stable',
			(int) ($matches[5] ?? 0),
			(int) ($matches[6] ?? 0),
		);
	}

	/**
	 * Converts the Version object to a string.
	 *
	 * @return string The semantic version string representation of the object.
	 */
	public function __toString(): string
	{
		$version = $this->major . '.' . $this->minor . '.' . $this->patch;

		if ($this->preReleaseType !== 'stable') {
			$version .= '-' . $this->preReleaseType;

			if ($this->preReleaseMajor > 0) {
				$version .= '.' . $this->preReleaseMajor;

				if ($this->preReleaseMinor > 0) {
					$version .= '.' . $this->preReleaseMinor;
				}
			}
		}

		return $version;
	}

	/**
	 * Specify data which should be serialized to JSON.
	 *
	 * @return array An array representation of the version instance.
	 */
	public function jsonSerialize(): array
	{
		return [
			'major' => $this->major,
			'minor' => $this->minor,
			'patch' => $this->patch,
			'preReleaseType' => $this->preReleaseType,
			'preReleaseMajor' => $this->preReleaseMajor,
			'preReleaseMinor' => $this->preReleaseMinor,
		];
	}

	/**
	 * Compares this version to another version.
	 *
	 * @param Version $other The other Version object to compare.
	 * @return int Comparison result:
	 *              -1 if this version is less than the other.
	 *               0 if this version is equal to the other.
	 *               1 if this version is greater than the other.
	 */
	public function compareTo(Version $other): int
	{
		$fields = ['major', 'minor', 'patch'];

		foreach ($fields as $field) {
			if ($this->$field !== $other->$field) {
				return $this->$field <=> $other->$field;
			}
		}

		$typeOrder = ['dev' => -1, 'alpha' => 0, 'beta' => 1, 'rc' => 2, 'stable' => 3];
		$typeComparison = $typeOrder[$this->preReleaseType] <=> $typeOrder[$other->preReleaseType];

		if ($typeComparison !== 0) {
			return $typeComparison;
		}

		if ($this->preReleaseMajor !== $other->preReleaseMajor) {
			return $this->preReleaseMajor <=> $other->preReleaseMajor;
		}

		return $this->preReleaseMinor <=> $other->preReleaseMinor;
	}

	/**
	 * Checks if this version is compatible with a list of version ranges.
	 *
	 * @param string $ranges A comma-separated list of version ranges (e.g., "1.0, 1.2-1.5, 2.*").
	 * @return bool True if this version is compatible with any of the ranges, false otherwise.
	 */
	public function isCompatibleWith(string $ranges): bool
	{
		$ranges = explode(',', str_replace(' ', '', strtolower($ranges)));

		foreach ($ranges as $range) {
			// If 'all' is specified, all versions are considered compatible
			if ($range === 'all') {
				return true;
			}

			if (str_contains($range, '*')) {
				$range = str_replace('*', 'dev', $range) . '-' . str_replace('*', '999', $range);
			}

			if (str_contains($range, '-')) {
				[$min, $max] = explode('-', $range);
				$minVersion = self::fromString($min);
				$maxVersion = self::fromString($max);

				if ($this->compareTo($minVersion) >= 0 && $this->compareTo($maxVersion) <= 0) {
					return true;
				}
			} else {
				$specificVersion = self::fromString($range);

				if ($this->compareTo($specificVersion) === 0) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Finds the highest compatible version from a list of version ranges.
	 *
	 * @param string $ranges A comma-separated list of version ranges (e.g., "1.0, 1.2-1.5, 2.*").
	 * @param string $currentVersion The current version as a string.
	 * @return string|bool The highest compatible version string, or false if none found.
	 */
	public static function findHighestCompatible(string $ranges, string $currentVersion): string|bool
	{
		$current = self::fromString($currentVersion);
		$ranges = explode(',', str_replace(' ', '', strtolower($ranges)));
		$highest = null;

		foreach ($ranges as $range) {
			if (str_contains($range, '*')) {
				$range = str_replace('*', '0', $range) . '-' . str_replace('*', '999', $range);
			}

			if (str_contains($range, '-')) {
				[$min, $max] = explode('-', $range);
				$maxVersion = self::fromString($max);

				if ($current->compareTo(self::fromString($min)) >= 0 && $current->compareTo($maxVersion) <= 0) {
					$highest = $highest && $highest->compareTo($maxVersion) >= 0 ? $highest : $maxVersion;
				}
			} else {
				$specificVersion = self::fromString($range);

				if ($current->compareTo($specificVersion) >= 0) {
					$highest = $highest && $highest->compareTo($specificVersion) >= 0 ? $highest : $specificVersion;
				}
			}
		}

		return $highest ? (string) $highest : false;
	}
}
