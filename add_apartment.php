<?php
session_start();
if (!isset($_SESSION['owner'])) { header('Location: owner_login.php'); exit; }
require 'classes/database.php';
$db = new Database();

$errors = [];
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ownerID = $_SESSION['owner']['OwnerID'] ?? $_SESSION['owner']['owner_id'];
    $building = trim($_POST['building'] ?? '');
    $rent = $_POST['rent'] ?? 0;
    $bed = $_POST['bedrooms'] ?? 0;
    $bath = $_POST['bathrooms'] ?? 0;
    $unit = trim($_POST['unit'] ?? '');
    $street = trim($_POST['street'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $prov = trim($_POST['prov'] ?? '');

    if (!$building || !$unit) $errors[] = 'Building and unit required.';
    if (empty($errors)) {
        $ok = $db->addApartment($ownerID, $building, $rent, $bed, $bath, $unit, $street, $city, $prov);
        if ($ok) $success = 'Apartment added.';
        else $errors[] = 'Failed to add apartment.';
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Add Apartment</title></head>
<body>
  <h2>Add Apartment</h2>
  <?php foreach($errors as $e) echo '<p style="color:red">'.htmlspecialchars($e).'</p>'; ?>
  <?php if($success) echo '<p style="color:green">'.htmlspecialchars($success).'</p>'; ?>
  <form method="post">
    <label>Building Name<br><input name="building" required></label><br>
    <label>Unit Number<br><input name="unit" required></label><br>
    <label>Rent Amount<br><input name="rent" type="number" step="0.01"></label><br>
    <label>Bedrooms<br><input name="bedrooms" type="number"></label><br>
    <label>Bathrooms<br><input name="bathrooms" type="number"></label><br>
    <label>Street<br><input name="street"></label><br>
    <label>City<br><input name="city"></label><br>
    <label>Province<br><input name="prov"></label><br>
    <button type="submit">Add Apartment</button>
  </form>
  <p><a href="owner_dashboard.php">Back</a></p>
</body>
</html>
