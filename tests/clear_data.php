<?php
require('bootstrap.php');

$dorm = new Dorm('config/metadata.xml');

$pdo = $dorm->getConnection();

$sql = 'SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE author;
TRUNCATE book;
TRUNCATE book_2_author;
TRUNCATE book_2_publisher;
TRUNCATE publisher;
TRUNCATE location;';

$sql = explode("\n", $sql);

foreach ($sql as $line) {
    $pdo->exec($line);
}