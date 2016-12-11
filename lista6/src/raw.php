<?php
require_once("query.php");

$RAW = <<<EOT
<!DOCTYPE html>
<html lang="pl">
  <head>
    <meta charset="UTF-8">
  </head>
  <body>
    {{RAW}}
  </body>
</html>
EOT;

if (isset($_POST["receiver"]) && !empty($_POST["receiver"]) && isset($_POST["login"]) && !empty($_POST["login"])) {
  $db = myDb();
  $query = "SELECT * FROM transferHistory WHERE username = '".$_POST['login']."' AND receiver = '".$_POST['receiver']."'";
  if (!$db->multi_query($query)) {
    echo $db->error;
  }
  $arr = "<h1>Historia przelewów do danego odbiorcy</h1><table>";
  do {
    if ($res = $db->store_result()) {
      $array = ($res->fetch_all(MYSQLI_ASSOC));
      foreach($array as $row) {
        $arr .= "<tr>";
        foreach($row as $element) {
          $arr .= "<td>".$element."</td>";
        }
        $arr .= "</tr>";
      }
    }
  } while ($db->more_results() && $db->next_result());
  $RAW = str_replace("{{RAW}}", $arr, $RAW);
  echo $RAW;
} else if (isset($_POST["login"]) && $_POST["login"] === "") {
  header("location: http://" .$_SERVER["HTTP_HOST"]. "/lista6/src/history.php");
  exit;
}
?>