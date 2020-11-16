<?php
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

//echo "Database Connection Successfully.\n";
//echo "Delete database " . $connection['dbname'] . " ?\n";
//
//echo "Are you sure you want to do this?  Type 'yes' to continue: ";
//$handle = fopen ("php://stdin","r");
//$line = fgets($handle);
//if(trim($line) != 'yes'){
//	echo "ABORTING!\n";
//	exit;
//}

$mysqli->query('SET foreign_key_checks = 0');
if ($result = $mysqli->query("SHOW TABLES"))
{
	while($row = $result->fetch_array(MYSQLI_NUM))
	{
		echo "Deleting $row[0] \n";
		$mysqli->query('DELETE FROM '.$row[0]);
	}
}

$mysqli->query('SET foreign_key_checks = 1');
$mysqli->close();

echo "Done delete!\n";
