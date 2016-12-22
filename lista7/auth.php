<?php
if (!isset($_SESSION)) session_start();
require_once("query.php");
require_once("recaptcha/autoload.php");

if (!isset($_POST["login"]) || !isset($_POST["pass"])) {
  header("location: http://" .$_SERVER["HTTP_HOST"]. "/lista7/login.php");
  exit;
}

if (empty($_POST["login"]) || empty($_POST["pass"])) {
  setcookie("logWarn", "1", time()+10);
  session_unset();
  session_destroy();
  header("location: http://" .$_SERVER["HTTP_HOST"]. "/lista7/login.php");
  exit;
} else if (!empty($_POST["login"]) && !empty($_POST["pass"])) {
  $recaptcha = new \ReCaptcha\ReCaptcha("6LdA6RMUAAAAAEZNpTxrpVAcqIPOhfMJ_ocKFrmk");
  $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
  $passFromDb = getPassword($_POST["login"]);
  if ($resp->isSuccess() && password_verify($_POST["pass"], $passFromDb)) {
    $_SESSION["login"] = $_POST["login"];
    $_SESSION["pass"] = $_POST["pass"]; 
    if ($_SESSION["login"] === "admin") {
      $_SESSION["godMode"] = 1;
    }
    header("location: http://" .$_SERVER["HTTP_HOST"]. "/lista7/index.php");
    exit;
  } else if (!$resp->isSuccess()) {
    setcookie("captchaError", "1", time()+10);
    session_unset();
    session_destroy();
    header("location: http://" .$_SERVER["HTTP_HOST"]. "/lista7/login.php");
    exit;   
  } else {
    setcookie("logErr", "1", time()+10);
    session_unset();
    session_destroy();
    header("location: http://" .$_SERVER["HTTP_HOST"]. "/lista7/login.php");
    exit;
  }
}

?>
