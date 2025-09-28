<?php
session_start();
if(!isset($_SESSION['tenant'])) {
    header("Location: index.php");
    exit();
}
$tenant = $_SESSION['tenant'];
?>
<h1>Tenant Dashboard</h1>
<p>Welcome, <?php echo $tenant['name']; ?>!</p>
<a href="logout.php">Logout</a>
