<?php

  include '../../connect.inc.php';
  session_start();

  if (!isset($_SESSION['id']) || !hasTag($conn, $_SESSION['id'], 'admin')) {
    header('Location: ../');
  } else {
    $stmt = $conn->prepare("SELECT `id`, `userID` FROM `cad_characters` WHERE `name`=?");
    $stmt->bind_param("s", $_POST['uid']);
    $stmt->execute();
    $result = $stmt->get_result();
    $char = $result->fetch_assoc();

    $stmt = $conn->prepare("SELECT `characterIDs`, `id` FROM `cad_users` WHERE `id`=?");
    $stmt->bind_param("i", $char['userID']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $charIDs = explode('|', $user['characterIDs']);
    array_splice($charIDs, array_search($char['id'], $charIDs));
    $charIDs = join('|', $charIDs);

    $stmt = $conn->prepare("DELETE FROM `cad_characters` WHERE `name`=?;");
    $stmt->bind_param("s", $_POST['uid']);
    $stmt->execute();

    $stmt = $conn->prepare("UPDATE `cad_users` SET `characterIDs` = ? WHERE `id` = ?");
    $stmt->bind_param("si", $charIDs, $user['id']);
    $stmt->execute();

    header('Location: ./');
  }
