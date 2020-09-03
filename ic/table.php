<?php

include '../connect.inc.php';
session_start();

$unit = '';
$row = unitFromChar($conn, $_SESSION['c']);
if (isset($row)) {
  $row['staff'] = explode('|', $row['staff']);
  $staff = [];
  foreach ($row['staff'] as $s) {
    array_push($staff, getCharacter($conn, $s)['name']);
  }
  $row['staff'] = $staff;
  $unit = $row;
}

$stmt = $conn->prepare("SELECT * FROM `cad_requests` WHERE `types` LIKE '%panic%'");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
  echo '<span class="panic" style="display:none"></span>';
}

?>

<table>
  <thead>
    <th>Unit</th>
    <th>Status Code</th>
    <th>Staff</th>
    <th>Location</th>
  </thead>
  <tbody>
    <tr>
      <td><?php echo $unit['display'] ?></td>
      <td><?php echo $unit['statusCode'] ?></td>
      <td><?php echo join(', ', $unit['staff']) ?></td>
      <td><?php echo $unit['postal'] ?></td>
    </tr>
  </tbody>
</table>
