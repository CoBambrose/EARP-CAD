<?php

session_start();
$_SESSION['id'] = null;

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="icon" href="./assets/logo.png">
  <link rel="stylesheet" href="./css/master.css">
  <link rel="stylesheet" href="./css/index.css">
  <script type="text/javascript">
    function toggleHeight(_q, _i) {
      let elt = document.querySelectorAll(_q+' p')[_i];
      elt.style.height = document.querySelectorAll(_q+' input')[_i].value == '' ? '0' : '20px';
    }
  </script>
  <title>Emergency Academy Roleplay | Log In or Sign Up</title>
</head>
<body>
  <?php if (isset($_GET['e'])) { ?>
    <script>setTimeout(() => {
      alert('The details you provided are invalid, please try again.');
    }, 1000);</script>
  <?php } ?>
  <form action="./auth/?a=login" method="post" class="login">
    <h3>Log In</h3>
    <p>Username</p>
    <input type="text" name="uid" placeholder="Username" oninput="toggleHeight('.login', 0)">
    <p>Password</p>
    <input type="password" name="pwd" placeholder="Password" oninput="toggleHeight('.login', 1)">
    <p id="exempt">Username must be between 6 - 20 characters long | Password must be between 6 - 20 characters long</p>
    <button>Log In</button>
  </form>
  <form action="./auth/?a=signup" method="post" class="signup">
    <h3>Sign Up</h3>
    <p>Username</p>
    <input type="text" name="uid" placeholder="Username" oninput="toggleHeight('.signup', 0)">
    <p>Password</p>
    <input type="password" name="pwd" placeholder="Password" oninput="toggleHeight('.signup', 1)">
    <p>Confirm Password</p>
    <input type="password" name="pwd2" placeholder="Confirm Password" oninput="toggleHeight('.signup', 2)">
    <p id="exempt">Username must be between 6 - 20 characters long | Password must be between 6 - 20 characters long | Passwords must match</p>
    <button>Sign Up</button>
  </form>
</body>
</html>
