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
<html>
<head><meta charset="utf-8"><title>Tenant Register</title></head>
<body>
  <h2>Tenant Registration</h2>
  <?php foreach($errors as $e) echo '<p style="color:red">'.htmlspecialchars($e).'</p>'; ?>
  <?php if($success) echo '<p style="color:green">'.htmlspecialchars($success).'</p>'; ?>
  <form method="post">
    <label>First name<br><input name="first" required></label><br>
    <label>Last name<br><input name="last" required></label><br>
    <label>Email<br><input name="email" type="email" required></label><br>
    <label>Phone<br><input name="phone"></label><br>
    <label>Password<br><input name="password" type="password" required></label><br>
    <button type="submit">Register</button>
  </form>
  <p><a href="index.php">Home</a> | <a href="login.php">Login</a></p>
</body>
</html>
