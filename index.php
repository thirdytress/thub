<?php
session_start();
include "classes/db.php";
$database = new Database();
$conn = $database->connect();

$error = "";

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $role = $_POST['role']; // tenant or admin
    $email = $_POST['email'];
    $password = $_POST['password'];

    if($role == "tenant") {
        $tenant = $database->loginTenant($email, $password);
        if($tenant) {
            $_SESSION['tenant'] = $tenant;
            header("Location: tenant_dashboard.php");
            exit();
        } else {
            $error = "Invalid tenant login!";
        }
    } elseif($role == "admin") {
        $admin = $database->loginAdmin($email, $password);
        if($admin) {
            $_SESSION['admin'] = $admin;
            header("Location: ad_dashboard.php");
            exit();
        } else {
            $error = "Invalid admin login!";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Apartment Hub</title>
</head>
<body>
    <h1>Welcome to Apartment Hub</h1>
    <p>Please login below:</p>

    <?php if(!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST">
        <select name="role" required>
            <option value="tenant">Tenant</option>
            <option value="admin">Admin</option>
        </select><br><br>
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button type="submit">Login</button>
    </form>

    <br>
    <a href="register.php">Register as Tenant</a>
</body>
</html>
