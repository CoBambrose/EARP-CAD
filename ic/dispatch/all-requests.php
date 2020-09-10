<?php
  include '../../connect.inc.php';
  session_start();

  $stmt = $conn->prepare("SELECT * FROM `cad_requests` WHERE NOT `types` = ''");
  $stmt->execute();
  $result = $stmt->get_result();
  $requests = [];
  $locations = [];
  while ($row = $result->fetch_assoc()) {
    $requests[$row['unitID']] = [];
    $locations[$row['unitID']] = $row['location'];
    $row['types'] = explode('|', $row['types']);
    foreach ($row['types'] as $key => $type) {
      array_push($requests[$row['unitID']], $type);
    }
  }
$_SESSION['panics'] = 0;
  foreach ($requests as $unit => $rqs) {
    $class = '';
    if (in_array('panic', $rqs)) {
      $class = ' class="panic"';
      $_SESSION['panics']++;
    }
?>
<div>
  <p<?php echo $class; ?>>
    <strong>
      <?php echo getUnit($conn, $unit)['display']; ?>
    </strong>
    requesting:
    <?php foreach ($rqs as $key => $rq) { ?>
      <span><?php echo $typeEnumeration[$rq]; ?></span>
      <a href="./cancel-backup.php?unitID=<?php echo $unit; ?>&type=<?php echo $rq; ?>"></a>,
    <?php } ?>
    to:
    <?php echo $locations[$unit]; ?>
  </p>
</div>
<?php } ?>
<input style="color:#000" type="hidden" value="<?php echo $_SESSION['panics']; ?>">
