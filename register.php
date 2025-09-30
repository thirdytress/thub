<?php
session_start();
require 'classes/database.php';
$db = new Database();

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = trim($_POST['first'] ?? '');
    $lname = trim($_POST['last'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$fname || !$lname || !$email || !$password) {
        $errors[] = 'Please fill required fields.';
    }

    if (empty($errors)) {
        $ok = $db->registerTenant($fname, $lname, $email, $phone, $password);
        if ($ok) {
            $success = 'Registration successful. You can now log in.';
        } else {
            $errors[] = 'Registration failed (email may already exist).';
        }
    }
}
?>

<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Tenant Registration - Apartment Hub</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height:100vh;">

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm">
          <div class="card-body p-4">
            <h2 class="text-center mb-4">Tenant Registration</h2>


        <!-- Error messages -->
        <?php foreach($errors as $e): ?>
          <div class="alert alert-danger py-2"><?= htmlspecialchars($e) ?></div>
        <?php endforeach; ?>

        <!-- Success message -->
        <?php if($success): ?>
          <div class="alert alert-success py-2"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <!-- Form -->
        <form method="post" novalidate>
          <div class="mb-3">
            <label for="first" class="form-label">First Name</label>
            <input type="text" class="form-control" id="first" name="first" required>
          </div>
          <div class="mb-3">
            <label for="last" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="last" name="last" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>
          <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone">
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>

        <div class="text-center mt-3">
          <a href="index.php">Home</a> | <a href="login.php">Login</a>
        </div>
      </div>
    </div>
  </div>
</div>


  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
