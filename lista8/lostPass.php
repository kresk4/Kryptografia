<?php
if (!isset($_SESSION)) {
  session_start();
}

$fbSVG = <<<EOT
<div class="icon" id="pass" title="Zapomniałem hasła"><a href="lostPass.php"><svg viewBox="0 0 24 24">
    <path d="M18,8A2,2 0 0,1 20,10V20A2,2 0 0,1 18,22H6C4.89,22 4,21.1 4,20V10A2,2 0 0,1 6,8H15V6A3,3 0 0,0 12,3A3,3 0 0,0 9,6H7A5,5 0 0,1 12,1A5,5 0 0,1 17,6V8H18M12,17A2,2 0 0,0 14,15A2,2 0 0,0 12,13A2,2 0 0,0 10,15A2,2 0 0,0 12,17Z" />
</svg></a></div><div class="icon" id="Google" data-onsuccess="onSignIn" title="Logowanie przez Google"><svg viewBox="0 0 24 24">
    <path d="M21.35,11.1H12.18V13.83H18.69C18.36,17.64 15.19,19.27 12.19,19.27C8.36,19.27 5,16.25 5,12C5,7.9 8.2,4.73 12.2,4.73C15.29,4.73 17.1,6.7 17.1,6.7L19,4.72C19,4.72 16.56,2 12.1,2C6.42,2 2.03,6.8 2.03,12C2.03,17.05 6.16,22 12.25,22C17.6,22 21.5,18.33 21.5,12.91C21.5,11.76 21.35,11.1 21.35,11.1V11.1Z" />
</svg></div><div class="icon" id="Facebook" title="Logowanie przez Facebooka"><svg viewBox="0 0 24 24">
    <path d="M19,4V7H17A1,1 0 0,0 16,8V10H19V13H16V20H13V13H11V10H13V7.5C13,5.56 14.57,4 16.5,4M20,2H4A2,2 0 0,0 2,4V20A2,2 0 0,0 4,22H20A2,2 0 0,0 22,20V4C22,2.89 21.1,2 20,2Z" />
</svg></div>
EOT;

$FORM = <<<EOT
<p>Wpisz swój adres e-mail, aby otrzymać wiadomość dotyczącą możliwości ustawienia nowego hasła do konta:</p>
<form method="post" action="authSendMail.php">
  <input type="text" name="email" placeholder="E-mail" maxlength="30">
  <input type="submit" value="Wyślij">
</form>
EOT;

$SUCC = <<<EOT
<p>Na podany adres e-mail została wysłana wiadomość zawierająca informacje na temat zmiany hasła.</p>
EOT;

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
        <h2>Zapomniałem hasła</h2>
        {{CONTENT}}
      </div>
      {{MSG}}
    </div>
  </body>
</html>
EOT;

$notFound = "<div class='errorMsg'>Podany e-mail jest niepoprawny lub nie znajduje się w bazie danych.</div>";

if (!empty($_SESSION["login"]) && !empty($_SESSION["pass"])) {
  header("location: http://" .$_SERVER["HTTP_HOST"]. "/index.php");
  exit;
} else if (empty($_SESSION["login"]) || empty($_SESSION["pass"])) {
	if (isset($_COOKIE["exists"]) && $_COOKIE["exists"] == 1) {
		$PAGE = str_replace("{{CONTENT}}", $SUCC, $PAGE);
    setcookie("exists", "", time()-1);
	} else if (isset($_COOKIE["notExists"]) && $_COOKIE["notExists"] == 1) {
    $PAGE = str_replace("{{MSG}}", $notFound, $PAGE);
    setcookie("notExists", "", time()-1);
  }
  $PAGE = str_replace("{{CONTENT}}", $FORM, $PAGE);
  $PAGE = str_replace("{{MSG}}", "", $PAGE);

	echo $PAGE;
}
?>