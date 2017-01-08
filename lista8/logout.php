<?php
if (!isset($_SESSION)) session_start();
session_unset();
session_destroy();

setcookie("logSucc", "1", time()+1);
header("location: http://" .$_SERVER["HTTP_HOST"]. "/login.php");
exit;
?>