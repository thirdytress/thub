<?php
session_start();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Apartment Hub</title>
  <style>body{font-family:Arial,Helvetica,sans-serif;max-width:900px;margin:30px auto;padding:10px}</style>
</head>
<body>
  <h1>Apartment Hub</h1>

  <?php if(isset($_SESSION['tenant'])): ?>
    <p>Hello, <?php echo htmlspecialchars($_SESSION['tenant']['FirstName']); ?> —
      <a href="tenant_dashboard.php">Dashboard</a> | <a href="logout.php">Logout</a></p>
  <?php elseif(isset($_SESSION['owner'])): ?>
    <p>Hello Owner, <?php echo htmlspecialchars($_SESSION['owner']['FirstName']); ?> —
      <a href="owner_dashboard.php">Owner Dashboard</a> | <a href="logout.php">Logout</a></p>
  <?php else: ?>
    <p><a href="register.php">Tenant Register</a> | <a href="login.php">Tenant Login</a> | <a href="owner_login.php">Owner Login</a></p>
  <?php endif; ?>

  <hr>
  <h3>About</h3>
  <p>Sample apartment management demo. Configure DB in <code>classes/database.php</code> and import your schema.</p>
</body>
</html>
