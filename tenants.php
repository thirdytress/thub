<?php
session_start();
if (!isset($_SESSION['owner'])) { exit("Unauthorized"); }

require 'classes/database.php';
$db = new Database();
$pdo = $db->connect();

$stmt = $pdo->query("SELECT * FROM Tenants ORDER BY FirstName");
$tenants = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="card shadow-sm">
  <div class="card-header">
    <h5 class="mb-0">Tenants</h5>
  </div>
  <div class="card-body">
    <?php if(!$tenants): ?>
      <p class="text-muted">No tenants yet.</p>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-striped align-middle">
          <thead class="table-dark">
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($tenants as $t): ?>
              <tr>
                <td><?php echo htmlspecialchars($t['FirstName'].' '.$t['LastName']); ?></td>
                <td><?php echo htmlspecialchars($t['Email']); ?></td>
                <td><?php echo htmlspecialchars($t['PhoneNumber'] ?? $t['Phone'] ?? ''); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>
