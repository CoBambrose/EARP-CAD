<?php

  include '../../connect.inc.php';
  session_start();

  $typeEnumeration = [
    'pd1' => 'Traffic Unit',
    'pd2' => 'Armed Response',
    'fd' => 'Fire Engine',
    'ias' => 'Ambulance',
    'panic' => 'Panic Response'
  ];

  if (!isset($_SESSION['id']) || !isset($_GET['type'])) {
    // header('Location: ../');
  } else {
    $type = $_GET['type'];
    $unit = unitFromChar($conn, $_SESSION['c']);

    // Check if unit exists in requests
    $stmt = $conn->prepare("SELECT * FROM `cad_requests` WHERE `unitID`=?");
    $stmt->bind_param("i", $unit['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    // Add unit
    if (mysqli_num_rows($result) < 1) {
      $stmt = $conn->prepare("INSERT INTO `cad_requests` (`unitID`) VALUES (?)");
      $stmt->bind_param("i", $unit['id']);
      $stmt->execute();
    }
    // Add request type
    $stmt = $conn->prepare("SELECT * FROM `cad_requests` WHERE `unitID`=?");
    $stmt->bind_param("i", $unit['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
      $types = explode('|', $row['types']);
      if ($types[0] == '') {
        array_splice($types, 0, 1);
      }
      if (in_array($type, $types)) {
        $index = array_search($type, $types);
        array_splice($types, $index, 1);
      } else {
        array_push($types, $type);
      }
      $types = join('|', $types);
      $stmt = $conn->prepare("UPDATE `cad_requests` SET `types` = ? WHERE `id` = ?");
      $stmt->bind_param("si", $types, $row['id']);
      $stmt->execute();
    }
    // header("Location: ../");
  }
?>

<link rel="stylesheet" href="../../css/master.css">
<style>
  body {
    display: grid;
    min-height: 100vh;
    overflow: hidden;
    font-family: sans-serif;
  }
  div {
    display: grid;
    margin: auto;
    padding: 2rem;
    border-radius: .5rem;
    background-color: #151515;
    grid-template-rows: 1fr 1fr;
    grid-row-gap: 2rem;
  }
  h2 {
    margin: auto;
    text-align: center;
  }
  form {
    display: grid;
    grid-template-columns: 1fr 1fr;
  }
  input {
    padding: .3rem;
    text-align: center;
    color: #000;
  }
  input[type=submit] {
    grid-column: 1/3;
  }
</style>
<div>
  <h2>What Location?</h2>
  <form action="./request.php" method="get">
    <input type="text" disabled value="<?php echo $typeEnumeration[$_GET['type']]; ?>">
    <input type="hidden" name="type" value="<?php echo $_GET['type']; ?>">
    <input type="text" autofocus name="location" placeholder="Location">
    <input type="submit" value="Request">
  </form>
</div>
