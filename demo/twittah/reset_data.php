<?php
require_once('bootstrap.php');

$pdo = $dorm->getConnection();

$sql = 'SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE user_2_twit;
TRUNCATE user_relationships;
TRUNCATE twit;
TRUNCATE user;';

$sql = explode("\n", $sql);

foreach ($sql as $line) {
    $pdo->exec($line);
}
echo 'Cleared database data.';