<?php
  include '../../connect.inc.php';
  session_start();

  if (!isset($_SESSION['id']) || !hasTag($conn, $_SESSION['id'], 'dispatcher')) {
    header('Location: ../');
  }

  $rq = requestFromUnit($conn, $_GET['unitID']);
  $types = explode('|', $rq['types']);
  array_splice($types, array_search($_GET['type'], $types), 1);

  $stmt = $conn->prepare("UPDATE `cad_requests` SET `types`=? WHERE `unitID`=?");
  $stmt->bind_param("si", join('|', $types), $_GET['unitID']);
  $stmt->execute();
  
  header('Location: ./');
