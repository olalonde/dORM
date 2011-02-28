<?php
//$_GET['user'] = 'oli';
require('bootstrap.php');
$messages = array();

$user = $dorm->getUserCollection('username=:username', array('username' => $_GET['user']));

if (!isset($user[0])) {
    echo 'This user does not exist';exit;
}
$user = $user[0];
$user_id = $dorm->getId($user);
$user_id = $user_id['user_id'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Profile</title>
</head>

<body>
<?php if (count($messages) > 0): ?>
<ul>
    <?php foreach ($messages as $msg): ?>
        <li><?php echo $msg; ?></li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>

<h1><?php echo $user->username; ?></h1>

<p><a href="home.php?follow=<?php echo $user_id; ?>">Follow</a> this user.</p>

<h4>Following</h4>

<ul>
<?php
foreach ($user->following as $usr) {
    echo "<li>$usr->username</li>";
}
?>
</ul>
<h4>Followers</h4>

<ul>
<?php
foreach ($user->followers as $usr) {
    echo "<li>$usr->username</li>";
}
?>
</ul>

<h2>Timeline</h2>

<ul>
<?php
$timeline = new Timeline();
$timeline->addUser($user);

foreach ($timeline->getTwits() as $twit) {
   echo "<li>{$twit->message}</li>";
}
?>
</ul>
</body>
</html>
