<?php

require('../bootstrap.php');


$dsn = "mysql://root@localhost/dorm";

$pdo = Dorm_PDO_Registry::get($dsn);

$introspector = Dorm_Database_Introspector::getInstance($pdo);

$database = $introspector->getDatabase('dorm');

echo 'ok';

var_dump($database);