<?php
session_start();
include 'classes/database.php';
$db = new Database();

// Admin check
if (!isset($_SESSION['owner'])) {
    die("<div class='alert alert-danger text-center mt-5'>Access denied. Admins only.</div>");
}

// Handle status update
if (isset($_POST['action'], $_POST['id'])) {
    $action = $_POST['action'];
    $id = $_POST['id'];

    // Only allow valid actions
    if (in_array($action, ['Approved', 'Rejected'])) {
        $db->updateApplicationStatus($id, $action);
    }

    header("Location: applicants.php");
    exit;
}

// Fetch all applicants
$applicants = $db->getAllApplications();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Apartment Applicants</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h3 class="mb-4 text-center fw-bold">Apartment Applicants</h3>

    <?php if (!$applicants): ?>
        <div class="alert alert-info text-center">No applications yet.</div>
    <?php else: ?>
        <div class="table-responsive shadow-sm">
            <table class="table table-bordered table-hover align-middle bg-white">
                <thead class="table-dark">
                <tr>
                    <th>Tenant Name</th>
                    <th>Contact</th>
                    <th>Employment</th>
                    <th>Income</th>
                    <th>Apartment</th>
                    <th>Status</th>
                    <th>Applied At</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($applicants as $app): ?>
                    <tr>
                        <!-- Tenant Name -->
                        <td>
                            <?= htmlspecialchars(
                                !empty($app['FirstName']) || !empty($app['LastName']) 
                                    ? $app['FirstName'] . ' ' . $app['LastName'] 
                                    : ($app['TenantName'] ?? 'Guest')
                            ) ?>
                        </td>

                        <!-- Contact -->
                        <td>
                            <?= htmlspecialchars(
                                $app['PhoneNumber'] ?? ($app['Contact'] ?? 'N/A')
                            ) ?>
                        </td>

                        <!-- Employment -->
                        <td>
                            <?= htmlspecialchars(
                                ($app['Employer'] ?? 'N/A') . ' - ' . ($app['Position'] ?? 'N/A')
                            ) ?>
                        </td>

                        <!-- Income -->
                        <td>
                            <?= htmlspecialchars($app['Income'] ?? 'N/A') ?>
                        </td>

                        <!-- Apartment -->
                        <td>
                            <?= htmlspecialchars(
                                ($app['BuildingName'] ?? 'N/A') . ' - Unit ' . ($app['UnitNumber'] ?? 'N/A')
                            ) ?>
                        </td>

                        <!-- Status -->
                        <td>
                            <span class="badge 
                                <?= $app['Status'] == 'Pending' ? 'bg-warning' : ($app['Status'] == 'Approved' ? 'bg-success' : 'bg-danger') ?>">
                                <?= $app['Status'] ?>
                            </span>
                        </td>

                        <!-- Applied At -->
                        <td>
                            <?= date('M d, Y H:i', strtotime($app['AppliedAt'] ?? $app['CreatedAt'] ?? date('Y-m-d H:i:s'))) ?>
                        </td>

                        <!-- Action -->
                        <td>
                            <?php if (($app['Status'] ?? '') === "Pending"): ?>
                                <form method="post" class="d-flex gap-1">
                                    <input type="hidden" name="id" value="<?= $app['ApplicationID'] ?>">
                                    <button type="submit" name="action" value="Approved" class="btn btn-sm btn-success">Approve</button>
                                    <button type="submit" name="action" value="Rejected" class="btn btn-sm btn-danger">Reject</button>
                                </form>
                            <?php else: ?>
                                <em>No action</em>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
