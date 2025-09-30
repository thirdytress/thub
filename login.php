<?php
session_start();
include 'classes/database.php';
$db = new Database();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['role']; 
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = $db->loginUser($role, $email, $password);

    if ($user) {
        $_SESSION[$role] = $user;
        header("Location: " . ($role === "tenant" ? "dashboard.php" : "owner_dashboard.php"));
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Login - Apartment Hub</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height:100vh;">

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-5 col-lg-4">
        <div class="card shadow-sm">
          <div class="card-body p-4">
            <h2 class="text-center mb-4">Login</h2>


        <?php if($error): ?>
          <div class="alert alert-danger py-2 text-center"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
          <div class="mb-3">
            <label for="role" class="form-label">Login as</label>
            <select class="form-select" name="role" id="role" required>
              <option value="tenant">Tenant</option>
              <option value="owner">Owner</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>

          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>

          <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>

        <div class="text-center mt-3">
          <a href="index.php">Home</a> | <a href="register.php">Tenant Register</a>
        </div>
      </div>
    </div>
  </div>
</div>
```

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
