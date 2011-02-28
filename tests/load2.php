<?php
require('bootstrap.php');

$dorm = new Dorm('config/metadata.xml');

$publisher = $dorm->getPublisher(array('id' => 1));

echo "{$publisher->name} ({$publisher->location->address} {$publisher->location->city}, {$publisher->location->country})";

print_r($publisher);


