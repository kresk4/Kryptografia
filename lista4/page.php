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
    <div id="main">
      <div class="panel" id="index">
        <h2>Panel użytkownika</h2>
        <ul>
          <li><a href="index.php">Strona główna</a></li>
          <li><a href="transfer.php">Zleć wykonanie przelewu</a></li>
          <li><a href="history.php">Historia potwierdzonych przelewów</a></li>
          <li><a href="logout.php">Wyloguj się</a></li>
        </ul>
      </div>
EOT;

$THIS_PAGE = <<<EOT
<div>
    <div class="panel" id="second">
        <h2>{{TITLE}}</h2>
        {{CONTENT}}
    </div>
</div>
EOT;

$PAGE_END = <<<EOT
    </div>
  </body>
</html>
EOT;

  $PAGE = str_replace("{{TITLE}}", $title, $PAGE);
  if ($param !== "") {
    $THIS_PAGE = str_replace("{{TITLE}}", $title, $THIS_PAGE);
    $THIS_PAGE = str_replace("{{CONTENT}}", $param, $THIS_PAGE);
    return $PAGE.$THIS_PAGE.$PAGE_END;
  } else {
    return $PAGE.$PAGE_END;
  }
}

?>
