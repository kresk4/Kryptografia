<?php
	if (!isset($_SESSION)) session_start();
	require_once("query.php");

	if (isset($_POST['email']) && !empty($_POST['email'])) {
		$n = ifExists($_POST['email'], "ggl");
		if ($n == 1) {
  		$data = getLoginAndPassword($_POST['email'], "ggl");
  		$_SESSION['login'] = $data['login'];
  		$_SESSION['pass'] = $data['pass'];
		} else {
		  setcookie("gmailErr", "1", time()+1);
  		session_unset();
  		session_destroy();
		}
	} 
?>