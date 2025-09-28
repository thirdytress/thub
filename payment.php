<?php
session_start();
if (!isset($_SESSION['owner'])) { header('Location: owner_login.php'); exit; }
require 'classes/database.php';
$db = new Database();
$pdo = $db->connect();

$errors = [];
$success = '';

$leases = $pdo->query("SELECT l.LeasesID, t.FirstName, t.LastName, a.BuildingName, a.UnitNumber FROM Leases l JOIN Tenants t ON l.TenantID = t.TenantID JOIN Apartments a ON l.ApartmentID = a.ApartmentID ORDER BY l.StartDate DESC")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $leaseID = $_POST['lease_id'] ?? null;
    $date = $_POST['payment_date'] ?? date('Y-m-d H:i:s');
    $amount = $_POST['amount'] ?? 0;
    $method = $_POST['method'] ?? 'cash';

    if (!$leaseID || !$amount) $errors[] = 'Select lease and amount.';
    if (empty($errors)) {
        $ok = $db->recordPayment($leaseID, $date, $amount, $method);
        if ($ok) $success = 'Payment recorded.';
        else $errors[] = 'Failed to record payment.';
    }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Record Payment</title></head><body>
  <h2>Record Payment</h2>
  <?php foreach($errors as $e) echo '<p style="color:red">'.htmlspecialchars($e).'</p>'; ?>
  <?php if($success) echo '<p style="color:green">'.htmlspecialchars($success).'</p>'; ?>

  <form method="post">
    <label>Lease<br>
      <select name="lease_id" required>
        <option value="">--select--</option>
        <?php foreach($leases as $l): ?>
          <option value="<?php echo $l['LeasesID']; ?>"><?php echo htmlspecialchars($l['FirstName'].' '.$l['LastName'].' - '.$l['BuildingName'].' #'.$l['UnitNumber']); ?></option>
        <?php endforeach; ?>
      </select>
    </label><br>

    <label>Payment Date<br><input name="payment_date" type="datetime-local" value="<?php echo date('Y-m-d\TH:i'); ?>"></label><br>
    <label>Amount<br><input name="amount" type="number" step="0.01" required></label><br>
    <label>Method<br><input name="method" value="cash"></label><br>
    <button type="submit">Record Payment</button>
  </form>

  <p><a href="owner_dashboard.php">Back</a></p>
</body></html>
