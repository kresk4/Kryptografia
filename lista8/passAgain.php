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
      <div class="panel reauth">
        <h2>Potwierdzenie</h2>
        <div class="request">Proszę, wpisz swoje hasło jeszcze raz.</div>
        <form method="post" action="reAuth.php">
          <input type="password" name="passTypedAgain" placeholder="Hasło" maxlength="24">
          <input type="submit" value="Zaloguj się">
        </form>
      </div>
    </div>
  </body>
</html>
EOT;

if (!empty($_SESSION["login"]) && !empty($_SESSION["pass"])) {
  echo $PAGE;
  $_SESSION["reAuthLogin"] = $_SESSION["login"];
  $_SESSION["reAuthPass"] = $_SESSION["pass"];
  unset($_SESSION["login"]);
  unset($_SESSION["pass"]);
} else {
  session_unset();
  session_destroy();
  header("location: http://" .$_SERVER["HTTP_HOST"]. "/login.php");
  exit;
}

?>