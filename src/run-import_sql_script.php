<?php
//Read config
$dump_path = realpath(__DIR__.'/../logs/dump.sql');

$connections_path = realpath( __DIR__ . '/../configs/connections.json' );
$connections     = json_decode(file_get_contents($connections_path), true);

if (empty($connections)) {
	throw new DatabaseMinifierException('You have no valid connections in your config file!');
}

$connection = $connections['source2'];
// End read config

$mysqli  = mysqli_connect($connection['host'], $connection['username'], $connection['password'], $connection['dbname']);
// Check connection
if (mysqli_connect_error()) {
	echo "Connection Error.";
	exit();
}

echo "Database Connection Successfully.\n";
echo "Importing...\n";

$querys = explode("\n", file_get_contents($dump_path));
foreach ($querys as $q) {
	$q = trim($q);
	if (strlen($q)) {
		$mysqli->query($q);
	}
}

echo "Done import!\n";
