<?php

function newPage($title, $param) {

$PAGE = <<<EOT
<!DOCTYPE html>
<html lang="pl">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/checkLinks.js"></script>
    <title>{{TITLE}}</title>
  </head>
  <body>
    {{ADMIN_ACTION}} 
    <div id="main">
      {{ADMIN_HEADER}}
      {{PANEL}}
EOT;

$ADMIN_PANEL = <<<EOT
      <div class="panel" id="index">
        <h2>Panel administratora</h2>
        <ul>
          <li><a href="index.php">Strona główna</a></li>
          <li><a href="confirm.php">Zatwierdź przelew</a></li>
          <li><a href="history.php">Historia potwierdzonych przelewów</a></li>
          <li><a href="logout.php">Wyloguj się</a></li>
        </ul>
      </div>
EOT;

$PANEL = <<<EOT
      <div class="panel" id="index">
        <h2>Panel użytkownika</h2>
        <ul>
          <li><a href="index.php">Strona główna</a></li>
          <li><a href="transfer.php">Zleć wykonanie przelewu</a></li>
          <li><a href="history.php">Historia przelewów</a></li>
          <li><a href="logout.php">Wyloguj się</a></li>
        </ul>
      </div>
EOT;

$THIS_PAGE = <<<EOT
<div class="panel" id="{{URI}}Content">
  <h2>{{TITLE}}</h2>
  {{CONTENT}}
</div>
EOT;

$PAGE_END = <<<EOT
    </div>
  </body>
</html>
EOT;

$godModeDiv = "<div class='panel' id='godMode'><h2>GOD MODE</h2></div>";
$confirmSuccess = "<div id='adminActions'>Przelew został potwierdzony.</div>";
$rejectSuccess = "<div id='adminActions'>Przelew został odrzucony.</div>";

  if (isset($_SESSION["godMode"]) && $_SESSION["godMode"] == 1) {
    if (!empty($_COOKIE["confirm"])) {
      $PAGE = str_replace("{{ADMIN_ACTION}}", $confirmSuccess, $PAGE);
      setcookie("confirm", "", time()-10);
    }
    if (!empty($_COOKIE["reject"])) {
      $PAGE = str_replace("{{ADMIN_ACTION}}", $rejectSuccess, $PAGE);
      setcookie("reject", "", time()-10);
    }
    $PAGE = str_replace("{{ADMIN_HEADER}}", $godModeDiv, $PAGE);
    $PAGE = str_replace("{{PANEL}}", $ADMIN_PANEL, $PAGE);
  } else {
    $PAGE = str_replace("{{ADMIN_HEADER}}", "", $PAGE);
    $PAGE = str_replace("{{PANEL}}", $PANEL, $PAGE);
  }
  $PAGE = str_replace("{{ADMIN_ACTION}}", "", $PAGE);
  $PAGE = str_replace("{{TITLE}}", $title, $PAGE);
  if ($param !== "") {
    $THIS_PAGE = str_replace("{{TITLE}}", $title, $THIS_PAGE);
    $THIS_PAGE = str_replace("{{URI}}", substr($_SERVER['REQUEST_URI'], 1, -4), $THIS_PAGE);
    $THIS_PAGE = str_replace("{{CONTENT}}", $param, $THIS_PAGE);
    return $PAGE.$THIS_PAGE.$PAGE_END;
  } else {
    return $PAGE.$PAGE_END;
  }
}

?>