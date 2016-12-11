<?php
if (!isset($_SESSION)) session_start();
require_once("database.php");

function getPassword($login) {
  $q = "SELECT pass FROM users WHERE login = ?";
  $answer = "";
  $db = myDb();
  $stmt = $db->prepare($q);
  $stmt->bind_param("s", $login);
  $stmt->execute();
  $stmt->bind_result($pass);
  if ($stmt->fetch()) {
    $answer = $pass;
  }
  $stmt->close();
  $db->close();
  return $answer;
}

function confirmTransfer($id) {
  $q = "UPDATE transferHistory SET confirm = 1 WHERE id = ?";
  $answer = "";
  $db = myDb();
  $stmt = $db->prepare($q);
  $stmt->bind_param("s", $id);
  $stmt->execute();
  $stmt->close();
  $db->close();
}

function rejectTransfer($id) {
  $q = "UPDATE transferHistory SET confirm = -1 WHERE id = ?";
  $answer = "";
  $db = myDb();
  $stmt = $db->prepare($q);
  $stmt->bind_param("s", $id);
  $stmt->execute();
  $stmt->close();
  $db->close();
}

function getAllUsersTransferHistory() {
  $q = "SELECT * FROM transferHistory WHERE confirm = 1 ORDER BY transferDate DESC";
  $answer = "";
  $db = myDb();
  $stmt = $db->query($q);
  if ($stmt->num_rows > 0) {
    while ($row = $stmt->fetch_assoc()) {
      $answer[] = $row;
    }
  }
  $stmt->close();
  $db->close();
  return $answer;
}

function getTransferHistory($login) {
  $q = "SELECT * FROM transferHistory WHERE username = ? ORDER BY transferDate DESC";
  $answer = "";
  $db = myDb();
  $stmt = $db->prepare($q);
  $stmt->bind_param("s", $login);
  $stmt->execute();
  $arr = $stmt->get_result();
  if ($arr->num_rows > 0) {
    while ($row = $arr->fetch_assoc()) {
      $answer[] = $row;
    }
  }
  $stmt->close();
  $db->close();
  return $answer;
}

function getNotConfirmed() {
  $q = "SELECT * FROM transferHistory WHERE confirm = 0 ORDER BY transferDate DESC";
  $answer = "";
  $db = myDb();
  $stmt = $db->query($q);
  if ($stmt->num_rows > 0) {
    while ($row = $stmt->fetch_assoc()) {
      $answer[] = $row;
    }
  }
  $stmt->close();
  $db->close();
  return $answer;
}

function sendTransfer($receiver, $id, $address, $title, $sum, $username) {
  $db = myDb();
  $numberOfIds = 0;
  $q = "SELECT COUNT(*) FROM transferHistory";
  $stmt = $db->prepare($q);
  $stmt->execute();
  $stmt->bind_result($num);
  if ($stmt->fetch()) {
    $numberOfIds = $num;
  }
  $stmt->close();
  $q = "INSERT INTO transferHistory (receiver, accountId, address, title, transferDate, sum, id, username) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
  $answer = "";
  $stmt = $db->prepare($q);
  $stmt->bind_param("ssssssss", $receiver, $id, $address, $title, date('Y-m-d G:i:s'), $sum, $numberOfIds, $username);
  $stmt->execute();
  $stmt->close();
  $db->close();
}

if (isset($_SESSION["godMode"]) && $_SESSION["godMode"] == 1 && isset($_GET["action"]) && $_GET["action"] == "confirm") {
  $arr = getNotConfirmed();
  foreach ($arr as $row) {
    confirmTransfer($row["id"]);
  }
}

?>