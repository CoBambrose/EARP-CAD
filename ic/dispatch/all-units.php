<?php
include '../../connect.inc.php';
session_start();

$units = [
  "ias" => [],
  "pd" => [],
  "fd" => []
];
$stmt = $conn->prepare("SELECT * FROM `cad_units`");
$stmt->execute();
$results = $stmt->get_result();
while ($row = $results->fetch_assoc()) {
  if ($row['postal'] == '') {$row['postal'] = '-';}
  array_push($units[$row['dept']], $row);
}

?>

<div class="pd">
  <h2>Police Dept</h2>
  <?php foreach ($units['pd'] as $unit) { ?>
    <button class="s<?php echo $unit['statusCode'] ?>">
      [ <?php echo $unit['callsign'] ?> ]&nbsp;&nbsp;&nbsp;&nbsp;
      <?php echo $unit['display'] ?>
      <span id="p">L-<?php echo $unit['postal'] ?></span>
      <span id="s">S<?php echo $unit['statusCode'] ?></span>
    </button>
  <?php } ?>
</div>
<div class="fd">
  <h2>Fire & Rescue</h2>
  <?php foreach ($units['fd'] as $unit) { ?>
    <button class="s<?php echo $unit['statusCode'] ?>">
      [ <?php echo $unit['callsign'] ?> ]&nbsp;&nbsp;&nbsp;&nbsp;
      <?php echo $unit['display'] ?>
      <span id="p">L-<?php echo $unit['postal'] ?></span>
      <span id="s">S<?php echo $unit['statusCode'] ?></span>
    </button>
  <?php } ?>
</div>
<div class="ais">
  <h2>Ambulance Service</h2>
  <?php foreach ($units['ias'] as $unit) { ?>
    <button class="s<?php echo $unit['statusCode'] ?>">
      [ <?php echo $unit['callsign'] ?> ]&nbsp;&nbsp;&nbsp;&nbsp;
      <?php echo $unit['display'] ?>
      <span id="p">L-<?php echo $unit['postal'] ?></span>
      <span id="s">S<?php echo $unit['statusCode'] ?></span>
    </button>
  <?php } ?>
</div>
