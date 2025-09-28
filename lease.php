<?php
session_start();
if (!isset($_SESSION['owner'])) { header('Location: owner_login.php'); exit; }
require 'classes/database.php';
$db = new Database();
$pdo = $db->connect();

$errors = [];
$success = '';

// fetch tenants and apartments for selection
$tenants = $pdo->query("SELECT TenantID, FirstName, LastName FROM Tenants ORDER BY FirstName")->fetchAll(PDO::FETCH_ASSOC);
$apartments = $pdo->query("SELECT ApartmentID, BuildingName, UnitNumber FROM Apartments ORDER BY BuildingName")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tenantID = $_POST['tenant_id'] ?? null;
    $apartmentID = $_POST['apartment_id'] ?? null;
    $start = $_POST['start_date'] ?? null;
    $end = $_POST['end_date'] ?? null;
    $monthly = $_POST['monthly_rent'] ?? 0;
    $deposit = $_POST['deposit'] ?? 0;

    if (!$tenantID || !$apartmentID || !$start || !$end) $errors[] = 'Fill required fields.';
    if (empty($errors)) {
        $ok = $db->createLease($tenantID, $apartmentID, $start, $end, $monthly, $deposit);
        if ($ok) $success = 'Lease created.';
        else $errors[] = 'Failed to create lease.';
    }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Create Lease</title></head><body>
  <h2>Create Lease</h2>
  <?php foreach($errors as $e) echo '<p style="color:red">'.htmlspecialchars($e).'</p>'; ?>
  <?php if($success) echo '<p style="color:green">'.htmlspecialchars($success).'</p>'; ?>
  <form method="post">
    <label>Tenant<br>
      <select name="tenant_id" required>
        <option value="">--select--</option>
        <?php foreach($tenants as $t): ?>
          <option value="<?php echo $t['TenantID']; ?>"><?php echo htmlspecialchars($t['FirstName'].' '.$t['LastName']); ?></option>
        <?php endforeach; ?>
      </select>
    </label><br>

    <label>Apartment<br>
      <select name="apartment_id" required>
        <option value="">--select--</option>
        <?php foreach($apartments as $a): ?>
          <option value="<?php echo $a['ApartmentID']; ?>"><?php echo htmlspecialchars($a['BuildingName'].' #'.$a['UnitNumber']); ?></option>
        <?php endforeach; ?>
      </select>
    </label><br>

    <label>Start Date<br><input name="start_date" type="date" required></label><br>
    <label>End Date<br><input name="end_date" type="date" required></label><br>
    <label>Monthly Rent<br><input name="monthly_rent" type="number" step="0.01"></label><br>
    <label>Deposit<br><input name="deposit" type="number" step="0.01"></label><br>
    <button type="submit">Create Lease</button>
  </form>
  <p><a href="owner_dashboard.php">Back</a></p>
</body></html>
