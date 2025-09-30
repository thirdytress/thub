<?php
session_start();
if (!isset($_SESSION['owner'])) {
    header('Location: owner_login.php'); exit;
}
$owner = $_SESSION['owner'];
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Owner Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { overflow-x: hidden; }
    #sidebar { min-height: 100vh; }
    #sidebar .nav-link.active { background-color: #0d6efd; }
    #content-area { padding: 20px; }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Owner Dashboard</a>
      <div class="d-flex text-white">
        <span class="me-3">Welcome, <?php echo htmlspecialchars($owner['FirstName'].' '.$owner['LastName']); ?></span>
        <a class="btn btn-outline-light btn-sm" href="logout.php">Logout</a>
      </div>
    </div>
  </nav>

  <div class="container-fluid">
    <div class="row">
      
      <!-- Sidebar -->
      <div id="sidebar" class="col-2 bg-dark text-white p-3">
        <ul class="nav flex-column">
          <li class="nav-item"><a href="#" class="nav-link text-white" data-page="apartments.php">Apartments</a></li>
          <li class="nav-item"><a href="#" class="nav-link text-white" data-page="applicants.php">Apartment Applicants</a></li>
          <li class="nav-item"><a href="#" class="nav-link text-white" data-page="tenants.php">Tenants</a></li>
          <li class="nav-item"><a href="#" class="nav-link text-white" data-page="lease.php">Create Lease</a></li>
          <li class="nav-item"><a href="#" class="nav-link text-white" data-page="payments.php">Payments</a></li>
          <li class="nav-item"><a href="#" class="nav-link text-white" data-page="parking.php">Parking</a></li>
        </ul>
      </div>

      <!-- Content area -->
      <div id="content-area" class="col-10">
        <h4>Please select an option from the sidebar</h4>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS + jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- AJAX Loader -->
  <script>
    $(document).ready(function(){
      $(".nav-link").click(function(e){
        e.preventDefault();
        $(".nav-link").removeClass("active");
        $(this).addClass("active");

        var page = $(this).data("page");
        $("#content-area").html("<p>Loading...</p>");
        $.get(page, function(data){
          $("#content-area").html(data);
        });
      });
    });
  </script>

</body>
</html>
