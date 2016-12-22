<?php
if (!isset($_SESSION)) session_start();

require_once("page.php");

$PAGE = newPage("Strona główna", "");

if (!empty($_SESSION["login"]) && !empty($_SESSION["pass"])) {
	echo $PAGE;
} else if (empty($_SESSION["login"]) || empty($_SESSION["pass"])) {
	session_unset();
	session_destroy();
  header("location: http://" .$_SERVER["HTTP_HOST"]. "/lista7/login.php");
	exit;
}

?>
