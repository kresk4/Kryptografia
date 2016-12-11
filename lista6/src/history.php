<?php
if (!isset($_SESSION)) session_start();

require_once("page.php");
require_once("query.php");

$CONTENT = <<<EOT
  {{TABLE}}
EOT;

$FORM = <<<EOT
  <form method="post" class="injectionForm" action="raw.php" target="_blank">
    <input type="text" name="login" placeholder="Nazwa użytkownika" maxlength="100">
    <input type="text" name="receiver" placeholder="Nazwa odbiorcy" maxlength="500">
    <input type="submit" value="Generuj dane tekstowe">
  </form>
EOT;

if (!empty($_SESSION["login"]) && !empty($_SESSION["pass"])) {
  if (!empty($_SESSION["godMode"]) && $_SESSION["godMode"] === 1) {
    $arr = getAllUsersTransferHistory();
  } else {
    $arr = getTransferHistory($_SESSION["login"]);
  }
  if ($arr !== "") {
    $table = "<table id='historyContent'><tr><td>Nazwa odbiorcy</td><td>Numer rachunku bankowego</td><td>Adres odbiorcy</td><td>Tytuł przelewu</td><td>Data przelewu</td><td>Kwota</td><td>Status</td></tr>";
    if (empty($_SESSION["godMode"])) {
      $table = $FORM.$table;
    }
    $statusDone = "wykonany";
    $statusNotDone = "niepotwierdzony";
    $statusRejected = "odrzucony";
    foreach ($arr as $row) {
      $table .= "<tr><td>".$row["receiver"]."</td><td>".$row["accountId"]."</td><td>".$row["address"]."</td><td>".$row["title"]."</td><td>".$row["transferDate"]."</td><td>".$row["sum"]."</td><td>{{CONFIRM}}</td></tr>";
      if (!empty($_SESSION["godMode"]) && $_SESSION["godMode"] === 1) {
        $table = str_replace("{{CONFIRM}}", $row["username"], $table);
        $table = str_replace("Status", "Użytkownik", $table);
      }
      if ($row["confirm"] == 1) {
        $table = str_replace("{{CONFIRM}}", $statusDone, $table);
      } else if ($row["confirm"] == 0) {
        $table = str_replace("{{CONFIRM}}", $statusNotDone, $table);
      } else {
        $table = str_replace("{{CONFIRM}}", $statusRejected, $table);
      }
    }
    $table .= "</table>";
    $CONTENT = str_replace("{{TABLE}}", $table, $CONTENT);
  } else {
    $CONTENT = str_replace("{{TABLE}}", "", $CONTENT);
  }
  if (!empty($_SESSION["godMode"]) && $_SESSION["godMode"] === 1) {
    $PAGE = newPage("Historia potwierdzonych przelewów", $CONTENT);
  } else {
    $PAGE = newPage("Historia przelewów", $CONTENT);
  }
  echo $PAGE;
} else if (empty($_SESSION["login"]) || empty($_SESSION["pass"])) {
  session_unset();
  session_destroy();
  header("location: http://" .$_SERVER["HTTP_HOST"]. "/lista6/src/login.php");
  exit;
}

?>
