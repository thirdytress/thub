<?php
session_start();
if(!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}
$admin = $_SESSION['admin'];
?>
<h1>Admin Dashboard</h1>
<p>Welcome, <?php echo $admin['name']; ?>!</p>
<a href="add_ad.php">Add New Admin</a> | 
<a href="logout.php">Logout</a>
