<?php

include '../../connect.inc.php';
session_start();

if (!isset($_SESSION['id'])) {
  header('Location: ../');
} else {
  $chars = getCharacters($conn, $_SESSION['id']);
  if (sizeof($chars) >= 5) {
    header("Location: ../");
  } if (isset($_POST['name'])) {
    $name = $_POST['name'];
    $department = $_POST['dept'];
    $rank = '';
    $bio = $_POST['bio'];
    $appearance = $_POST['appearance'];

    if ($department == 'civ') {
      $stmt = $conn->prepare("INSERT INTO `cad_characters` (`name`, `dept`, `rank`, `bio`, `appearance`, `userID`) VALUES (?, ?, ?, ?, ?, ?)");
      $stmt->bind_param("sssssi", $name, $department, $rank, $bio, $appearance, $_SESSION['id']);
    } else {
      $stmt = $conn->prepare("INSERT INTO `cad_characters` (`name`, `dept`, `bio`, `appearance`, `userID`) VALUES (?, ?, ?, ?, ?)");
      $stmt->bind_param("ssssi", $name, $department, $bio, $appearance, $_SESSION['id']);
    }
    $stmt->execute();

    $stmt = $conn->prepare("SELECT `id` FROM `cad_characters` WHERE `name`=?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = mysqli_fetch_assoc($result)) {
      array_push($chars, $row['id']);
      break;
    }
    if ($chars[0] == '') {
      array_splice($chars, 0, 1);
    }
    $chars = join('|', $chars);

    $stmt = $conn->prepare("UPDATE `cad_users` SET `characterIDs`=? WHERE `id`=?");
    $stmt->bind_param("si", $chars, $_SESSION['id']);
    $stmt->execute();

    header('Location: ../');
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="icon" href="../../assets/logo.png">
  <link rel="stylesheet" href="../../css/master.css">
  <link rel="stylesheet" href="../../css/portal_new.css">
  <title>Emergency Academy Roleplay | Create Character</title>
</head>
<body>
  <div class="content">
    <h2>Create New Character</h2>
    <form action="." method="post">
      <p>Full Character Name (Without rank):</p>
      <input type="text" name="name">
      <p>Character Department:</p>
      <select name="dept">
        <option value="pd">Island Police Department</option>
        <option value="ias">Island Ambulance Department</option>
        <option value="fd">Island Fire Department</option>
        <option value="gov" <?php if (!hasTag($conn, $_SESSION['id'], 'super')) { ?>disabled<?php } ?>>Federal Island Government</option>
        <option value="civ">Civillian of the Island</option>
      </select>
      <p>Character Biography:</p>
      <textarea name="bio"></textarea>
      <p>Physical Appearance:</p>
      <textarea name="appearance"></textarea>
      <button type="button" onclick="document.location.href = '../'">Back</button>
      <button>Create Your Character!!</button>
    </form>
  </div>
</body>
</html>
