<?php
require 'classes/database.php';
$db = new Database();
session_start();

// Read POST data safely
$apartmentId = $_POST['apartment_id'] ?? null;

if (!$apartmentId) {
    die("<div class='alert alert-danger text-center mt-5'>Apartment not specified.</div>");
}

// Tenant ID is optional (null for guest)
$tenantId = $_SESSION['tenant_id'] ?? null;

$currentAddress = $_POST['current_address'] ?? '';
$monthlyRent = $_POST['monthly_rent'] ?? '';
$reasonMoving = $_POST['reason_moving'] ?? '';
$employer = $_POST['employer'] ?? '';
$position = $_POST['position'] ?? '';
$income = $_POST['income'] ?? '';
$evicted = $_POST['evicted'] ?? 'No';
$evictedExplain = $_POST['evicted_explain'] ?? '';
$brokenLease = $_POST['broken_lease'] ?? 'No';
$leaseExplain = $_POST['lease_explain'] ?? '';

// Submit application
$db->submitApplication(
    $tenantId,
    $apartmentId,
    $currentAddress,
    $monthlyRent,
    $reasonMoving,
    $employer,
    $position,
    $income,
    $evicted,
    $evictedExplain,
    $brokenLease,
    $leaseExplain
);

echo "<script>alert('Application submitted successfully!'); window.location.href='index.php';</script>";
