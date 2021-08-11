<?php
require 'functions.php';
$action = new Action();
echo $action->status_get(2)->title;
echo "<br>";
echo $action->request_status_counter(2);
?>