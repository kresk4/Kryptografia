<?php
if (!isset($_SESSION)) session_start();
require_once("query.php");
require_once("recaptcha/autoload.php");

if (!isset($_POST["login"]) || !isset($_POST["pass"])) {
  header("location: http://" .$_SERVER["HTTP_HOST"]. "/login.php");
  exit;
}

if (empty($_POST["login"]) || empty($_POST["pass"])) {
  setcookie("logWarn", "1", time()+10);
  session_unset();
  session_destroy();
  header("location: http://" .$_SERVER["HTTP_HOST"]. "/login.php");
  exit;
} else if (!empty($_POST["login"]) && !empty($_POST["pass"])) {
  $password = htmlspecialchars($_POST["pass"]);
  $recaptcha = new \ReCaptcha\ReCaptcha("6Lew9g4UAAAAAFXkQajoCRQ5E44n1z3dGTw7fM9Z");
  $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
  $passFromDb = getPassword($_POST["login"]);
  if ($resp->isSuccess() && password_verify($password, $passFromDb)) {
    $_SESSION["login"] = $_POST["login"];
    $_SESSION["pass"] = $password; 
    if ($_SESSION["login"] === "admin") {
      $_SESSION["godMode"] = 1;
    }
    header("location: http://" .$_SERVER["HTTP_HOST"]. "/passAgain.php");
    exit;
  } else if (!$resp->isSuccess()) {
    setcookie("captchaError", "1", time()+10);
    session_unset();
    session_destroy();
    header("location: http://" .$_SERVER["HTTP_HOST"]. "/login.php");
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