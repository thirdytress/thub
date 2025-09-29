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
  <style>
    body { font-family: Arial, sans-serif; background:#f4f6f9; padding:20px; }
    h2 { color:#2c3e50; }
    table { border-collapse:collapse; width:100%; background:#fff; box-shadow:0 2px 5px rgba(0,0,0,0.1); }
    th, td { border:1px solid #ccc; padding:10px; text-align:left; }
    th { background:#3498db; color:#fff; }
    tr:nth-child(even) { background:#f9f9f9; }
    a.btn { display:inline-block; margin-top:15px; padding:10px 15px; background:#3498db; color:#fff; text-decoration:none; border-radius:6px; }
    a.btn:hover { background:#1d6fa5; }
  </style>
</head>
<body>
  <h2>Available Apartments</h2>
  <?php if(!$apartments): ?>
    <p>No apartments available.</p>
  <?php else: ?>
    <table>
      <tr>
        <th>Building</th>
        <th>Unit</th>
        <th>Rent</th>
        <th>Bedrooms</th>
        <th>Bathrooms</th>
        <th>Location</th>
        <th>Owner</th>
      </tr>
      <?php foreach($apartments as $apt): ?>
      <tr>
        <td><?= htmlspecialchars($apt['BuildingName']) ?></td>
        <td><?= htmlspecialchars($apt['UnitNumber']) ?></td>
        <td><?= htmlspecialchars($apt['RentAmount']) ?></td>
        <td><?= htmlspecialchars($apt['Bedrooms']) ?></td>
        <td><?= htmlspecialchars($apt['Bathrooms']) ?></td>
        <td><?= htmlspecialchars($apt['Apt_Street'] . ', ' . $apt['Apt_City'] . ', ' . $apt['Apt_Prov']) ?></td>
        <td><?= htmlspecialchars($apt['FirstName'] . ' ' . $apt['LastName']) ?></td>
      </tr>
      <?php endforeach; ?>
    </table>
  <?php endif; ?>

<a href="index.php" class="btn">Back to Home</a>

</body>
</html>
