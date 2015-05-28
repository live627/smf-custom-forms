<?php

// Version: 1.0: Field.php
namespace CustomForms\Services;

if (!defined('SMF')) {
	die('Hacking attempt...');
}

/**
 * @package CustomForms
 * @since 1.0
 */

class Field extends \ModHelper\BitwiseFlag

	const FLAG_ACTIVE = 1;
	const FLAG_BBC_ENABLED = 2;
	const FLAG_SEARCHABLE = 4;
	const FLAG_ADMIN = 8;

	public function isBanned()
	{
		return $this->isFlagSet(self::FLAG_ACTIVE);
	}

	public function isSilenced()
	{
		return $this->isFlagSet(self::FLAG_BBC_ENABLED);
	}

	public function isMember()
	{
		return $this->isFlagSet(self::FLAG_SEARCHABLE);
	}

	public function isAdmin()
	{
		return $this->isFlagSet(self::FLAG_ADMIN);
	}

	public function setWhispeer($value)
	{
		$this->setFlag(self::FLAG_ACTIVE, $value);
	}

	public function setSilenced($value)
	{
		$this->setFlag(self::FLAG_BBC_ENABLED, $value);
	}

	public function setMember($value)
	{
		$this->setFlag(self::FLAG_SEARCHABLE, $value);
	}

	public function setAdmin($value)
	{
		$this->setFlag(self::FLAG_ADMIN, $value);
	}

	public function __toString()
	{
		return 'Fields [' .
			($this->isBanned() ? 'ACTIVE' : '') .
			($this->isSilenced() ? ' BBC_ENABLED' : '') .
			($this->isMember() ? ' SEARCHABLE' : '') .
			($this->isAdmin() ? ' ADMIN' : '') .
		']';
	}
}

?>