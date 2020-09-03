<?php

include '../../connect.inc.php';
session_start();

if (!isset($_SESSION['id']) || !isset($_SESSION['c'])) {
  header('Location: ../');
}

$unit = getUnit($conn, $_POST['id']);
$staff = explode('|', $unit['staff']);
array_push($staff, $_SESSION['c']);
if ($staff[0] == '') {
  array_splice($staff, 0, 1);
}
$staff = join('|', $staff);
$stmt = $conn->prepare("UPDATE `cad_units` SET `staff` = ? WHERE `id` = ?");
$stmt->bind_param("si", $staff, $unit['id']);
$stmt->execute();

header('Location: ../');

?>
