<?php
session_start();
$_SESSION['user_id'] = null;
header('Location: login.php');