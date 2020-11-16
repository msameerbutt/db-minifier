<?php
require_once "../vendor/autoload.php";

use Paunin\DatabaseMinifier\DatabaseMinifierAdv;
use Paunin\DatabaseMinifier\Exception\DatabaseMinifierException;

$connections_path = realpath( __DIR__ . '/../configs/connections.json' );
$connections     = json_decode(file_get_contents($connections_path), true);

if (empty($connections)) {
	throw new DatabaseMinifierException('You have no valid connections in your config file!');
}

$relations_path = realpath( __DIR__ . '/../configs/relations-pulse-client.json' );
$relations     = json_decode(file_get_contents($relations_path), true);

if (empty($relations)) {
	throw new DatabaseMinifierException('You have no relation!');
}

//Read config
$configFile = realpath( __DIR__ . '/../configs/minifier-client.json' );
$config     = json_decode(file_get_contents($configFile), true);

// Run
$minifier = new DatabaseMinifierAdv(
	$connections,
	$relations,
	$config['limits'],
	$config['filters']
);

$directiveCounter = 0;
foreach ($config['directives'] as $directive) {
	$params = array_key_exists('arguments', $directive) ? $directive['arguments'] : [];
	if (!array_key_exists('method', $directive)) {
		throw new DatabaseMinifierException('You have no method for directive #'.$directiveCounter);
	}
	call_user_func_array([$minifier, $directive['method']], $params);
	$directiveCounter++;
}

// Importing
echo shell_exec('php run-import_sql_script.php');