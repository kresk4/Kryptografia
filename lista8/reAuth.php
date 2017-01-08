<?php

if (!isset($_SESSION)) session_start();

require_once("query.php");

if (empty($_SESSION["reAuthPass"]) || empty($_SESSION["reAuthLogin"]) || empty($_POST["passTypedAgain"])) {
  setcookie("logWarn", "1", time()+10);
  session_unset();
  session_destroy();
  header("location: http://" .$_SERVER["HTTP_HOST"]. "/login.php");
  exit;
} else {
  $password = htmlspecialchars($_POST["passTypedAgain"]); 
  if (password_verify($password, getPassword($_SESSION["reAuthLogin"]))) {
    $_SESSION["login"] = $_SESSION["reAuthLogin"];
    $_SESSION["pass"] = $_SESSION["reAuthPass"];
    unset($_SESSION["reAuthLogin"]);
    unset($_SESSION["reAuthPass"]);
    header("location: http://" .$_SERVER["HTTP_HOST"]. "/index.php");
    exit;
  } else {
    setcookie("logErr", "1", time()+10);
    session_unset();
    session_destroy();
    header("location: http://" .$_SERVER["HTTP_HOST"]. "/login.php");
    exit;
  }
}

?>