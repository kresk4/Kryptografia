<?php
if (!isset($_SESSION)) session_start();

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
    <title>Logowanie</title>
  </head>
  <body>
    <div id="main">
      <div class="panel">
        <h2>Logowanie</h2>
        <form method="post" action="auth.php">
          <input type="text" name="login" placeholder="Login" maxlength="24">
          <input type="password" name="pass" placeholder="Hasło" maxlength="24">
          <input type="submit" value="Zaloguj się">
        </form>
      </div>
      {{MSG}}
    </div>
  </body>
</html>
EOT;

$logWarn = "<div class='warningMsg'>Żadne z pól nie może pozostać puste.</div>";
$logSucc = "<div class='successMsg'>Wylogowanie zakończone pomyślnie.</div>";
$logErr = "<div class='errorMsg'>Nieprawidłowa nazwa użytkownika lub hasło.</div>";

if (empty($_SESSION["login"]) || empty($_SESSION["pass"])) {
  session_unset();
  session_destroy();
  if (!empty($_COOKIE["logWarn"])) {
    $PAGE = str_replace("{{MSG}}", $logWarn, $PAGE);
    setcookie("logWarn", "", time()-1);
  } else if (!empty($_COOKIE["logErr"])) {
    $PAGE = str_replace("{{MSG}}", $logErr, $PAGE);
    setcookie("logErr", "", time()-1);  
  } else if (!empty($_COOKIE["logSucc"])) {
    $PAGE = str_replace("{{MSG}}", $logSucc, $PAGE);
    setcookie("logSucc", "", time()-1);
  } else {
    $PAGE = str_replace("{{MSG}}", "", $PAGE);
  }
  echo $PAGE;
} else {
  header("location: http://" .$_SERVER["HTTP_HOST"]. "/lista6/src/index.php");
  exit;
}

?>