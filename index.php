
<?php
session_start();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Apartment Hub</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0; padding: 0;
    }
    body {
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background: #f4f6f9;
      color: #333;
      line-height: 1.6;
    }
    header {
      background: #2c3e50;
      color: #fff;
      padding: 20px;
      text-align: center;
      border-radius: 0 0 12px 12px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.2);
    }
    header h1 {
      font-size: 2.2rem;
      margin-bottom: 5px;
    }
    nav {
      margin-top: 10px;
    }
    nav a {
      display: inline-block;
      margin: 0 8px;
      padding: 8px 15px;
      text-decoration: none;
      background: #3498db;
      color: #fff;
      border-radius: 6px;
      transition: background 0.3s ease;
    }
    nav a:hover {
      background: #1d6fa5;
    }
    main {
      max-width: 900px;
      margin: 40px auto;
      background: #fff;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 6px 12px rgba(0,0,0,0.1);
    }
    main h3 {
      margin-bottom: 12px;
      color: #2c3e50;
    }
    footer {
      text-align: center;
      padding: 20px;
      color: #777;
      font-size: 0.9rem;
    }
  </style>
</head>
<body>
  <header>
    <h1>🏢 Apartment Hub</h1>
    <?php if(isset($_SESSION['tenant'])): ?>
      <nav>
        <span>Hello, <?php echo htmlspecialchars($_SESSION['tenant']['FirstName']); ?></span>
        <a href="tenant_dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
      </nav>
    <?php elseif(isset($_SESSION['owner'])): ?>
      <nav>
        <span>Hello Owner, <?php echo htmlspecialchars($_SESSION['owner']['FirstName']); ?></span>
        <a href="owner_dashboard.php">Owner Dashboard</a>
        <a href="logout.php">Logout</a>
      </nav>
    <?php else: ?>
      <nav>
        <a href="register.php">Tenant Register</a>
        <a href="login.php">Login</a>
        <a href="apartment.php" class="btn">View Apartments</a>
      </nav>
    <?php endif; ?>
  </header>

  <main>
    <h3>About</h3>
    <p>
      Welcome to <strong>Apartment Hub</strong> — a simple apartment management demo system.  
      You can manage tenants, owners, leases, and utilities with ease.  
    </p>
  </main>

  <footer>
    &copy; <?php echo date("Y"); ?> Apartment Hub. All Rights Reserved.
  </footer>
</body>
</html>

