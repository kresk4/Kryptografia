<?php
session_start();
session_unset();
session_destroy();

setcookie("logSucc", "1", time()+1);
header("location: http://" .$_SERVER["HTTP_HOST"]. "/lista4/login.php");
exit;
?>
