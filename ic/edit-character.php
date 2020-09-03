<?php

  include '../connect.inc.php';
  session_start();

  if (!isset($_SESSION['id'])) {
    header('Location: ../portal/');
  } else {
    if (!isset($_SESSION['c'])) {
      header('Location: ../portal/');
    } if (isset($_POST['bio'])) {
      $stmt = $conn->prepare("UPDATE `cad_characters` SET `bio`=?, `appearance`=? WHERE `id`=?");
      $stmt->bind_param('ssi', $_POST['bio'], $_POST['appearance'], $_SESSION['c']);
      $stmt->execute();
      header('Location: ./');
    }
    $character = getCharacter($conn, $_SESSION['c']);
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
  <title>Emergency Academy Roleplay | Edit Character</title>
</head>
<body class="<?php echo getCharacter($conn, $_SESSION['c'])['dept']; ?>">
  <div class="nav">
    <a href="./">Cancel</a>
  </div>
  <div class="edit">
    <h2>Edit character info</h2>
    <form action="./edit-character.php" method="post">
      <p>Bio</p>
      <textarea name="bio"><?php echo $character['bio']; ?></textarea>
      <p>Appearance</p>
      <textarea name="appearance"><?php echo $character['appearance']; ?></textarea>
      <button>Change</button>
    </form>
  </div>
</body>
</html>
