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
