<?php

	// connect to database
	$dbServerName = "localhost";
	$dbUsername = "root";
	$dbPassword = "";
	$dbName = "earp";

	$conn = mysqli_connect($dbServerName, $dbUsername, $dbPassword, $dbName);

	function getCharacters($_conn, $_id) {
		$charIDs = getUser($_conn, $_id)['characterIDs'];
		$characters = explode('|', $charIDs);
		if ($characters[0] == '') {
			array_splice($characters, 0, 1);
		}
		return $characters;
	}

	function getUser($_conn, $_id) {
		$stmt = $_conn->prepare("SELECT * FROM `cad_users` WHERE `id`=?");
		$stmt->bind_param("i", $_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$result_check = mysqli_num_rows($result);
		if ($result_check > 0) {
			while ($row = mysqli_fetch_assoc($result)) {
				$row['tags'] = explode('|', $row['tags']);
				if ($row['tags'][0] == '') {
					array_splice($row['tags'], 0, 1);
				} if (sizeof($row['tags']) == 0) {
					array_push($row['tags'], 'No tags');
				}
				return $row;
			}
		}
	}

	function getCharacter($_conn, $_id) {
		$stmt = $_conn->prepare("SELECT * FROM `cad_characters` WHERE `id`=?");
		$stmt->bind_param("i", $_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$result_check = mysqli_num_rows($result);
		if ($result_check > 0) {
			while ($row = mysqli_fetch_assoc($result)) {
				return $row;
			}
		}
	}

	function getUnit($_conn, $_id) {
		$stmt = $_conn->prepare("SELECT * FROM `cad_units` WHERE `id`=?");
		$stmt->bind_param("i", $_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$result_check = mysqli_num_rows($result);
		if ($result_check > 0) {
			while ($row = mysqli_fetch_assoc($result)) {
				return $row;
			}
		}
	}

	function hasTag($_conn, $_id, $_tag) {
		$user = getUser($_conn, $_id);
		return in_array($_tag, $user['tags']);
	}

	function inUnit($_conn, $_unit, $_char) { // is character in unit
		$unit = getUnit($_conn, $_unit);
		return in_array($_char, explode('|', $unit['staff']));
	}

	function getRequest($_conn, $_id) {
		$stmt = $_conn->prepare("SELECT * FROM `cad_requests` WHERE `id`=?");
		$stmt->bind_param("i", $_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$result_check = mysqli_num_rows($result);
		if ($result_check > 0) {
			while ($row = mysqli_fetch_assoc($result)) {
				return $row;
			}
		}
	}

	function unitFromChar($_conn, $_char) {
	  $stmt = $_conn->prepare("SELECT * FROM `cad_units`");
	  $stmt->execute();
	  $results = $stmt->get_result();
	  while ($row = $results->fetch_assoc()) {
	    if (inUnit($_conn, $row['id'], $_char)) {
				return $row;
	    }
	  }
		return '';
	}

	function requestFromUnit($_conn, $_unit) {
	  $stmt = $_conn->prepare("SELECT * FROM `cad_requests` WHERE `unitID`=?");
		$stmt->bind_param("i", $_unit);
		$stmt->execute();
	  $results = $stmt->get_result();
	  while ($row = $results->fetch_assoc()) {
			return $row;
	  }
		return '';
	}

	$rankEnumeration = [
		'pd' => 'Island Police Department',
		'fd' => 'Island Fire Department',
		'ias' => 'Island Ambulance Service',
		'civ' => 'Citizen of The Island',
		'dispatch' => 'Island Dispatch Service'
	];

  $typeEnumeration = [
    'pd1' => 'Traffic Unit',
    'pd2' => 'Armed Response',
    'fd' => 'Fire Engine',
    'ias' => 'Ambulance',
    'panic' => 'Panic Response'
  ];

// DROP TABLE `cad_users`, `cad_characters`, `cad_units`; CREATE TABLE cad_users( id INT PRIMARY KEY AUTO_INCREMENT, uid VARCHAR(50) NOT NULL, pwd VARCHAR(150) NOT NULL, tags VARCHAR(100) DEFAULT '', approved BOOLEAN DEFAULT false, characterIDs VARCHAR(30) NOT NULL DEFAULT ''); CREATE TABLE cad_characters ( id INT PRIMARY KEY AUTO_INCREMENT, name VARCHAR(40) UNIQUE NOT NULL, dept VARCHAR(10) NOT NULL, rank VARCHAR(40) NOT NULL DEFAULT 'Probationary', bio VARCHAR(1500) NOT NULL, appearance VARCHAR(1500) NOT NULL, status VARCHAR(30) NOT NULL DEFAULT 'alive', notes VARCHAR(1500) NOT NULL DEFAULT '', userID INT ); CREATE TABlE cad_units ( id INT PRIMARY KEY AUTO_INCREMENT, dept VARCHAR(20) NOT NULL DEFAULT 'pd', display VARCHAR(50) NOT NULL UNIQUE, callsign VARCHAR(10) NOT NULL UNIQUE, staff VARCHAR(20) NOT NULL, statusCode INT NOT NULL DEFAULT 1, postal VARCHAR(10) DEFAULT '' ); INSERT INTO `cad_users` (`uid`, `pwd`, `approved`, `tags`) VALUES ('admin1', MD5('password'), 1, 'admin|super|dispatcher'); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statusCode`, `postal`) VALUES ('ias', 'E1', 'Echo 1', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statusCode`, `postal`) VALUES ('ias', 'E12', 'Echo 1-2', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statusCode`, `postal`) VALUES ('ias', 'E2', 'Echo 2', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statusCode`, `postal`) VALUES ('ias', 'E22', 'Echo 2-2', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statusCode`, `postal`) VALUES ('ias', 'E3', 'Echo 3', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statusCode`, `postal`) VALUES ('ias', 'E4', 'Echo 4', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statusCode`, `postal`) VALUES ('pd', 'A1', 'Alpha 1', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statusCode`, `postal`) VALUES ('pd', 'A2', 'Alpha 2', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statusCode`, `postal`) VALUES ('pd', 'WA1', 'Whiskey Alpha 1', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statusCode`, `postal`) VALUES ('pd', 'WA2', 'Whiskey Alpha 2', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statusCode`, `postal`) VALUES ('pd', 'P1', 'Papa 1', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statusCode`, `postal`) VALUES ('pd', 'P2', 'Papa 2', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statusCode`, `postal`) VALUES ('pd', 'WP1', 'Whiskey Papa 1', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statusCode`, `postal`) VALUES ('pd', 'WP2', 'Whiskey Papa 2', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statusCode`, `postal`) VALUES ('pd', 'F1', 'Foxtrot 1', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statusCode`, `postal`) VALUES ('pd', 'F2', 'Foxtrot 2', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statusCode`, `postal`) VALUES ('pd', 'B1', 'Bravo 1', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statusCode`, `postal`) VALUES ('pd', 'B2', 'Bravo 2', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statusCode`, `postal`) VALUES ('pd', 'R1', 'Romeo 1', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statusCode`, `postal`) VALUES ('pd', 'R2', 'Romeo 2', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statusCode`, `postal`) VALUES ('pd', 'G1', 'Griffin 1', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statusCode`, `postal`) VALUES ('pd', 'D1', 'Delta 1', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statusCode`, `postal`) VALUES ('pd', 'D2', 'Delta 2', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statusCode`, `postal`) VALUES ('pd', 'T1', 'Trojan 1', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statusCode`, `postal`) VALUES ('pd', 'T2', 'Trojan 2', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statusCode`, `postal`) VALUES ('fd', 'L1', 'Ladder 1', '', '7', ''); INSERT INTO `cad_units` (`dept`, `callsign`, `display`, `staff`, `statusCode`, `postal`) VALUES ('fd', 'L2', 'Ladder 2', '', '7', '');
// CREATE TABLE cad_requests (
// 	id INT PRIMARY KEY AUTO_INCREMENT,
//  characterID INT NOT NULL,
//  unitID INT,
//  types VARCHAR(30) NOT NULL DEFAULT ''
// )
