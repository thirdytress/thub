<?php
session_start();
if (!isset($_SESSION['owner'])) { exit("Unauthorized"); }

require 'classes/database.php';
$db = new Database();
$pdo = $db->connect();
$owner = $_SESSION['owner'];
$owner_id = $owner['OwnerID'] ?? $owner['owner_id'] ?? null;

$stmt = $pdo->prepare("SELECT * FROM Apartments WHERE OwnerID = :oid");
$stmt->execute([':oid' => $owner_id]);
$apartments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="card shadow-sm">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Your Apartments</h5>
    <a class="btn btn-primary btn-sm" href="add_apartment.php">+ Add Apartment</a>
  </div>
  <div class="card-body">
    <?php if(!$apartments): ?>
      <p class="text-muted">No apartments yet.</p>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-dark">
            <tr>
              <th>Unit</th>
              <th>Building</th>
              <th>Rent</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($apartments as $a): ?>
              <tr>
                <td><?php echo htmlspecialchars($a['UnitNumber']); ?></td>
                <td><?php echo htmlspecialchars($a['BuildingName']); ?></td>
                <td><?php echo htmlspecialchars($a['RentAmount']); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>
