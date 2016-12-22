<?php
	require_once("query.php");
	if (empty($_POST["id"])) {
		echo 0;
	} else {
		rejectTransfer($_POST["id"]);
		setcookie("reject", "1", time()+10);
		echo 1;
	}
?>