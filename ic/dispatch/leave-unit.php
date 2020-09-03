<?php
  include '../../connect.inc.php';
  session_start();

  $unit = unitFromChar($conn, $_SESSION['c']);
  $staff = explode('|', $unit['staff']);
  array_splice($staff, array_search($_SESSION['c'], $staff), 1);
  $staff = join('|', $staff);

  $stmt = $conn->prepare("UPDATE `cad_units` SET `staff` = ? WHERE `id` = ?");
  $stmt->bind_param("si", $staff, $unit['id']);
  $stmt->execute();
  header('Location: ../');
