<?php
session_start();
if (!isset($_SESSION['owner'])) { header('Location: owner_login.php'); exit; }
require 'classes/database.php';
$db = new Database();
$pdo = $db->connect();

$errors = [];
$success = '';

$tenants = $pdo->query("SELECT TenantID, FirstName, LastName FROM Tenants ORDER BY FirstName")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tenantID = $_POST['tenant_id'] ?? null;
    $spot = trim($_POST['spot'] ?? '');
    if (!$tenantID || !$spot) $errors[] = 'Select tenant and provide spot number.';
    if (empty($errors)) {
        $ok = $db->assignParking($tenantID, $spot);
        if ($ok) $success = 'Parking assigned.';
        else $errors[] = 'Failed to assign parking (maybe spot exists).';
    }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Assign Parking</title></head><body>
  <h2>Assign Parking</h2>
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
    <label>Spot Number<br><input name="spot" required></label><br>
    <button type="submit">Assign</button>
  </form>
  <p><a href="owner_dashboard.php">Back</a></p>
</body></html>
