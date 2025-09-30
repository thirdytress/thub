<?php
session_start();
?>

<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Apartment Hub</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

  <!-- Header -->

  <header class="bg-dark text-white py-4 mb-4">
    <div class="container text-center">
      <h1 class="mb-0">Apartment Hub</h1>
    </div>
  </header>

  <!-- Navigation -->

  <nav class="mb-4">
    <div class="container text-center">
      <?php if(isset($_SESSION['tenant'])): ?>
        <span class="me-2">Hello, <?php echo htmlspecialchars($_SESSION['tenant']['FirstName']); ?></span>
        <a href="dashboard.php" class="btn btn-primary btn-sm">Dashboard</a>
        <a href="logout.php" class="btn btn-danger btn-sm ms-2">Logout</a>
      <?php elseif(isset($_SESSION['owner'])): ?>
        <span class="me-2">Hello Owner, <?php echo htmlspecialchars($_SESSION['owner']['FirstName']); ?></span>
        <a href="owner_dashboard.php" class="btn btn-primary btn-sm">Owner Dashboard</a>
        <a href="logout.php" class="btn btn-danger btn-sm ms-2">Logout</a>
      <?php else: ?>
        <a href="register.php" class="btn btn-primary btn-sm">Tenant Register</a>
        <a href="login.php" class="btn btn-secondary btn-sm ms-2">Login</a>
        <a href="apartment.php" class="btn btn-success btn-sm ms-2">View Apartments</a>
      <?php endif; ?>
    </div>
  </nav>

  <!-- Hero Section -->

  <section class="py-5 bg-primary text-white text-center mb-4">
    <div class="container">
      <h2 class="fw-bold">Find Your Next Home Easily</h2>
      <p class="lead mb-0">Manage tenants, owners, leases, and utilities in one simple system.</p>
    </div>
  </section>

  <!-- Main -->

  <main class="container bg-white p-4 rounded shadow-sm">
    <h3 class="mb-3">About</h3>
    <p>
      Welcome to <strong>Apartment Hub</strong> — a simple apartment management demo system.  
      You can manage tenants, owners, leases, and utilities with ease.
    </p>
  </main>

  <!-- Footer -->

  <footer class="text-center py-3 mt-4 text-muted">
    &copy; <?php echo date("Y"); ?> Apartment Hub. All Rights Reserved.
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
