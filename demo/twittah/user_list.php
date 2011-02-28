<?php
require('bootstrap.php');

$users = $dorm->getUserCollection();
?>
<h3>Users</h3>

<ul>
<?php
foreach ($users as $user) {
    echo "<li><a href='profile.php?user={$user->username}'>$user->username</a></li>";
}
?>
</ul>