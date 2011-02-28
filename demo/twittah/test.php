<?php
require('bootstrap.php');


$user = new User();
$user->username = 'test';
$user->password = 'test';
$user->email = 'test';

$twit = new Twit();
$twit->message = 'test twit !!!';
$user->addTwit($twit);

$dorm->save($user);