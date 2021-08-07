<?
// delete all session
session_start();
unset($_SESSION['admin_id']);
unset($_SESSION['admin_access']);
// bye bye :)
header('location: index.php');
?>