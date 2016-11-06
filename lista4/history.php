<?php
session_start();

require_once("page.php");
require_once("query.php");

$CONTENT = <<<EOT
  {{TABLE}}
EOT;

if (!empty($_SESSION["login"]) && !empty($_SESSION["pass"])) {
  $arr = getTransferHistory();
  if ($arr != "") {
    $table = "<table id='historyContent'><tr><td>Nazwa odbiorcy</td><td>Numer rachunku bankowego</td><td>Adres odbiorcy</td><td>Tytuł przelewu</td><td>Data przelewu</td><td>Kwota</td>";
    foreach ($arr as $row) {
      $table .= "<tr><td>".$row["receiver"]."</td><td>".$row["accountId"]."</td><td>".$row["address"]."</td><td>".$row["title"]."</td><td>".$row["transferDate"]."</td><td>".$row["sum"]."</td></tr>";
    }
    $table .= "</table>";
    $CONTENT = str_replace("{{TABLE}}", $table, $CONTENT);
  } else {
    $CONTENT = str_replace("{{TABLE}}", "", $CONTENT);    
  }
  $PAGE = newPage("Historia potwierdzonych przelewów", $CONTENT);
  echo $PAGE;
} else if (empty($_SESSION["login"]) || empty($_SESSION["pass"])) {
  session_unset();
  session_destroy();
  header("location: http://" .$_SERVER["HTTP_HOST"]. "/lista4/login.php");
  exit;
}

?>
