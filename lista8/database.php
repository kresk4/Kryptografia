<?php

function myDb() {
  $mysqli = new mysqli("localhost", "root", "radek2509", "crypto");
  if (!$mysqli->connect_errno) {
    $mysqli->set_charset("utf8");
    return $mysqli;
  } else {
    die("Connection failed: " . $mysqli->connect_error);
  }
}

?>