<?php
session_start();
if (!isset($_SESSION['tenant'])) {
    header('Location: login.php'); exit;
}
require 'classes/database.php';
$db = new Database();
$pdo = $db->connect();
$tenant = $_SESSION['tenant'];
$tenant_id = $tenant['TenantID'] ?? $tenant['tenant_id'] ?? null;

// Fetch leases
$stmt = $pdo->prepare("SELECT l.*, a.BuildingName, a.UnitNumber FROM Leases l JOIN Apartments a ON l.ApartmentID = a.ApartmentID WHERE l.TenantID = :tid ORDER BY l.StartDate DESC");
$stmt->execute([':tid' => $tenant_id]);
$leases = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch payments (join payments -> leases)
$stmt = $pdo->prepare("SELECT p.* FROM Payment p JOIN Leases l ON p.LeasesID = l.LeasesID WHERE l.TenantID = :tid ORDER BY p.Pay_Date DESC");
$stmt->execute([':tid' => $tenant_id]);
$payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch maintenance requests
$stmt = $pdo->prepare("SELECT * FROM MaintenanceRequest WHERE TenantID = :tid ORDER BY RequestDate DESC");
$stmt->execute([':tid' => $tenant_id]);
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch parking assignment (simplified)
$stmt = $pdo->prepare("SELECT ps.* FROM ParkingSpaces ps WHERE ps.TenantID = :tid");
$stmt->execute([':tid' => $tenant_id]);
$parking = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Tenant Dashboard</title></head>
<body>
  <h2>Tenant Dashboard</h2>
  <p>Welcome, <?php echo htmlspecialchars($tenant['FirstName'] . ' ' . $tenant['LastName']); ?> | <a href="logout.php">Logout</a></p>

  <h3>Your Leases</h3>
  <?php if (!$leases) echo '<p>No leases found.</p>'; else { ?>
  <table border="1" cellpadding="6">
    <tr><th>Apartment</th><th>Start</th><th>End</th><th>Rent</th><th>Status</th></tr>
    <?php foreach($leases as $l): ?>
      <tr>
        <td><?php echo htmlspecialchars($l['BuildingName'] . ' #' . $l['UnitNumber']); ?></td>
        <td><?php echo htmlspecialchars($l['StartDate']); ?></td>
        <td><?php echo htmlspecialchars($l['EndDate']); ?></td>
        <td><?php echo htmlspecialchars($l['MonthlyRent']); ?></td>
        <td><?php echo htmlspecialchars($l['status'] ?? $l['Status'] ?? ''); ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
  <?php } ?>

  <h3>Your Payments</h3>
  <?php if(!$payments) echo '<p>No payments.</p>'; else { ?>
    <table border="1" cellpadding="6"><tr><th>Date</th><th>Amount</th><th>Method</th><th>Status</th></tr>
    <?php foreach($payments as $p): ?>
      <tr>
        <td><?php echo htmlspecialchars($p['Pay_Date']); ?></td>
        <td><?php echo htmlspecialchars($p['Amount']); ?></td>
        <td><?php echo htmlspecialchars($p['Pay_Method']); ?></td>
        <td><?php echo htmlspecialchars($p['status'] ?? $p['Status'] ?? ''); ?></td>
      </tr>
    <?php endforeach; ?>
    </table>
  <?php } ?>

  <h3>Your Maintenance Requests</h3>
  <?php if(!$requests) echo '<p>No requests.</p>'; else { ?>
    <ul>
    <?php foreach($requests as $r): ?>
      <li><?php echo '[' . htmlspecialchars($r['status'] ?? $r['Status'] ?? '') . '] ' . htmlspecialchars($r['RequestDate']) . ' - ' . htmlspecialchars(substr($r['description'] ?? $r['Description'] ?? '',0,120)); ?></li>
    <?php endforeach; ?>
    </ul>
  <?php } ?>

  <h3>Parking</h3>
  <?php if($parking) echo '<p>Spot: ' . htmlspecialchars($parking['SpotNumber'] ?? $parking['SpotNumber']) . '</p>'; else echo '<p>No parking assigned.</p>'; ?>

  <p><a href="request_maintenance.php">Create Maintenance Request</a></p>
  <p><a href="index.php">Home</a></p>
</body>
</html>
