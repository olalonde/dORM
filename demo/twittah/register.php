<?php
require('bootstrap.php');

$messages = array();

$_POST = sanitize($_POST);

$username = isset($_POST['username']) ? $_POST['username'] : null;
$email = isset($_POST['email']) ? $_POST['email'] : null;
$password = isset($_POST['password']) ? $_POST['password'] : null;

if (!empty($username)
    && !empty($email)
    && !empty($password)) {

    // check if user or email exists
    $users = $dorm->getUserCollection(
        'username=:username OR email=:email'
        , array('username' => $username, 'email' => $email)
    );

    // user or email exists
    if (isset($users)) {
        foreach ($users as $usr) {
            if ($usr->email == $email)
                $messages[] = 'This email is already taken.';
            if ($usr->username == $username)
                $messages[] = 'This username is already taken.';
        }
    }
    // user/email doesn't exist, create user
    else {
        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->password = $password;
        $dorm->save($user);
        $messages[] = 'You are now registered ! <a href="login.php">Login here.</a>';
    }
}
elseif (count($_POST) > 0) {
    $messages[] = 'All fields are required.';
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Register</title>
</head>

<body>

<?php if (count($messages) > 0): ?>
<ul>
    <?php foreach ($messages as $msg): ?>
        <li><?php echo $msg; ?></li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>

<h1>Register</h1>

<form action="" method="post">
    <div>username: <input type="text" name="username" value="<?php echo $username; ?>" /></div>
    <div>email: <input type="text" name="email" value="<?php echo $email; ?>" /></div>
    <div>password: <input type="password" name="password" value="<?php echo $password; ?>" /></div>

    <div><input type="submit" value="Register" /></div>
</form>

<p>Already registered? <a href="login.php">Login.</a></p>

</body>
</html>
