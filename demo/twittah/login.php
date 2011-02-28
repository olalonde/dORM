<?php
require('bootstrap.php');

$messages = array();

$_POST = sanitize($_POST);

$username = isset($_POST['username']) ? $_POST['username'] : null;
$password = isset($_POST['password']) ? $_POST['password'] : null;


$user = $dorm->getUserCollection(
            'username=:username AND password=:password',
            array(
                'username' => $username,
                'password' => $password
            )
        );

// valid login
if (isset($user[0])) {
    $user = $user[0];
    $messages[] = 'You are now logged in as ' . $user->username . '.';
    $_SESSION['user_id'] = $dorm->getId($user);
    header('Location: home.php');
}
elseif(isset($username)) {
    $messages[] = 'Invalid login!';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login</title>
</head>

<body>

<?php if (count($messages) > 0): ?>
<ul>
    <?php foreach ($messages as $msg): ?>
        <li><?php echo $msg; ?></li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>

<h1>Login</h1>

<form action="" method="post">
    <div>username: <input type="text" name="username" /></div>
    <div>password: <input type="password" name="password" /></div>

    <div><input type="submit" value="Login" /></div>
</form>

<p><a href="register.php">Register here.</a></p>

</body>
</html>
