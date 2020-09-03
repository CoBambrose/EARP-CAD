<?php

  include '../../connect.inc.php';
  session_start();

  if (!isset($_SESSION['id']) || !hasTag($conn, $_SESSION['id'], 'admin') ) {
    header('Location: ../');
  } else {
    $stmt = $conn->prepare("UPDATE `cad_users` SET `tags` = ? WHERE `uid` = ?");
    $stmt->bind_param("ss", $_POST['tags'], $_POST['uid']);
    $stmt->execute();
    header('Location: ./');
  }
