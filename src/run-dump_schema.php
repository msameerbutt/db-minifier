<?php

require_once "../vendor/autoload.php";

use Ifsnop\Mysqldump as IMysqldump;

$dump_file = __DIR__ . '/../logs/schema_dump.sql';
$ignore_tables_file = __DIR__ . '/../configs/ignore-tables.txt';

$connections_path = realpath( __DIR__ . '/../configs/connections.json' );
$connections      = json_decode( file_get_contents( $connections_path ), true );

if ( empty( $connections ) ) {
	throw new DatabaseMinifierException( 'You have no valid connections in your config file!' );
}

$mdt_path = realpath( $ignore_tables_file );
$mdt      = file_get_contents( $mdt_path );

$connection_root   = $connections['source1'];
$connection_target = $connections['source2'];

$arr_tables = [];
$tables     = explode( "\n", $mdt );
foreach ( $tables as $table ) {
	$table = trim( $table );
	if ( strlen( $table ) ) {
		array_push( $arr_tables, $table );
	}
}

try {
    echo "Dumping...\n";
    $dump = new IMysqldump\Mysqldump(
        "mysql:host={$connection_root['host']};dbname={$connection_root['dbname']}",
        $connection_root['username'],
        $connection_root['password'],
        [
            'exclude-tables' => $arr_tables,
            'no-data' => true,
            'add-drop-table' => true,
        ]
    );
    $dump->start($dump_file);
    echo "Done dump!\n";

    echo "Importing...\n";
    //# MySQL with PDO_MYSQL
    $db = new PDO(
        "mysql:host={$connection_target['host']};dbname={$connection_target['dbname']}",
        $connection_target['username'],
        $connection_target['password']
    );
    $query = file_get_contents($dump_file);
    $db->exec($query);

    echo "Done import!\n";
} catch (Exception $e) {
    echo 'mysqldump-php error: ' . $e->getMessage();
}
