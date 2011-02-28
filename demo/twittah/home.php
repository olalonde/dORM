<?php
require('bootstrap.php');

//$_SESSION['user_id'] = array('user_id' => 1);
//$_GET['unfollow'] = 2;

$messages = array();

if (!isset($_SESSION['user_id'])) {
    echo '<a href="login.php">Login</a> or <a href="register.php">register</a>.';
    exit;
}

$user = $dorm->getUser($_SESSION['user_id']);

$_POST = sanitize($_POST);
$twit = isset($_POST['twit']) && !empty($_POST['twit']) ? $_POST['twit'] : null;

if (isset($twit))
    $user->twit($twit);


if (isset($_GET['unfollow'])) {
    $unfollow = $dorm->getUser($_GET['unfollow']);
    $user->unfollow($unfollow);
}
if (isset($_GET['follow'])) {
    $follow = $dorm->getUser($_GET['follow']);
    if ($follow === $user) $messages[] = 'You can not follow yourself.';
    else $user->follow($follow);
}

if (isset($unfollow) || isset($follow) || isset($twit))
    $dorm->save($user);


$timeline = new Timeline();

$timeline->addUser($user);
foreach ($user->following as $usr)
    $timeline->addUser($usr);

if (isset($twit)) header('Location: home.php');

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

<p>
Welcome <?php echo $user->username; ?> (<a href="logout.php">logout</a>). <a href="user_list.php">Click here</a> to find people to follow.
</p>

<h4>Following</h4>

<ul>
<?php
foreach ($user->following as $usr) {
    $user_id = $dorm->getId($usr);
    $user_id = $user_id['user_id'];
    echo "<li>{$usr->username} (<a href='?unfollow={$user_id}'>unfollow</a>)</li>";
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
foreach ($timeline->getTwits() as $twit) {
   echo "<li><strong>{$twit->user->username}:</strong> {$twit->message}</li>";
}
?>
</ul>

<h2>Post an update</h2>
<form action="" method="post">
    <textarea name="twit"></textarea>
    <input type="submit" value="Send" />
</form>
</body>
</html>
