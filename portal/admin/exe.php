<?php

include '../../connect.inc.php';
if (isset($_POST['sql'])) {
  $sql = $_POST['sql'];
  $result = $conn->query($sql);
  if ($result instanceof mysqli_result) {
    while ($row = $result->fetch_assoc()) {
      echo '<table>';
      foreach ($row as $key => $val) {
        echo '<tr>';
        echo '<th>'.$key.'</th>';
        echo '<td>'.$val.'</td>';
        echo '<tr>';
        echo '</tr>';
      }
      echo '</table><br>';
    }
  }
}

?>

<style>
  table {
    border-collapse: collapse;
    width: 100%;
    max-width: 400px;
    background-color: #ddd;
  } table * {
    border: 1px solid #000;
    width: 50%;
  }
</style>

<form action="./exe.php" method="post">
  <input type="text" name="sql" autofocus />
  <button type="button" name="button">Execute</button>
</form>
