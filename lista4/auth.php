<?php
session_start();
require_once("query.php");

if (!isset($_POST["login"]) || !isset($_POST["pass"])) {
  header("location: http://" .$_SERVER["HTTP_HOST"]. "/lista4/login.php");
  exit;
}

if (empty($_POST["login"]) || empty($_POST["pass"])) {
  setcookie("logWarn", "1", time()+10);
  session_unset();
  session_destroy();
  header("location: http://" .$_SERVER["HTTP_HOST"]. "/lista4/login.php");
  exit;
} else if (!empty($_POST["login"]) && !empty($_POST["pass"])) {
  $passFromDb = getPassword($_POST["login"]);
  if (password_verify($_POST["pass"], $passFromDb)) {
    $_SESSION["login"] = $_POST["login"];
    $_SESSION["pass"] = $_POST["pass"]; 
    header("location: http://" .$_SERVER["HTTP_HOST"]. "/lista4/index.php");
    exit;
  } else {
    setcookie("logErr", "1", time()+10);
    session_unset();
    session_destroy();
    header("location: http://" .$_SERVER["HTTP_HOST"]. "/lista4/login.php");
    exit;
  }
}

?>
