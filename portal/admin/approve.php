<?php

  include '../../connect.inc.php';
  session_start();

  if (!isset($_SESSION['id']) || !hasTag($conn, $_SESSION['id'], 'admin') || !isset($_GET['id']) || !isset($_GET['a'])) {
    header('Location: ../');
    // echo '1';
  } else if ($_GET['a'] == 0) {
    $stmt = $conn->prepare("DELETE FROM `cad_users` WHERE `id`=?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    header('Location: ./');
    // echo '2';
  } else if ($_GET['a'] == 1) {
    $stmt = $conn->prepare("UPDATE `cad_users` SET `approved` = 1 WHERE `id`=?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    header('Location: ./');
    // echo '3';
  } else if ($_GET['a'] == 2) {
    $stmt = $conn->prepare("UPDATE `cad_users` SET `approved` = 0 WHERE `uid`=?");
    $stmt->bind_param("s", $_GET['id']);
    $stmt->execute();
    header('Location: ./');
    // echo '4';
  }
