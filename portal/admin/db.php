<?php

include '../../connect.inc.php';

$stmt = $conn->prepare("SELECT TABLE_NAME FROM information_schema.tables;");
$stmt->execute();
$result = $stmt->get_result();
$tables = [];
while ($row = $result->fetch_assoc()) {
  array_push($tables, $row['TABLE_NAME']);
}

function runSQL($_conn, $_stmt) {
  $stmt = $_conn->prepare($_stmt);
  echo mysqli_error($_conn);
  $stmt->execute();
}

if (!in_array('cad_units', $tables)) {
  echo "1";
  runSQL($conn, "CREATE TABLE cad_units ( id INT PRIMARY KEY auto_increment, dept VARCHAR(20) NOT NULL DEFAULT 'civ', display VARCHAR(50) NOT NULL UNIQUE, callsign VARCHAR(10) NOT NULL UNIQUE, staff VARCHAR(20) NOT NULL, statusCode INT NOT NULL DEFAULT 1, postal VARCHAR(10) DEFAULT '' );");
  sleep(5);
  runSQL($conn, "INSERT INTO `cad_units`(`dept`, `callsign`, `display`, `staff`, `statusCode`, `postal`) VALUES ('ias', 'E1', 'Echo 1', '', 7, ''), ('ias', 'E12', 'Echo 1-2', '', 7, ''), ('ias', 'E2', 'Echo 2', '', 7, ''), ('ias', 'E22', 'Echo 2-2', '', 7, ''), ('ias', 'E3', 'Echo 3', '', 7, ''), ('ias', 'E4', 'Echo 4', '', 7, ''), ('pd', 'A1', 'Alpha 1', '', 7, ''), ('pd', 'A2', 'Alpha 2', '', 7, ''), ('pd', 'WA1', 'Whiskey Alpha 1', '', 7, ''), ('pd', 'WA2', 'Whiskey Alpha 2', '', 7, ''), ('pd', 'P1', 'Papa 1', '', 7, ''), ('pd', 'P2', 'Papa 2', '', 7, ''), ('pd', 'WP1', 'Whiskey Papa 1', '', 7, ''), ('pd', 'WP2', 'Whiskey Papa 2', '', 7, ''), ('pd', 'F1', 'Foxtrot 1', '', 7, ''), ('pd', 'F2', 'Foxtrot 2', '', 7, ''), ('pd', 'B1', 'Bravo 1', '', 7, ''), ('pd', 'B2', 'Bravo 2', '', 7, ''), ('pd', 'R1', 'Romeo 1', '', 7, ''), ('pd', 'R2', 'Romeo 2', '', 7, ''), ('pd', 'G1', 'Griffin 1', '', 7, ''), ('pd', 'D1', 'Delta 1', '', 7, ''), ('pd', 'D2', 'Delta 2', '', 7, ''), ('pd', 'T1', 'Trojan 1', '', 7, ''), ('pd', 'T2', 'Trojan 2', '', 7, ''), ('fd', 'FD1', 'Ladder 1', '', 7, ''), ('fd', 'FD12', 'Ladder 2', '', 7, ''), ('fd', 'FD2', 'Rescue 1', '', 7, ''), ('fd', 'FD22', 'Rescue 2', '', 7, ''), ('fd', 'FD3', 'FD Command', '', 7, '');");
} if (!in_array('cad_users', $tables)) {
  echo "2";
  runSQL($conn, "CREATE TABLE cad_users( id INT PRIMARY KEY auto_increment, uid VARCHAR(50) NOT NULL, pwd VARCHAR(150) NOT NULL, tags VARCHAR(100) DEFAULT '', approved BOOLEAN DEFAULT false, characterIDs VARCHAR(30) NOT NULL DEFAULT '');");
  runSQL($conn, "INSERT INTO `cad_users` (`uid`, `pwd`, `approved`, `tags`) VALUES ( 'administrator', md5('password'), 1, 'super|admin|dispatcher');");
} if (!in_array('cad_characters', $tables)) {
  echo "3";
  runSQL($conn, "CREATE TABLE cad_characters ( id INT PRIMARY KEY auto_increment, name VARCHAR(40) UNIQUE NOT NULL, dept VARCHAR(10) NOT NULL, rank VARCHAR(40) NOT NULL DEFAULT 'Probationary', bio VARCHAR(1500) NOT NULL, appearance VARCHAR(1500) NOT NULL, status VARCHAR(30) NOT NULL DEFAULT 'alive', notes VARCHAR(1500) NOT NULL DEFAULT '', userID INT );");
} if (!in_array('cad_requests', $tables)) {
  echo "4";
  runSQL($conn, "CREATE TABLE cad_requests ( id INT PRIMARY KEY auto_increment, characterID INT NOT NULL, unitID INT, types VARCHAR(30) NOT NULL DEFAULT '', location VARCHAR(50) NOT NULL DEFAULT '' );");
}

$stmt = $conn->prepare("SELECT uid FROM cad_users;");
$stmt->execute();
$result = $stmt->get_result();
$tables = [];
$adminExists = 0;
while ($row = $result->fetch_assoc()) {
  if ($row['uid'] == 'administrator') {
    $adminExists = 1;
  }
}
if ($adminExists == 0) {
  echo "5";
  runSQL("INSERT INTO `cad_users` (`uid`, `pwd`, `approved`, `tags`) VALUES ( 'administrator', md5('password'), 1, 'super|admin|dispatcher');");
}
