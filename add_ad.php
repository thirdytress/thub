<?php
session_start();
if(!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

include "classes/db.php";
$database = new Database();
$conn = $database->connect();

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if($database->addAdmin($name, $email, $password)) {
        echo "New admin added successfully! <a href='admin_dashboard.php'>Go back</a>";
    } else {
        echo "Error adding admin!";
    }
}
?>
<h2>Add New Admin</h2>
<form method="POST">
    <input type="text" name="name" placeholder="Full Name" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Add Admin</button>
</form>
<a href="logout.php">Logout</a>
