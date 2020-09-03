<?php

include '../../connect.inc.php';
session_start();

if (!isset($_SESSION['id']) || !hasTag($conn, $_SESSION['id'], 'admin') || !isset($_POST['uid']) || !isset($_POST['val'])) {
  header('Location: ./');
} else {
  if ($_POST['field'] == 'rank') {
    $stmt = $conn->prepare("UPDATE `cad_characters` SET `rank` = ? WHERE `name` = ?");
  } else if ($_POST['field'] == 'status') {
    $stmt = $conn->prepare("UPDATE `cad_characters` SET `status` = ? WHERE `name` = ?");
  } else if ($_POST['field'] == 'notes') {
    $stmt = $conn->prepare("UPDATE `cad_characters` SET `notes` = ? WHERE `name` = ?");
  } else if ($_POST['field'] == 'department') {
    $stmt = $conn->prepare("UPDATE `cad_characters` SET `department` = ? WHERE `name` = ?");
  }
  $stmt->bind_param("ss", $_POST['val'], $_POST['uid']);
  $stmt->execute();
  header('Location: ./');
}
