<?php


require_once __DIR__ . "/app/functions.php";
$action = new Action();
$action->admin_log(2);

// delete all session
session_start();
unset($_SESSION['admin_id']);
unset($_SESSION['admin_access']);

// bye bye :)
$_SESSION['alarm'] = 1;
header('location: login.php');
?>