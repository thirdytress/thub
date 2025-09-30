
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
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Add Apartment</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-8">
        
        <div class="card shadow">
          <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Add Apartment</h4>
          </div>
          <div class="card-body">
            
            <!-- Error messages -->
            <?php if($errors): ?>
              <div class="alert alert-danger">
                <?php foreach($errors as $e) echo '<div>'.htmlspecialchars($e).'</div>'; ?>
              </div>
            <?php endif; ?>

            <!-- Success message -->
            <?php if($success): ?>
              <div class="alert alert-success">
                <?= htmlspecialchars($success) ?>
              </div>
            <?php endif; ?>

            <!-- Form -->
            <form method="post">
              <div class="mb-3">
                <label class="form-label">Building Name</label>
                <input type="text" name="building" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Unit Number</label>
                <input type="text" name="unit" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Rent Amount</label>
                <input type="number" step="0.01" name="rent" class="form-control">
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Bedrooms</label>
                  <input type="number" name="bedrooms" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Bathrooms</label>
                  <input type="number" name="bathrooms" class="form-control">
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Street</label>
                <input type="text" name="street" class="form-control">
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">City</label>
                  <input type="text" name="city" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Province</label>
                  <input type="text" name="prov" class="form-control">
                </div>
              </div>
              <div class="d-flex justify-content-between">
                <a href="owner_dashboard.php" class="btn btn-secondary">Back</a>
                <button type="submit" class="btn btn-primary">Add Apartment</button>
              </div>
            </form>

          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

