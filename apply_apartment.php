<?php
require 'classes/database.php';
$db = new Database();

// Get apartment_id from URL
$apartment_id = $_GET['apartment_id'] ?? null;

if (!$apartment_id) {
    die("<div class='alert alert-danger text-center mt-5'>Apartment not specified.</div>");
}

// Fetch apartment details
$apartment = $db->getApartmentById($apartment_id);

if (!$apartment) {
    die("<div class='alert alert-danger text-center mt-5'>Apartment not found.</div>");
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Apply for Apartment</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="row mb-4">
    <div class="col text-center">
      <h2 class="fw-bold">Rental / Lease Application</h2>
    </div>
  </div>

  <form action="submit_application.php" method="POST" class="bg-white p-4 shadow rounded">

    <!-- Hidden input to send apartment_id -->
    <input type="hidden" name="apartment_id" value="<?= htmlspecialchars($apartment['ApartmentID']) ?>">

    <!-- Apartment Info -->
    <h5 class="mb-3 text-primary">Apartment Details</h5>
    <div class="row mb-3">
      <div class="col-md-8">
        <label class="form-label">Property Address</label>
        <input type="text" class="form-control" name="property_address" readonly 
               value="<?= htmlspecialchars($apartment['Apt_Street'] . ', ' . $apartment['Apt_City'] . ', ' . $apartment['Apt_Prov']) ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">Unit No.</label>
        <input type="text" class="form-control" name="unit_number" readonly value="<?= htmlspecialchars($apartment['UnitNumber']) ?>">
      </div>
    </div>

    <!-- Applicant Info -->
    <h5 class="mb-3 text-primary">Applicant Information</h5>
    <div class="row mb-3">
      <div class="col-md-4">
        <label class="form-label">First Name</label>
        <input type="text" class="form-control" name="first_name" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Middle Initial</label>
        <input type="text" class="form-control" name="middle_initial">
      </div>
      <div class="col-md-4">
        <label class="form-label">Last Name</label>
        <input type="text" class="form-control" name="last_name" required>
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-6">
        <label class="form-label">Date of Birth</label>
        <input type="date" class="form-control" name="dob" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Phone Number</label>
        <input type="text" class="form-control" name="phone" required>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">Email Address</label>
      <input type="email" class="form-control" name="email" required>
    </div>

    <!-- Residence History -->
    <h5 class="mb-3 text-primary">Residence History</h5>
    <div class="mb-3">
      <label class="form-label">Current Address</label>
      <input type="text" class="form-control" name="current_address" required>
    </div>
    <div class="row mb-3">
      <div class="col-md-6">
        <label class="form-label">Dates of Residence (From)</label>
        <input type="date" class="form-control" name="res_from">
      </div>
      <div class="col-md-6">
        <label class="form-label">To</label>
        <input type="date" class="form-control" name="res_to">
      </div>
    </div>
    <div class="row mb-3">
      <div class="col-md-6">
        <label class="form-label">Monthly Rent</label>
        <input type="number" class="form-control" name="monthly_rent">
      </div>
      <div class="col-md-6">
        <label class="form-label">Reason for Moving</label>
        <input type="text" class="form-control" name="reason_moving">
      </div>
    </div>

    <!-- Employment / Financial -->
    <h5 class="mb-3 text-primary">Employment / Financial</h5>
    <div class="mb-3">
      <label class="form-label">Current Employer</label>
      <input type="text" class="form-control" name="employer" required>
    </div>
    <div class="row mb-3">
      <div class="col-md-6">
        <label class="form-label">Position / Title</label>
        <input type="text" class="form-control" name="position">
      </div>
      <div class="col-md-6">
        <label class="form-label">Monthly Income</label>
        <input type="number" class="form-control" name="income" required>
      </div>
    </div>

    <!-- Additional Questions -->
    <h5 class="mb-3 text-primary">Additional Information</h5>
    <div class="mb-3">
      <label class="form-label d-block">Have you ever been evicted?</label>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="evicted" value="Yes"> 
        <label class="form-check-label">Yes</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="evicted" value="No" checked> 
        <label class="form-check-label">No</label>
      </div>
    </div>
    <div class="mb-3">
      <label class="form-label">If yes, explain</label>
      <textarea class="form-control" name="evicted_explain"></textarea>
    </div>

    <div class="mb-3">
      <label class="form-label d-block">Have you ever broken a lease?</label>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="broken_lease" value="Yes"> 
        <label class="form-check-label">Yes</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="broken_lease" value="No" checked> 
        <label class="form-check-label">No</label>
      </div>
    </div>
    <div class="mb-3">
      <label class="form-label">If yes, explain</label>
      <textarea class="form-control" name="lease_explain"></textarea>
    </div>

    <!-- Submit -->
    <div class="text-center">
      <button type="submit" class="btn btn-success px-5">Submit Application</button>
    </div>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
