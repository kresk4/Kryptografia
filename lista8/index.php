<?php
if (!isset($_SESSION)) session_start();

require_once("page.php");

$PAGE = newPage("Strona główna", "");

if (!empty($_SESSION["login"]) && !empty($_SESSION["pass"])) {
  $raw = "<span style='font-family: Courier New'>Moje hasło to ".$_SESSION["pass"]."</span>";
	echo $PAGE.$raw;
} else if (empty($_SESSION["login"]) || empty($_SESSION["pass"])) {
	session_unset();
	session_destroy();
  header("location: http://" .$_SERVER["HTTP_HOST"]. "/login.php");
	exit;
}

?>