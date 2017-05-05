<?php

require __DIR__."/../vendor/autoload.php";

$linesToBeInserted = 50000;
$numberOfSelect =5;
set_time_limit ( 20000);

// Function for get the difference from 2 microtimes (trovata online al volissimo)
function microtime_diff($message, $start, $end = null)
{
    if (!$end) {
        $end = microtime();
    }

    list($start_usec, $start_sec) = explode(" ", $start);
    list($end_usec, $end_sec) = explode(" ", $end);
    $diff_sec = intval($end_sec) - intval($start_sec);
    $diff_usec = floatval($end_usec) - floatval($start_usec);
    $value = floatval($diff_sec) + $diff_usec;
    echo $message . ' ' . $value;
    echo PHP_EOL.PHP_EOL;
}


$config = new \Doctrine\DBAL\Configuration();
$connectionParams = array(
    'dbname' => 'uuid_test',
    'user' => 'root',
    'password' => 'root',
    'host' => 'uuid_mysql',
    'driver' => 'pdo_mysql'
);

$connection = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);

// Drop and recreate all tables
$connection->beginTransaction();
try{
     $connection->exec("CREATE TABLE IF NOT EXISTS autoincrement_id (id INT NOT NULL AUTO_INCREMENT, PRIMARY KEY(id)); ");
     $connection->exec("CREATE TABLE IF NOT EXISTS unoptimized_uuid (id VARCHAR(40) NOT NULL, PRIMARY KEY(id));");
     $connection->exec("CREATE TABLE IF NOT EXISTS optimized_uuid (id BINARY(16) NOT NULL, PRIMARY KEY(id));");
    $connection->commit();
} catch (\Exception $e){
    $connection->rollBack();
    var_dump("ERROR: {$e->getMessage()}");
}


// Insert n records for each table
$startInsertIdToAutoincrementTable = microtime();
for($i = 0; $i < $linesToBeInserted; $i++) {
    $connection->exec("INSERT INTO autoincrement_id () VALUES (NULL)");
}
microtime_diff("Inserted data into autoincrement_id table in", $startInsertIdToAutoincrementTable);

$unoptimizedUuidList = [];
$startInsertIdToUnoptimizedUuidTable = microtime();
for($i = 0; $i < $linesToBeInserted; $i++) {
    $uuid = new \Damiano\Uuid\UuidOptimized(\Ramsey\Uuid\Uuid::uuid1());
    if($i < $numberOfSelect){
        $unoptimizedUuidList[] = $uuid;
    }
    $stmt = $connection->prepare("INSERT INTO unoptimized_uuid (id) VALUES (:id)");
    $stmt->bindParam(':id',$uuid->optimized());
    $stmt->execute();
}
microtime_diff("Inserted data into unoptimized_uuid table in",  $startInsertIdToUnoptimizedUuidTable);

$optimizedUuidList = [];
$startInsertIdToOptimizedUuidTable = microtime();
for($i = 0; $i < $linesToBeInserted; $i++) {
    $uuid = new \Damiano\Uuid\UuidOptimized(\Ramsey\Uuid\Uuid::uuid1());
    if($i < $numberOfSelect){
        $optimizedUuidList[] = $uuid;
    }
    $stmt = $connection->prepare("INSERT INTO optimized_uuid (id) VALUES (:id)");
    $stmt->bindParam(':id',$uuid->optimizedForPersistence());
    $stmt->execute();
}
$endInsertIdToOptimizedUuidTable = microtime();
microtime_diff("Inserted data into optimized_uuid table in", $startInsertIdToOptimizedUuidTable);









// Select random id
$startSelectRandomIdFromAutoincrementTable = microtime();
foreach (range(1,$numberOfSelect,1) as $id){
    $stmt = $connection->prepare("SELECT * FROM autoincrement_id WHERE id = :id");
    $stmt->bindParam(':id',$id, PDO::PARAM_INT);
    $stmt->execute();
}
microtime_diff("Selected data from autoincrement_id table in",$startSelectRandomIdFromAutoincrementTable);


$startSelectRandomIdFromUnoptimizedUuidTable = microtime();
/** @var Damiano\Uuid\UuidOptimized $uuid */
foreach ($unoptimizedUuidList as $uuid) {
    $stmt = $connection->prepare("SELECT * FROM unoptimized_uuid WHERE id = :id");
    $stmt->bindParam(':id',$uuid->optimized());
    $stmt->execute();
}
microtime_diff("Selected data from unoptimized_uuid table in",$startSelectRandomIdFromUnoptimizedUuidTable);



$startSelectRandomIdFromOptimizedUuidTable = microtime();
/** @var Damiano\Uuid\UuidOptimized $uuid */
foreach ($optimizedUuidList as $uuid) {
    $stmt = $connection->prepare("SELECT * FROM optimized_uuid WHERE id = :id");
    $stmt->bindParam(':id',$uuid->optimizedForPersistence());
    $stmt->execute();
}
microtime_diff("Selected data from optimized_uuid table in",$startSelectRandomIdFromOptimizedUuidTable);









// select * ordered by id desc
$startSelectRandomIdFromAutoincrementTable = microtime();
foreach (range(1,$numberOfSelect,1) as $id){
    $stmt = $connection->prepare("SELECT * FROM autoincrement_id ORDER BY id DESC");
    $stmt->execute();
}
microtime_diff("Selected data from autoincrement_id table in",$startSelectRandomIdFromAutoincrementTable);


$startSelectRandomIdFromUnoptimizedUuidTable = microtime();
/** @var Damiano\Uuid\UuidOptimized $uuid */
foreach ($unoptimizedUuidList as $uuid) {
    $stmt = $connection->prepare("SELECT * FROM unoptimized_uuid ORDER BY id DESC");
    $stmt->execute();
}
microtime_diff("Selected data from unoptimized_uuid table in",$startSelectRandomIdFromUnoptimizedUuidTable);



$startSelectRandomIdFromOptimizedUuidTable = microtime();
/** @var Damiano\Uuid\UuidOptimized $uuid */
foreach ($optimizedUuidList as $uuid) {
    $stmt = $connection->prepare("SELECT * FROM optimized_uuid ORDER BY id DESC");
    $stmt->execute();
}
microtime_diff("Selected data from optimized_uuid table in",$startSelectRandomIdFromOptimizedUuidTable);