<?php
	require_once("query.php");
	if (empty($_POST["id"])) {
		echo 0;
	} else {
		confirmTransfer($_POST["id"]);
		setcookie("confirm", "1", time()+10);
		echo 1;
	}
?>