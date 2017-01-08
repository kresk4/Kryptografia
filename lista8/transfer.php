<?php
if (!isset($_SESSION)) session_start();

require_once("page.php");

$CONTENT = <<<EOT
  <script>var login = '{{LOGIN}}';</script>
	<script src="js/checkInput.js"></script>
  <script src="js/checkForm.js"></script>
  <form id="transferForm" method="post" action="authTransfer.php">
    <input type="text" name="receiver" placeholder="Nazwa odbiorcy" maxlength="200">
    <input type="text" name="id" placeholder="Numer rachunku bankowego" maxlength="32">
    <input type="text" name="address" placeholder="Adres odbiorcy" maxlength="70">
    <input type="text" name="title" placeholder="Tytuł przelewu" maxlength="70">
    <input type="text" name="sum" placeholder="Kwota" maxlength="40">
 		<div class="specialButton" id="1st">Dalej</div>
 		<div class="specialButton" id="2nd">Wykonaj przelew</div>
  </form>
EOT;

if (!empty($_SESSION["login"]) && !empty($_SESSION["pass"])) {
  $CONTENT = str_replace("{{LOGIN}}", $_SESSION["login"], $CONTENT);
	$PAGE = newPage("Zleć wykonanie przelewu", $CONTENT);
	echo $PAGE;
} else if (empty($_SESSION["login"]) || empty($_SESSION["pass"])) {
	session_unset();
	session_destroy();
  header("location: http://" .$_SERVER["HTTP_HOST"]. "/login.php");
	exit;
}

?>