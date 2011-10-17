--TEST--
Test different error handling modes
--SKIPIF--
<?php require_once(dirname(__FILE__) . '/skipif.inc'); ?>
--FILE--
<?php

require_once(dirname(__FILE__) . '/config.inc');

$db = new PDO($dsn);

pdo_cassandra_init ($db, $keyspace);

echo "-- SILENT -- " . PHP_EOL;
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
$db->exec ("CREATE KEYSPACE $keyspace with strategy_class = 'SimpleStrategy' and strategy_options:replication_factor=1;");
var_dump ($db->errorInfo ());
echo "-- SILENT -- " . PHP_EOL;

echo "-- WARNING -- " . PHP_EOL;
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
$db->exec ("CREATE KEYSPACE $keyspace with strategy_class = 'SimpleStrategy' and strategy_options:replication_factor=1;");
echo "-- WARNING -- " . PHP_EOL;

echo "-- EXCEPTION -- " . PHP_EOL;
try {
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->exec ("CREATE KEYSPACE $keyspace with strategy_class = 'SimpleStrategy' and strategy_options:replication_factor=1;");
} catch (PDOException $e) {
	echo $e->getMessage () . PHP_EOL;
}
echo "-- EXCEPTION -- " . PHP_EOL;

pdo_cassandra_done ($db, $keyspace);

echo "OK";

?>
--EXPECTF--
-- SILENT -- 
array(3) {
  [0]=>
  string(5) "HY000"
  [1]=>
  int(2)
  [2]=>
  string(48) "Keyspace names must be case-insensitively unique"
}
-- SILENT -- 
-- WARNING -- 

Warning: PDO::exec(): CQLSTATE[HY000] [2] Keyspace names must be case-insensitively unique in /home/gasol/code/php-pdo_cassandra/tests/013-errorhandling.php on line 17
-- WARNING -- 
-- EXCEPTION -- 
CQLSTATE[HY000] [2] Keyspace names must be case-insensitively unique
-- EXCEPTION -- 
OK
