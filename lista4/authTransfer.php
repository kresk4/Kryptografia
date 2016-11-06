<?php
session_start();
require_once("query.php");

if (empty($_POST["receiver"]) || empty($_POST["id"]) || empty($_POST["sum"]) || empty($_POST["address"]) || empty($_POST["title"])) {
  echo 0;
} else {
	sendTransfer($_POST["receiver"], $_POST["id"], $_POST["address"], $_POST["title"], $_POST["sum"]);
  echo 1;
}

?>