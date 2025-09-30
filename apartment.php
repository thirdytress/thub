<?php
session_start();
include 'classes/database.php';
$db = new Database();
$apartments = $db->getAllApartments();
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Apartments - Apartment Hub</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

  <div class="container py-5">
    <div class="row mb-4">
      <div class="col text-center">
        <h2 class="fw-bold">Available Apartments</h2>
      </div>
    </div>

    <?php if(!$apartments): ?>
      <div class="alert alert-info text-center">No apartments available.</div>
    <?php else: ?>
      <div class="table-responsive shadow-sm">
        <table class="table table-bordered table-hover align-middle bg-white">
          <thead class="table-primary">
            <tr>
              <th>Building</th>
              <th>Unit</th>
              <th>Rent</th>
              <th>Bedrooms</th>
              <th>Bathrooms</th>
              <th>Location</th>
              <th>Owner</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($apartments as $apt): ?>
            <tr>
              <td><?= htmlspecialchars($apt['BuildingName']) ?></td>
              <td><?= htmlspecialchars($apt['UnitNumber']) ?></td>
              <td><?= htmlspecialchars($apt['RentAmount']) ?></td>
              <td><?= htmlspecialchars($apt['Bedrooms']) ?></td>
              <td><?= htmlspecialchars($apt['Bathrooms']) ?></td>
              <td><?= htmlspecialchars($apt['Apt_Street'] . ', ' . $apt['Apt_City'] . ', ' . $apt['Apt_Prov']) ?></td>
              <td><?= htmlspecialchars($apt['FirstName'] . ' ' . $apt['LastName']) ?></td>
              <td>
<a href="apply_apartment.php?apartment_id=<?= $apartment['ApartmentID'] ?>" class="btn btn-primary">Apply</a>

              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>

    <div class="text-center mt-4">
      <a href="index.php" class="btn btn-primary px-4">Back to Home</a>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
