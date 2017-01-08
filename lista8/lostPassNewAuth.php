<?php
  if (!isset($_SESSION)) session_start();
  require_once('query.php');
  if (isset($_SESSION["key"])) {
    $key = $_COOKIE["userKey"];
    setcookie("userKey", "", time()-1);
    if (empty($_POST["login"]) || empty($_POST["email"]) || empty($_POST["password1"]) || empty($_POST["password2"])) {
      setcookie("logWarn", "1", time()+10);
    } else {
      if (userAndMail($_POST["login"], $_POST["email"]) == 1) {
        if ($_SESSION["email"] !== $_POST["email"]) {
          setcookie("mailMismatch", "1", time()+10);
        } else if ($_POST["password1"] !== $_POST["password2"]) {
          setcookie("logErr", "1", time()+10);
        } else {
          if (strlen($_POST["password1"]) <= 8) {
            setcookie("shortPassErr", "1", time()+10);
          } else {
            setPassword($_POST["login"], $_POST["password1"]);
            setcookie("changePassSucc", "1", time()+10);
            session_unset();
            session_destroy();
            header("location: http://" .$_SERVER["HTTP_HOST"]. "/login.php");
            exit;
          }
        }
      } else {
        setcookie("logMailErr", "1", time()+10);
      }
    }
    header("location: http://" .$_SERVER["HTTP_HOST"]. "/lostPassChange.php?k=".$key);
    exit;
  } else {
    session_unset();
    session_destroy();
    header("location: http://" .$_SERVER["HTTP_HOST"]. "/lostPass.php");
    exit;
  }
?>