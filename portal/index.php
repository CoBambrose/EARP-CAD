<?php

include '../connect.inc.php';
session_start();

if (!isset($_SESSION['id'])) {
  header('Location: ../');
} else {
  $userData = getUser($conn, $_SESSION['id']);
  $stmt = $conn->prepare("SELECT * FROM `cad_users`");
  $stmt->execute();
  $result = $stmt->get_result();
  $all_users = [];
  while ($row = $result->fetch_assoc()) {array_push($all_users, $row);}

  $characterNames = [];
  foreach (getCharacters($conn, $_SESSION['id']) as $charID) {
    $char = getCharacter($conn, $charID);
    array_push($characterNames, [$charID, $char['name'], $char['dept']]);
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="icon" href="../assets/logo.png">
  <link rel="stylesheet" href="../css/master.css">
  <link rel="stylesheet" href="../css/portal.css">
  <title>Emergency Academy Roleplay | My Portal</title>
</head>
<body>
  <?php if (!$userData['approved']) { ?>
    <div class="disapproved">
      <h1>EARP Portal</h1>
      <p>Welcome to the EARP CAD system. Here is where you manage your characters and use the digital tools provided within your chosen department, have fun!</p>
      <p>Your account has been created but not yet been activated: please let us know on <a href="https://discord.com/invite/fYeQxVN" target="_blank">discord</a> if you've been waiting more than a few days :)</p>
    </div>
  <?php } else { ?>
    <div class="approved">
      <div class="header">
        <h1>EARP Portal</h1>
        <a href="../">Log Out</a>
      </div>
      <div class="characters" style="grid-row:2/4;">
        <h2>Your Characters</h2>
        <div class="list">
          <?php foreach ($characterNames as $char) { ?>
            <a href="../ic/?c=<?php echo $char[0]; ?>" id="<?php echo $char[2]; ?>"><?php echo $char[1]; ?></a>
          <?php } if (sizeof($characterNames) < 5) { ?>
            <a href="./new/">New Character</a>
          <?php } else { ?>
            <a href="#max-reached" id="max">~ Max Characters Reached ~</a>
          <?php } ?>
        </div>
      </div>
      <div class="actions">
        <h2>Welcome back,
          <span><?php echo ucfirst($userData['uid']); ?></span>
          <span>[ <?php echo join(', ', $userData['tags']); ?> ]</span>
        </h2>
        <form action="change-password.php" method="post" id="change-password">
          <input type="password" name="pwd" placeholder="6 - 20 characters">
          <button>Change Password</button>
        </form>
        <p>More features coming soon.</p>
      </div>
      <div class="links">
        <h2>Quick Links</h2>
        <a href="http://ea-rp.com">Emergency Academy Website</a>
        <a href="https://discord.com/invite/aArTBza">Emergency Academy Discord</a>
        <a href="http://invite.teamspeak.com/earp.zap-ts3.com">Emerygency Academy Teamspeak</a>
        <a href="https://www.dropbox.com/sh/82vl7pudzuocxsz/AACxjrgerixCJb3asVVue9C7a?dl=0" target="_blank">Shared Mod Files</a>
        <a href="https://discord.com/invite/flashinglights">Flashing Lights Discord</a>
        <?php if (hasTag($conn, $_SESSION['id'], 'admin') || hasTag($conn, $_SESSION['id'], 'supporter')) { ?>
          <a href="./admin/">Admin Area</a>
        <?php } if (hasTag($conn, $_SESSION['id'], 'dispatcher')) { ?>
          <a href="../ic/dispatch/">Dispatch CAD</a>
        <?php } ?>
      </div>
    </div>
  <?php } ?>
</body>
</html>
