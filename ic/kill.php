<?php
  include '../connect.inc.php';
  session_start();

  if (!isset($_SESSION['id']) || !isset($_SESSION['c'])) {
    header('Location: ./');
  } else {
    $stmt = $conn->prepare("UPDATE `cad_characters` SET `status` = 'deceased' WHERE `id` = ?");
    $stmt->bind_param("i", $_SESSION['c']);
    $stmt->execute();
    header('Location: ./');
  }
