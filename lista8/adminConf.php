<?php
  if (!isset($_SESSION)) session_start();
  if (empty($_SESSION["login"]) || empty($_SESSION["pass"]) || $_SESSION["godMode"] == 0) {
    header("location: http://" .$_SERVER["HTTP_HOST"]. "/login.php");
    exit;
  }
	require_once("query.php");
	if (empty($_POST["id"])) {
		echo 0;
	} else {
		confirmTransfer($_POST["id"]);
		setcookie("confirm", "1", time()+10);
		echo 1;
	}
?>