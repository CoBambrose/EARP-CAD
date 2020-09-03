<?php

  include '../../connect.inc.php';
  session_start();

  if (!isset($_SESSION['id']) || !hasTag($conn, $_SESSION['id'], 'admin') ) {
    header('Location: ../');
  } else {
    $stmt = $conn->prepare("SELECT * FROM `cad_users` WHERE `uid`=?");
    $stmt->bind_param("s", $_POST['uid']);
    $stmt->execute();
    $results = $stmt->get_result();
    if (mysqli_num_rows($results) < 1) {
      header('Location: ./');
    }
    $results = $results->fetch_assoc();
    if (hasTag($conn, $results['id'], 'admin') && !hasTag($conn, $_SESSION['id'], 'super')) {
      header('Location: ./');
    } else {
      $_SESSION['id'] = $results['id'];
      header('Location: ../');
    }
  }
