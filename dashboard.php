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

// Fetch payments
$stmt = $pdo->prepare("SELECT p.* FROM Payment p JOIN Leases l ON p.LeaseID = l.LeaseID WHERE l.TenantID = :tid ORDER BY p.Pay_Date DESC");
$stmt->execute([':tid' => $tenant_id]);
$payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch maintenance requests
$stmt = $pdo->prepare("SELECT * FROM MaintenanceRequest WHERE TenantID = :tid ORDER BY RequestDate DESC");
$stmt->execute([':tid' => $tenant_id]);
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch parking
$stmt = $pdo->prepare("SELECT ps.* FROM ParkingSpaces ps WHERE ps.TenantID = :tid");
$stmt->execute([':tid' => $tenant_id]);
$parking = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch tenant applications
$applications = $db->getTenantApplications($tenant_id);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Tenant Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container">
    <a class="navbar-brand" href="#">Tenant Dashboard</a>
    <div class="ms-auto">
      <span class="navbar-text me-3">Hello, <?php echo htmlspecialchars($tenant['FirstName'] . ' ' . $tenant['LastName']); ?></span>
      <a class="btn btn-light btn-sm" href="logout.php">Logout</a>
    </div>
  </div>
</nav>

<div class="container">

  <!-- Leases -->
  <div class="card mb-4">
    <div class="card-header bg-success text-white">Your Leases</div>
    <div class="card-body p-0">
      <?php if (!$leases) echo '<p class="p-3">No leases found.</p>'; else { ?>
      <div class="table-responsive">
        <table class="table table-striped mb-0">
          <thead class="table-dark">
            <tr>
              <th>Apartment</th>
              <th>Start</th>
              <th>End</th>
              <th>Rent</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach($leases as $l): ?>
            <tr>
              <td><?php echo htmlspecialchars($l['BuildingName'] . ' #' . $l['UnitNumber']); ?></td>
              <td><?php echo htmlspecialchars($l['StartDate']); ?></td>
              <td><?php echo htmlspecialchars($l['EndDate']); ?></td>
              <td><?php echo htmlspecialchars($l['MonthlyRent']); ?></td>
              <td><?php echo htmlspecialchars($l['status'] ?? $l['Status'] ?? ''); ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php } ?>
    </div>
  </div>

  <!-- Applications -->
  <div class="card mb-4">
    <div class="card-header bg-primary text-white">Your Applications</div>
    <div class="card-body p-0">
      <?php if(!$applications) { ?>
        <p class="p-3">No applications found.</p>
      <?php } else { ?>
        <div class="table-responsive">
          <table class="table table-bordered mb-0">
            <thead class="table-light">
              <tr>
                <th>Apartment</th>
                <th>Submitted On</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($applications as $app): ?>
              <tr>
                <td><?= htmlspecialchars($app['BuildingName'] . " - " . $app['UnitNumber']) ?></td>
                <td><?= htmlspecialchars($app['CreatedAt']) ?></td>
                <td>
                  <?php if($app['Status'] == 'Pending'): ?>
                    <span class="badge bg-warning">Pending</span>
                  <?php elseif($app['Status'] == 'Approved'): ?>
                    <span class="badge bg-success">Approved</span>
                  <?php else: ?>
                    <span class="badge bg-danger">Rejected</span>
                  <?php endif; ?>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php } ?>
    </div>
  </div>

  <!-- Payments -->
  <div class="card mb-4">
    <div class="card-header bg-info text-white">Your Payments</div>
    <div class="card-body p-0">
      <?php if(!$payments) echo '<p class="p-3">No payments.</p>'; else { ?>
      <div class="table-responsive">
        <table class="table table-striped mb-0">
          <thead class="table-dark">
            <tr>
              <th>Date</th>
              <th>Amount</th>
              <th>Method</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach($payments as $p): ?>
            <tr>
              <td><?php echo htmlspecialchars($p['Pay_Date']); ?></td>
              <td><?php echo htmlspecialchars($p['Amount']); ?></td>
              <td><?php echo htmlspecialchars($p['Pay_Method']); ?></td>
              <td><?php echo htmlspecialchars($p['status'] ?? $p['Status'] ?? ''); ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php } ?>
    </div>
  </div>

  <!-- Maintenance Requests -->
  <div class="card mb-4">
    <div class="card-header bg-warning text-dark">Maintenance Requests</div>
    <div class="card-body">
      <?php if(!$requests) echo '<p>No requests.</p>'; else { ?>
      <ul class="list-group list-group-flush">
        <?php foreach($requests as $r): ?>
          <li class="list-group-item">
            <span class="badge bg-secondary me-2"><?php echo htmlspecialchars($r['status'] ?? $r['Status'] ?? ''); ?></span>
            <?php echo htmlspecialchars($r['RequestDate']); ?> - <?php echo htmlspecialchars(substr($r['description'] ?? $r['Description'] ?? '',0,120)); ?>
          </li>
        <?php endforeach; ?>
      </ul>
      <?php } ?>
    </div>
  </div>

  <!-- Parking -->
  <div class="card mb-4">
    <div class="card-header bg-secondary text-white">Parking</div>
    <div class="card-body">
      <?php if($parking) echo '<p>Spot: ' . htmlspecialchars($parking['SpotNumber'] ?? $parking['SpotNumber']) . '</p>'; else echo '<p>No parking assigned.</p>'; ?>
    </div>
  </div>

  <!-- Actions -->
  <div class="mb-4">
    <a href="request_maintenance.php" class="btn btn-primary me-2">Create Maintenance Request</a>
    <a href="index.php" class="btn btn-outline-secondary">Home</a>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
