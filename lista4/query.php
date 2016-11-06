<?php
require_once("database.php");

function getPassword($login) {
  $q = "SELECT pass FROM users WHERE login = ?";
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

function getTransferHistory() {
  $q = "SELECT * FROM transferHistory ORDER BY transferDate DESC";
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

function sendTransfer($receiver, $id, $address, $title, $sum) {
  $q = "INSERT INTO transferHistory (receiver, accountId, address, title, transferDate, sum) VALUES (?, ?, ?, ?, ?, ?)";
  $db = myDb();
  $stmt = $db->prepare($q);
  $stmt->bind_param("ssssss", $receiver, $id, $address, $title, date('Y-m-d G:i:s'), $sum);
  $stmt->execute();
  $stmt->close();
  $db->close();
}

?>