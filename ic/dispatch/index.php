<?php

include '../../connect.inc.php';
session_start();

if (!isset($_SESSION['id']) || !hasTag($conn, $_SESSION['id'], 'dispatcher')) {
  header('Location: ../');
}

$view = '';
if (isset($_POST['command'])) {
  $_POST['command'] = explode(' ', $_POST['command']);
  $command = substr($_POST['command'][0], 1);
  if ($command == 's') {
    $callsign = strtoupper($_POST['command'][1]);
    if (sizeof($_POST['command']) == 2) {
      array_push($_POST['command'], '7');
    }
    $status = $_POST['command'][2];
    if ($status < 8 && $status > 0) {
      $stmt = $conn->prepare("UPDATE `cad_units` SET `statusCode` = ? WHERE `callsign` = ?");
      $stmt->bind_param("is", $status, $callsign);
      $stmt->execute();
    }

    if ($status == '7') {
      $stmt = $conn->prepare("UPDATE `cad_units` SET `postal` = '' WHERE `callsign` = ?");
      $stmt->bind_param("s", $callsign);
      $stmt->execute();
    }
  } else if ($command == 'l') {
    $callsign = strtoupper($_POST['command'][1]);
    if (sizeof($_POST['command']) == 2) {
      array_push($_POST['command'], '');
    }
    $postal = $_POST['command'][2];
    $stmt = $conn->prepare("UPDATE `cad_units` SET `postal` = ? WHERE `callsign` = ?");
    $stmt->bind_param("ss", $postal, $callsign);
    $stmt->execute();
  } else if ($command == 'u') {
    $callsign = strtoupper($_POST['command'][1]);
    if (sizeof($_POST['command']) == 4) {
      $status = $_POST['command'][2];
      $postal = $_POST['command'][3];
      if ($status < 8 && $status > 0) {
        $stmt = $conn->prepare("UPDATE `cad_units` SET `statusCode` = ? WHERE `callsign` = ?");
        $stmt->bind_param("ss", $status, $callsign);
        $stmt->execute();
        $stmt = $conn->prepare("UPDATE `cad_units` SET `postal` = ? WHERE `callsign` = ?");
        $stmt->bind_param("ss", $postal, $callsign);
        $stmt->execute();
      }
    }

  } else if ($command == 'v') {
    $callsign = strtoupper($_POST['command'][1]);
    $stmt = $conn->prepare("SELECT * FROM `cad_units` WHERE `callsign` = ?");
    $stmt->bind_param("s", $callsign);
    $stmt->execute();
    $results = $stmt->get_result();
    while ($row = $results->fetch_assoc()) {
      $row['staff'] = explode('|', $row['staff']);
      if ($row['staff'][0] != '') {
        $staff = [];
        foreach ($row['staff'] as $s) {
          array_push($staff, getCharacter($conn, $s)['name']);
        }
        $row['staff'] = join(', ', $staff);
      } else {
        $row['staff'] = 'None';
      }
      $view = $row;
    }
  } else if ($command == 'kickall') {
    $callsign = $_POST['command'][1];
    $stmt = $conn->prepare("UPDATE `cad_units` SET `staff`='' WHERE 1=1");
    $stmt->bind_param("s", $callsign);
    $stmt->execute();
  } else if ($command == 'kick') {
    $callsign = $_POST['command'][1];
    $stmt = $conn->prepare("UPDATE `cad_units` SET `staff`='' WHERE `callsign`=?");
    $stmt->bind_param("s", $callsign);
    $stmt->execute();
  } else if ($command == 'exit') {
    header('Location: ../../portal/');
  }
}

$statusEnumeration = [
  1 => "[1] Available & roaming",
  2 => "[2] Available at station",
  3 => "[3] Accepted and en route",
  4 => "[4] On scene",
  5 => "[5] Transporting prisoner/patient",
  6 => "[6] Arrived at hospital/jail",
  7 => "[7] Unavailable"
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="icon" href="../../assets/logo.png">
  <link rel="stylesheet" href="../../css/master.css">
  <link rel="stylesheet" href="../../css/dispatch.css">
  <script type="text/javascript">
    let a;
    let panics = 0;
    function initUpdates() {
      a = new Audio('../../assets/panic.mp3');
      a.volume = .1;
      updateTable('.units', './all-units.php');
      updateTable('.requests > div', './all-requests.php');
      setInterval(() => {
        updateTable('.units', './all-units.php');
        updateTable('.requests > div', './all-requests.php');
        if (document.getElementsByClassName('panic').length > panics) {
          a.play();
        }
        panics = document.getElementsByClassName('panic').length;
      }, 1000);
    }
    function updateTable(_query, _dataHref, useJQuery=false) {
      // JQuery method
      if (useJQuery) {
        $(_query).load(_dataHref);
      }
      // Vanilla JS method
      else {
        let request = new XMLHttpRequest();
        request.open('GET', _dataHref, true);
        request.onload = () => {
          if (request.status >=200 && request.status <= 400) {
            let resp = request.responseText;
            document.querySelectorAll(_query)[0].innerHTML = resp;
          }
        }
        request.send();
      }
    }
  </script>
  <title>Emergency Academy Roleplay | Dispatch</title>
</head>
<body onload="initUpdates()">
  <div class="units">

  </div>
  <form action="./" method="post">
    <input type="text" name="command" autofocus autocomplete="no">
    <button>Execute</button>
    <button type="button" onclick="document.location.href = '../../portal/'">Exit</button>
  </form>
  <div class="help">
    <div class="icon"><p>?</p></div>
    <div class="help-inner">
      <h2>Help Menu</h2>
      <ul>
        <li><span>/s {unit} {status}</span> : Sets status of unit</li>
        <li><span>/s {unit}</span> : Sets unit to status 7 and clears location</li>
        <li><span>/l {unit} {postal}</span> : Sets the location of a unit</li>
        <li><span>/l {unit}</span> : Clears unit's location</li>
        <li><span>/u {unit} {status} {postal}</span> : Updates a unit's status & location</li>
        <li><span>/v {unit}</span> : View detailed information about a unit</li>
        <li><span>/kick {unit}</span> : Kick all staff from a unit</li>
        <li><span>/kickall {unit}</span> : Kick everyone from every unit</li>
        <li><span>/exit</span> : Exit the Dispatch CAD</li>
      </ul>
    </div>
  </div>
  <?php if ($view != '') { ?>
    <div class="view">
      <h2>View Unit Details</h2>
      <table>
        <tr>
          <th>Unit</th>
          <td><?php echo $view['display'] ?></td>
        </tr>
        <tr>
          <th>Callsign</th>
          <td><?php echo $view['callsign'] ?></td>
        </tr>
        <tr>
          <th>Status Code</th>
          <td><?php echo $statusEnumeration[$view['statusCode']] ?></td>
        </tr>
        <tr>
          <th>Location</th>
          <td><?php echo $view['postal'] ?></td>
        </tr>
        <tr>
          <th>Occupants</th>
          <td><?php echo $view['staff'] ?></td>
        </tr>
      </table>
      <button id="close" onclick="document.querySelector('.view').style.animation='fade-out .5s forwards';document.querySelector('form input').focus()">Close</button>
    </div>
  <?php } ?>
    <div class="requests">
      <h2>REQUESTS</h2>
      <div>

      </div>
    </div>
</body>
</html>
