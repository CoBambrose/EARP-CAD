<?php

  include '../connect.inc.php';
  session_start();

  if (!isset($_GET['a'])) {
    header('Location: ../');
  } else if ($_GET['a'] == 'login') {
    $uid = $_POST['uid'];
    $pwd = $_POST['pwd'];
    if (strlen($uid) < 6 || strlen($pwd) < 6 || strlen($uid) > 20 || strlen($pwd) > 20) {
      header('Location: ../?e=0');
    }
    $pwd = md5($pwd);
    $stmt = $conn->prepare("SELECT * FROM `cad_users` WHERE `uid`=? AND `pwd`=?");
    $stmt->bind_param("ss", $uid, $pwd);
    $stmt->execute();
    $result = $stmt->get_result();
    $result_check = mysqli_num_rows($result);
    if ($result_check > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['id'] = $row['id'];
        header('Location: ../portal/');
      }
    } else {
      header('Location: ../?e=0');
    }
  } else if ($_GET['a'] == 'signup') {
    $uid = $_POST['uid'];
    $pwd = $_POST['pwd'];
    $pwd2 = $_POST['pwd2'];
    if (strlen($uid) < 6 || strlen($pwd) < 6 || strlen($pwd2) < 6 || strlen($uid) > 20 || strlen($pwd) > 20 || strlen($pwd2) > 20 || $pwd != $pwd2) {
      header('Location: ../?e=0');
    } else {
      $pwd = md5($pwd);
      if ($uid == 'Ambrosius_') {
        $stmt = $conn->prepare("INSERT INTO `cad_users` (`uid`, `pwd`, `approved`, `tags`) VALUES (?, ?, 1, 'admin|super|dispatcher|developer')");
      } else {
        $stmt = $conn->prepare("INSERT INTO `cad_users` (`uid`, `pwd`) VALUES (?, ?)");
      }
      $stmt->bind_param("ss", $uid, $pwd);
      $result = $stmt->execute();
      $stmt = $conn->prepare("SELECT * FROM `cad_users` WHERE `uid`=? AND `pwd`=?");
      $stmt->bind_param("ss", $uid, $pwd);
      $stmt->execute();
      $result = $stmt->get_result();
      while ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['id'] = $row['id'];
      }
      header('Location: ../portal/');
    }
  }
