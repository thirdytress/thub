<?php
include "classes/db.php";
$database = new Database();
$conn = $database->connect();

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $database->registerTenant($name, $email, $password);
    echo "Registration successful! <a href='login.php'>Login here</a>";
}
?>

<form method="POST">
    <input type="text" name="name" placeholder="Full Name" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Register</button>
</form>
