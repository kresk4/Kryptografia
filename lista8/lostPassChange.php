<?php
  if (!isset($_SESSION)) session_start();

  require_once("query.php");

$PAGE = <<<EOT
<!DOCTYPE html>
<html lang="pl">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/checkInput.js"></script>
  </head>
  <body>
    <div id="main">
      <div class="panel mail">
        <h2>Zmień hasło</h2>
        <p>Wpisz wymagane dane.</p>
        <form method="post" action="lostPassNewAuth.php">
          <input type="text" name="email" placeholder="E-mail" maxlength="30">
          <input type="text" name="login" placeholder="Login" maxlength="24">
          <input type="password" name="password1" placeholder="Hasło" maxlength="24">
          <input type="password" name="password2" placeholder="Potwórz hasło" maxlength="24">
          <input type="submit" value="Zmień hasło">
        </form>
      </div>
      {{MSG}}
    </div>
  </body>
</html>
EOT;
  $logErr = "<div class='errorMsg'>Hasła nie są takie same.</div>";
  $logMailErr = "<div class='errorMsg'>W bazie nie znajduje się użytkownik z podanym adresem e-mail.</div>";
  $shortPassErr = "<div class='errorMsg'>Hasło musi się składać z co najmniej 8 znaków.</div>";
  $mailMismatch = "<div class='errorMsg'>Podany adres e-mail nie jest tym, za pomocą którego zażądano zmiany hasła.</div>";
  $logWarn = "<div class='warningMsg'>Żadne z pól nie może pozostać puste.</div>";
  if (isset($_COOKIE["timeout"]) && isset($_SESSION["key"])) {
    if (isset($_GET["k"]) && password_verify($_GET["k"], $_SESSION["key"])) {
      if (isset($_COOKIE["logWarn"])) {
        setcookie("logWarn", "", time()-1);
        $PAGE = str_replace("{{MSG}}", $logWarn, $PAGE);
      } else if (isset($_COOKIE["logErr"])) {
        setcookie("logErr", "", time()-1);
        $PAGE = str_replace("{{MSG}}", $logErr, $PAGE);
      } else if (isset($_COOKIE["logMailErr"])) {
        setcookie("logMailErr", "", time()-1);
        $PAGE = str_replace("{{MSG}}", $logMailErr, $PAGE);
      } else if (isset($_COOKIE["shortPassErr"])) {
        setcookie("shortPassErr", "", time()-1);
        $PAGE = str_replace("{{MSG}}", $shortPassErr, $PAGE);
      } else if (isset($_COOKIE["mailMismatch"])) {
        setcookie("mailMismatch", "", time()-1);
        $PAGE = str_replace("{{MSG}}", $mailMismatch, $PAGE);
      }
      $PAGE = str_replace("{{MSG}}", "", $PAGE);
      setcookie("userKey", $_GET["k"], time()+300);
      echo $PAGE;
    } else {
      unset($_SESSION["key"]);
      setcookie("timeout", "", time()-1);
      header("location: http://" .$_SERVER["HTTP_HOST"]. "/lostPass.php");
      exit;
    }
  } else {
    if (isset($_SESSION["key"])) {
      unset($_SESSION["key"]);
    }
    setcookie("timeout", "", time()-1);
    header("location: http://" .$_SERVER["HTTP_HOST"]. "/lostPass.php");
    exit;
  }

?>