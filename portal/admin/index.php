<?php

include '../../connect.inc.php';
session_start();

if (!isset($_SESSION['id'])) {
  header('Location: ../');
} else if (!hasTag($conn, $_SESSION['id'], 'admin') && !hasTag($conn, $_SESSION['id'], 'supporter')) {
  header('Location: ../');
} else {
  $stmt = $conn->prepare("SELECT * FROM `cad_users`");
  $stmt->execute();
  $result = $stmt->get_result();
  $unapprovedUsers = [];
  $users = [];
  while ($row = mysqli_fetch_assoc($result)) {
    if ($row['approved'] == 0) {
      array_push($unapprovedUsers, $row);
    } else {
      array_push($users, $row);
    }
  }

  $stmt = $conn->prepare("SELECT * FROM `cad_characters`");
  $stmt->execute();
  $result = $stmt->get_result();
  $characters = [];
  while ($row = mysqli_fetch_assoc($result)) {
    array_push($characters, $row);
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
  <link rel="stylesheet" href="../../css/admin.css">
  <script type="text/javascript">
    function setInputs(_inp) {
      let elts = document.querySelectorAll('.action form input[type=text]:first-child');
      for (let i = 0; i < elts.length; i++) {
        elts[i].value = i == 2 ? elts[i].value : _inp;
      }
    }
  </script>
  <title>Emergency Academy Roleplay | Admin Area</title>
</head>
<body>
  <div class="main">
    <div class="header">
      <h1>EARP Admin Area</h1>
      <a href="../">Go Back</a>
    </div>
    <div class="approvals">
      <h2>Approval Requests (<?php echo sizeof($unapprovedUsers); ?>)</h2>
      <?php foreach ($unapprovedUsers as $user) { ?>
        <div>
          <p><?php echo $user['uid']; ?></p>
          <button onclick="document.location.href = './approve.php?id=<?php echo $user['id']; ?>&a=0'">&#10006;</button>
          <button onclick="document.location.href = './approve.php?id=<?php echo $user['id']; ?>&a=1'">&#10004;</button>
        </div>
      <?php } ?>
    </div>
    <div class="members">
      <h2>Member Count: <?php echo sizeof($users); ?></h2>
      <?php foreach ($users as $user) { ?>
        <p
          onclick="setInputs('<?php echo $user['uid']; ?>')"
          <?php if (in_array('super', explode('|', $user['tags']))) { ?>
            style="color:#00c728;"
          <?php } else if (in_array('admin', explode('|', $user['tags']))) { ?>
            style="color:#0069bf;"
          <?php } else if (in_array('supporter', explode('|', $user['tags']))) { ?>
            style="color:#FFFF33;"
          <?php } else if (in_array('dispatcher', explode('|', $user['tags']))) { ?>
            style="color:#688;"
          <?php } ?>
        >
          <?php echo $user['uid']; ?>
        </p>
      <?php } ?>
    </div>
    <?php if (hasTag($conn, $_SESSION['id'], 'admin')) { ?>
      <div class="action">
        <h2>Login as a user</h2>
        <form action="./fake-login.php" method="post">
          <input type="text" name="uid" placeholder="Username" />
          <button>Log In</button>
        </form>
      </div>
      <div class="action tags">
        <h2>Set a user's tags</h2>
        <form action="./set-tags.php" method="post">
          <input type="text" name="uid" placeholder="Username" />
          <p style="color:#00c728"><input type="checkbox" name="super" /> super</p>
          <p style="color:#0069bf"><input type="checkbox" name="admin" /> admin</p>
          <p style="color:#FFFF33"><input type="checkbox" name="supporter" /> supporter</p>
          <p style="color:#688"><input type="checkbox" name="dispatcher" /> dispatcher</p>
          <p><input type="checkbox" name="beta-tester" /> beta-tester</p>
          <p>
            Other: &nbsp;&nbsp;&nbsp;
            <input type="text" name="tags" placeholder="Tags (separated by |)" />
          </p>
          <button>Set</button>
        </form>
      </div>
      <div class="action">
        <h2>Reset a user's password (to their username)</h2>
        <form action="./reset-password.php" method="post">
          <input type="text" name="uid" placeholder="Username" />
          <button>Reset</button>
        </form>
      </div>
      <div class="action">
        <h2>Suspend a user</h2>
        <form action="./approve.php" method="get">
          <input type="text" name="id" placeholder="Username" />
          <input type="hidden" name="a" value="2">
          <button>Suspend</button>
        </form>
      </div>
      <div class="characters">
        <h2>Character Count: <?php echo sizeof($characters); ?></h2>
        <?php foreach ($characters as $character) { ?>
          <p onclick="setInputs('<?php echo $character['name']; ?>');"><?php echo $character['name']; ?></p>
        <?php } ?>
      </div>
      <div class="action">
        <h2>Change a character's rank</h2>
        <form class="change-field" action="./change-field.php" method="post">
          <input type="text" name="uid" placeholder="Character Name" />
          <input type="text" name="val" placeholder="Rank" />
          <input type="hidden" name="field" value="rank" />
          <button>Change</button>
        </form>
      </div>
      <div class="action">
        <h2>Change a character's alive status</h2>
        <form class="change-field" action="./change-field.php" method="post">
          <input type="text" name="uid" placeholder="Character Name" />
          <input type="text" name="val" placeholder="Status" />
          <input type="hidden" name="field" value="status" />
          <button>Change</button>
        </form>
      </div>
      <div class="action">
        <h2>Delete a character</h2>
        <form action="./delete.php" method="post">
          <input type="text" name="uid" placeholder="Character Name" />
          <button>DELETE FOREVER</button>
        </form>
      </div>
    <?php } if (hasTag($conn, $_SESSION['id'], 'super')) { ?>
      <div class="action" style="border: 3px solid #f00;">
        <h2>SUPER SETTINGS</h2>
        <form action="./exe.php">
          <button>SQL Terminal</button>
        </form>
        <form action="./db.php">
          <button>Update Databases</button>
        </form>
      </div>
    <?php } ?>
  </div>
</body>
</html>
