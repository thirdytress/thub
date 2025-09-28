<?php
session_start();
if (!isset($_SESSION['owner'])) {
    header('Location: owner_login.php'); exit;
}
require 'classes/database.php';
$db = new Database();
$pdo = $db->connect();
$owner = $_SESSION['owner'];
$owner_id = $owner['OwnerID'] ?? $owner['owner_id'] ?? null;

// apartments for this owner
$stmt = $pdo->prepare("SELECT * FROM Apartments WHERE OwnerID = :oid");
$stmt->execute([':oid' => $owner_id]);
$apartments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// simple tenants list
$stmt = $pdo->query("SELECT * FROM Tenants ORDER BY FirstName");
$tenants = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Owner Dashboard</title></head>
<body>
  <h2>Owner Dashboard</h2>
  <p>Welcome, <?php echo htmlspecialchars($owner['FirstName'] . ' ' . $owner['LastName']); ?> | <a href="logout.php">Logout</a></p>

  <h3>Your Apartments</h3>
  <p><a href="add_apartment.php">Add Apartment</a></p>
  <?php if(!$apartments) echo '<p>No apartments yet.</p>'; else { ?>
    <table border="1" cellpadding="6"><tr><th>Unit</th><th>Building</th><th>Rent</th></tr>
    <?php foreach($apartments as $a): ?>
      <tr>
        <td><?php echo htmlspecialchars($a['UnitNumber']); ?></td>
        <td><?php echo htmlspecialchars($a['BuildingName']); ?></td>
        <td><?php echo htmlspecialchars($a['RentAmount']); ?></td>
      </tr>
    <?php endforeach; ?>
    </table>
  <?php } ?>

  <h3>Tenants</h3>
  <?php if(!$tenants) echo '<p>No tenants yet.</p>'; else { ?>
    <table border="1" cellpadding="6"><tr><th>Name</th><th>Email</th><th>Phone</th></tr>
    <?php foreach($tenants as $t): ?>
      <tr>
        <td><?php echo htmlspecialchars($t['FirstName'].' '.$t['LastName']); ?></td>
        <td><?php echo htmlspecialchars($t['Email']); ?></td>
        <td><?php echo htmlspecialchars($t['PhoneNumber'] ?? $t['Phone'] ?? ''); ?></td>
      </tr>
    <?php endforeach; ?>
    </table>
  <?php } ?>

  <p><a href="lease.php">Create Lease</a> | <a href="payment.php">Record Payment</a> | <a href="parking.php">Assign Parking</a></p>
  <p><a href="index.php">Home</a></p>
</body>
</html>
