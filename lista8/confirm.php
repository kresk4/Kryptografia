<?php
if (!isset($_SESSION)) session_start();

require_once("page.php");
require_once("query.php");

$CONTENT = <<<EOT
  <script src="js/confirm.js"></script>
  {{TABLE}}
EOT;

if (!empty($_SESSION["login"]) && !empty($_SESSION["pass"]) && $_SESSION["godMode"] == 1) {
  $arr = getNotConfirmed();
  if ($arr !== "") {
    $table = "<table><tr><td>Nazwa odbiorcy</td><td>Numer rachunku bankowego</td><td>Adres odbiorcy</td><td>Tytuł przelewu</td><td>Data przelewu</td><td>Kwota</td><td>Akcja</td></tr>";
    foreach ($arr as $row) {
      $table .= "<tr><td>".$row["receiver"]."</td><td>".$row["accountId"]."</td><td>".$row["address"]."</td><td>".$row["title"]."</td><td>".$row["transferDate"]."</td><td>".$row["sum"]."</td><td><a href='javascript:void(0);' id='confirm_".$row["id"]."'>Potwierdź</a><br/><a href='javascript:void(0);' id='reject_".$row["id"]."'>Odrzuć</a></td></tr>";
    }
    $table .= "</table>";
  }
  if ($arr !== "") {
    $CONTENT = str_replace("{{TABLE}}", $table, $CONTENT);
  } else {
    $CONTENT = str_replace("{{TABLE}}", "Wszystkie przelewy zostały zatwierdzone.", $CONTENT);
  }
  $PAGE = newPage("Zatwierdź przelew", $CONTENT);
  echo $PAGE;
} else if ($_SESSION["godMode"] != 1) {
  header("location: http://" .$_SERVER["HTTP_HOST"]. "/login.php");
  exit;
} else if (empty($_SESSION["login"]) || empty($_SESSION["pass"])) {
  session_unset();
  session_destroy();
  header("location: http://" .$_SERVER["HTTP_HOST"]. "/login.php");
  exit;
}

?>