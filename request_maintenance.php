<?php
session_start();
if (!isset($_SESSION['tenant'])) { header('Location: login.php'); exit; }
require 'classes/database.php';
$db = new Database();

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tenantID = $_SESSION['tenant']['TenantID'] ?? $_SESSION['tenant']['tenant_id'];
    $aptID = $_POST['apartment_id'] ?? null;
    $desc = trim($_POST['description'] ?? '');
    $date = date('Y-m-d H:i:s');

    if (!$aptID || !$desc) $errors[] = 'Select apartment and enter description.';
    if (empty($errors)) {
        $ok = $db->addRequest($tenantID, $aptID, $date, $desc);
        if ($ok) $success = 'Request submitted.';
        else $errors[] = 'Failed to submit request.';
    }
}

// fetch apartments for tenant to choose (optional)
$pdo = $db->connect();
$stmt = $pdo->prepare("SELECT a.ApartmentID, a.BuildingName, a.UnitNumber FROM Apartments a JOIN Leases l ON a.ApartmentID = l.ApartmentID WHERE l.TenantID = :tid");
$stmt->execute([':tid' => $_SESSION['tenant']['TenantID'] ?? $_SESSION['tenant']['tenant_id']]);
$apartments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Maintenance Request</title></head><body>
  <h2>Submit Maintenance Request</h2>
  <?php foreach($errors as $e) echo '<p style="color:red">'.htmlspecialchars($e).'</p>'; ?>
  <?php if($success) echo '<p style="color:green">'.htmlspecialchars($success).'</p>'; ?>

  <form method="post">
    <label>Apartment<br>
      <select name="apartment_id" required>
        <option value="">--select--</option>
        <?php foreach($apartments as $a): ?>
          <option value="<?php echo $a['ApartmentID']; ?>"><?php echo htmlspecialchars($a['BuildingName'].' #'.$a['UnitNumber']); ?></option>
        <?php endforeach; ?>
      </select>
    </label><br>
    <label>Description<br><textarea name="description" required></textarea></label><br>
    <button type="submit">Submit Request</button>
  </form>
  <p><a href="tenant_dashboard.php">Back</a></p>
</body></html>
