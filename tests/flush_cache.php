<?php
require('bootstrap.php');

$dorm = new Dorm('config.xml');
$dorm->flushCache();