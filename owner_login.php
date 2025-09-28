<?php
session_start();
require 'classes/database.php';
$db = new Database();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) $errors[] = 'Provide email and password.';
    else {
        $owner = $db->loginOwner($email, $password);
        if ($owner) {
            unset($owner['OwnerPass']);
            $_SESSION['owner'] = $owner;
            header('Location: owner_dashboard.php'); exit;
        } else $errors[] = 'Invalid credentials.';
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Owner Login</title></head>
<body>
  <h2>Owner Login</h2>
  <?php foreach($errors as $e) echo '<p style="color:red">'.htmlspecialchars($e).'</p>'; ?>
  <form method="post">
    <label>Email<br><input name="email" type="email" required></label><br>
    <label>Password<br><input name="password" type="password" required></label><br>
    <button type="submit">Login</button>
  </form>
  <p><a href="index.php">Home</a></p>
</body>
</html>
