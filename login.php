
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
  <style>
    body {
      font-family: Arial, sans-serif; 
      background: #f4f6f9; 
      display:flex; 
      justify-content:center; 
      align-items:center; 
      height:100vh;
    }
    .login-box {
      background:#fff; 
      padding:25px; 
      border-radius:10px; 
      box-shadow:0 4px 8px rgba(0,0,0,0.1); 
      width:320px;
    }
    h2 {
      margin-bottom:15px; 
      color:#2c3e50;
    }
    label {
      display:block; 
      margin-top:10px; 
      font-weight:bold;
    }
    input, select {
      width:100%; 
      padding:10px; 
      margin-top:5px; 
      border:1px solid #ccc; 
      border-radius:6px;
    }
    button {
      margin-top:15px; 
      padding:10px; 
      width:100%; 
      border:none; 
      background:#3498db; 
      color:#fff; 
      border-radius:6px; 
      cursor:pointer;
    }
    button:hover { background:#1d6fa5; }
    .error { color:red; margin-top:10px; }
  </style>
</head>
<body>
  <div class="login-box">
    <h2>Login</h2>
    <form method="POST">
      <label for="role">Login as:</label>
      <select name="role" required>
        <option value="tenant">Tenant</option>
        <option value="owner">Owner</option>
      </select>

      <label for="email">Email:</label>
      <input type="email" name="email" required>

      <label for="password">Password:</label>
      <input type="password" name="password" required>

      <button type="submit">Login</button>
    </form>
    <?php if($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
  </div>
</body>
</html>

