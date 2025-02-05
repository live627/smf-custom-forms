<?php

declare(strict_types=1);

$loader = require './vendor/autoload.php';
$loader->addPsr4('SMF\\', 'vendor/simplemachines/smf/Sources');
if (!defined('SMF_SOFTWARE_YEAR')) {
	define('SMF_SOFTWARE_YEAR', '2025');
}
if (!defined('SMF_VERSION')) {
	define('SMF_VERSION', '3.0 Alpha 2');
}
function call_integration_hook($hook, $parameters = [])
{
	// You're fired!  You're all fired!  Get outta here!
	return [];
}