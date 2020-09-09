<?php

  include '../connect.inc.php';
  session_start();

  if (!isset($_SESSION['id'])) {
    header('Location: ../portal/');
  } else {
    if (!isset($_SESSION['c']) && !isset($_GET['c'])) {
      header('Location: ../portal/');
    } else if (isset($_GET['c'])) {
      $_SESSION['c'] = $_GET['c'];
      if (getCharacter($conn, $_SESSION['c'])['userID'] != $_SESSION['id']) {
        header('Location: ../portal/');
      }
    } else {
      if (getCharacter($conn, $_SESSION['c'])['userID'] != $_SESSION['id']) {
        header('Location: ../portal/');
      }
    }
    $character = getCharacter($conn, $_SESSION['c']);

    $unit = '';
    $row = unitFromChar($conn, $_SESSION['c']);
    if ($row != '') {
      $row['staff'] = explode('|', $row['staff']);
      $staff = [];
      foreach ($row['staff'] as $s) {
        array_push($staff, getCharacter($conn, $s)['name']);
      }
      $row['staff'] = $staff;
      $unit = $row;
    }

    $stmt = $conn->prepare('SELECT * FROM `cad_units`');
    $stmt->execute();
    $result = $stmt->get_result();
    $allUnits = [
      'ias' => [],
      'pd' => [],
      'fd' => []
    ];
    while ($row = $result->fetch_assoc()) {
      array_push($allUnits[$row['dept']], $row);
    }

    $requestAnchorEnum = [
      "pd1" => 0,
      "pd2" => 1,
      "ias" => 2,
      "fd" => 3,
      "panic" => 4
    ];
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="icon" href="../assets/logo.png">
  <link rel="stylesheet" href="../css/ic-master.css">
  <script type="text/javascript">
    let a, panics;
    function initUpdates() {
      a = new Audio('../assets/panic.mp3');
      a.volume = .1;
      if (<?php if (getCharacter($conn, $_SESSION['c'])['dept'] != 'civ' && $unit != '') {echo 'true';} else {echo 'false';} ?>) {
        updateTable('table-cont', './table.php');
        setInterval(() => {
          updateTable('table-cont', './table.php');
          let newPanics = document.getElementsByClassName('panic').length;
          if (newPanics > panics) { a.play(); }
          panics = newPanics;
        }, 1000);
      }
    }
    function updateTable(className, dataHref, useJQuery=false) {
      // JQuery method
      if (useJQuery) {
        $("."+className).load(dataHref);
        return;
      }
      // Vanilla JS method
      let request = new XMLHttpRequest();
      request.open('GET', dataHref, true);
      request.onload = () => {
        if (request.status >= 200 && request.status <= 400) {
          let resp = request.responseText;
          document.getElementsByClassName(className)[0].innerHTML = resp;
        }
      }
      request.send();
    }
    function edit() {
      document.location.href = './edit-character.php';
    }
  </script>
  <title>Emergency Academy Roleplay | Character Screen</title>
</head>
<body class="<?php echo getCharacter($conn, $_SESSION['c'])['dept']; ?>" onload="initUpdates()">
  <div class="nav">
    <a href="../portal/">Leave Character</a>
  </div>
  <div class="section character-info">
    <h2>Character Details</h2>
    <p>Full Name: </p>
    <p><?php echo $character['name'] ?></p>
    <p>Dept:</p>
    <p><?php echo $rankEnumeration[$character['dept']] ?></p>
    <p>Rank:</p>
    <p><?php echo $character['rank'] ?></p>
    <p>Biography:</p>
    <p><?php echo nl2br($character['bio']) ?>&nbsp;&nbsp;<button onclick="edit()">edit</button></p>
    <p>Appearance:</p>
    <p><?php echo nl2br($character['appearance']) ?>&nbsp;&nbsp;<button onclick="edit()">edit</button></p>
    <p>Status:</p>
    <p><?php echo nl2br($character['status']) ?></p>
  </div>
  <?php if (getCharacter($conn, $_SESSION['c'])['dept'] != 'civ' && $unit == '' && getCharacter($conn, $_SESSION['c'])['status'] != 'deceased') { ?>
    <div class="section">
      <h2>Mobile Unit Management</h2>
      <h3>Join a unit</h3>
      <form action="./dispatch/join-unit.php" method="post">
        <select name="id">
          <optgroup label="IAS">
            <?php foreach ($allUnits['ias'] as $u) { ?>
              <option value="<?php echo $u['id'] ?>"><?php echo $u['display'] ?></option>
            <?php } ?>
          </optgroup>
          <optgroup label="PD">
            <?php foreach ($allUnits['pd'] as $u) { ?>
              <option value="<?php echo $u['id'] ?>"><?php echo $u['display'] ?></option>
            <?php } ?>
          </optgroup>
          <optgroup label="FD">
            <?php foreach ($allUnits['fd'] as $u) { ?>
              <option value="<?php echo $u['id'] ?>"><?php echo $u['display'] ?></option>
            <?php } ?>
          </optgroup>
        </select>
        <button>Join</button>
      </form>
    </div>
<?php
    } else if (getCharacter($conn, $_SESSION['c'])['dept'] != 'civ' && getCharacter($conn, $_SESSION['c'])['status'] != 'deceased') {
      $stmt = $conn->prepare("SELECT * FROM `cad_requests` WHERE `unitID` = ?");
      $stmt->bind_param("i", $unit['id']);
      $stmt->execute();
      $result = $stmt->get_result();
      while ($row = $result->fetch_assoc()) {
        $types = explode('|', $row['types']);
        if ($types[0] != '') {
          echo '<script>setTimeout(() => {';
          foreach ($types as $key => $type) {
            echo 'document.querySelectorAll(".request a")['.$requestAnchorEnum[$type].'].innerHTML = "En route to '.$row['location'].'";';
          }
          echo '}, 500)</script>';
        }
      }
?>
    <div class="section unit">
      <h2>Mobile Unit Management</h2>
      <div class="table-cont">
        <!-- Unit Details -->
      </div>
      <a href="./dispatch/leave-unit.php">Leave Unit</a>
    </div>
    <div class="request">
      <h2>Request Unit</h2>
      <a href="./dispatch/request.php?type=pd1" style="background-color:var(--pd-dark)">PD Traffic Unit</a>
      <a href="./dispatch/request.php?type=pd2" style="background-color:var(--pd-darkest)">Armed Response</a>
      <a href="./dispatch/request.php?type=ias" style="background-color:var(--ias-darkest)">IAS Ambulance</a>
      <a href="./dispatch/request.php?type=fd" style="background-color:var(--fd-dark)">FD Unit</a>
      <a href="./dispatch/request.php?type=panic" style="background-color:#700;">Panic 10-99 !!</a>
    </div>
  <?php } ?>
  <?php if (getCharacter($conn, $_SESSION['c'])['status'] != 'deceased') { ?>
    <div class="section">
      <h2>Mark character as deceased</h2>
      <a href="./kill.php">Mark as dead</a>
    </div>
  <?php } ?>
</body>
</html>
