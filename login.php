<?php
session_start();
include "classes/db.php";
$database = new Database();
$conn = $database->connect();

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $tenant = $database->loginTenant($email, $password);
    if($tenant) {
        $_SESSION['tenant'] = $tenant;
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Invalid email or password!";
    }
}
?>

<form method="POST">
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Login</button>
</form>
