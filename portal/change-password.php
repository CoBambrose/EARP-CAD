<?php

include '../connect.inc.php';
session_start();

if (!isset($_SESSION['id']) || !isset($_POST['pwd']) || strlen($_POST['pwd']) < 6 || strlen($_POST['pwd'] > 20)) {
  header('Location: ./');
} else {
  $stmt = $conn->prepare("UPDATE `cad_users` SET `pwd` = MD5(?) WHERE `id` = ?");
  $stmt->bind_param("si", $_POST['pwd'], $_SESSION['id']);
  $stmt->execute();
  header('Location: ../');
}
