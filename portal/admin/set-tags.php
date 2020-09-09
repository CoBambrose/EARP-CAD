<?php

  include '../../connect.inc.php';
  session_start();

  if (!isset($_SESSION['id']) || !hasTag($conn, $_SESSION['id'], 'admin') ) {
    header('Location: ../');
  } else {
    $tags = explode('|', $_POST['tags']);
    if ($tags[0] == '') {
      array_splice($tags, 0, 1);
    }

    if (isset($_POST['super'])) {
      array_push($tags, 'super');
    } if (isset($_POST['admin'])) {
      array_push($tags, 'admin');
    } if (isset($_POST['supporter'])) {
      array_push($tags, 'supporter');
    } if (isset($_POST['dispatcher'])) {
      array_push($tags, 'dispatcher');
    } if (isset($_POST['beta-tester'])) {
      array_push($tags, 'beta-tester');
    }
    $tags = join('|', $tags);
    $stmt = $conn->prepare("UPDATE `cad_users` SET `tags` = ? WHERE `uid` = ?");
    $stmt->bind_param("ss", $tags, $_POST['uid']);
    $stmt->execute();
    header('Location: ./');
  }
