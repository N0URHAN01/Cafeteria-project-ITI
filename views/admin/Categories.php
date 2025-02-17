<?php
session_start();
require_once __DIR__ . "/../../classes/db/Database.php";
require_once __DIR__ . "/../../controllers/admin/categoryController.php";


// Ensure admin is logged in
if (!isset($_SESSION["is_admin"]) || !isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

$db = new Database();
$conn = $db->connect();

// Fetch admin details
$admin_id = $_SESSION["admin_id"];
$stmt = $conn->prepare("SELECT name, profile_image FROM admins WHERE admin_id = :admin_id");
$stmt->execute(['admin_id' => $admin_id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch categories
$categoryController = new CategoryController();
$categories = $categoryController->getCategories();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Categories</title>
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Roboto"
    />
    <link
      rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
    />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/icon?family=Material+Icons"
    />
    <link
      rel="stylesheet"
      href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
    />
     <!-- fevicon -->
     <link rel="icon" href="images/fevicon.png" type="image/gif" />
    <!-- navbar style sheet -->
    <link href="../../css/adminNavbar.css"   rel="stylesheet" />

    <style>
      body {
        color: #566787;
        background: linear-gradient(135deg, #e3c6a8, #b08968);
        font-family: "Roboto", sans-serif;
      }
      .table-responsive {
        margin: 30px 0;
      }
      .table-wrapper {
        min-width: 1000px;
        background: #fff;
        padding: 20px;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
      }
      .table-title {
        padding-bottom: 10px;
        margin: 0 0 10px;
        min-width: 100%;
      }
      .table-title h2 {
        margin: 8px 0 0;
        font-size: 22px;
      }
      table.table tr th,
      table.table tr td {
        border-color: #e9e9e9;
      }
      table.table-striped tbody tr:nth-of-type(odd) {
        background-color: #fcfcfc;
      }
      table.table-striped.table-hover tbody tr:hover {
        background: #f5f5f5;
      }
      table.table th i {
        font-size: 13px;
        margin: 0 5px;
        cursor: pointer;
      }
      table.table td:last-child {
        width: 130px;
      }
      table.table td a {
        color: #a0a5b1;
        display: inline-block;
        margin: 0 5px;
      }
      table.table td a.view {
        color: #03a9f4;
      }
      table.table td a.edit {
        color: #ffc107;
      }
      table.table td a.delete {
        color: #e34724;
      }
      table.table td i {
        font-size: 19px;
      }
    </style>
    <script>
      $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
      });
      function toggleDropdown() {
          var dropdown = document.getElementById("dropdownMenu");
          dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
      }
    </script>
</head>
<body>

<nav class="navbar navbar-expand-lg " style="background-color:#5c3d2e">
  <a class="navbar-brand" href="#" style="display:inline-block; width:50px; height:50px"><img style="display:inline-block; width:100%; height:100%" src="../../static_images/logo.png" alt=""></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse admin-header " id="navbarSupportedContent">
    <ul class="navbar-nav w-100  mx-2">
      <li class="nav-item px-2  active ">
        <a class="nav-link" href="admin_dashboard.php">Home
        </a>
     </li>
     <li class="nav-item px-2  ">
        <a class="nav-link" href="products.php">Products</a>
     </li>
     <li class="nav-item px-2  ">
        <a class="nav-link" href="manual_order.php">Manual Order</a>
     </li>
     <li class="nav-item px-2  "><a class="nav-link" href="checks.php">Checks</a></li>
     <li class="nav-item px-2  "><a class="nav-link" href="users.php">Users</a></li>
     <li class="nav-item px-2  "><a class="nav-link" href="Categories.php">Categories</a></li>

      <li class=" admin-dropdown ml-md-auto">
        <div class="admin-info" onclick="toggleDropdown()">
            <img src="../../uploads/<?= $admin['profile_image']; ?>" alt="Admin" class="profile-img" />
            <span><?= htmlspecialchars($admin['name']); ?></span>
        </div>
        <ul class="dropdown-menu" id="dropdownMenu">
            <li><a href="../../controllers/admin/logoutController.php">Logout</a></li>
        </ul>
     </li>
    </ul>
  </div>
</nav>
   
<div class="container-xl">
      <div class="table-responsive">
        <div class="table-wrapper">
          <div class="table-title">
            <div class="row d-flex justify-between ">
              <div class="col-sm-10">
                <h2>Categories <b>Details</b></h2>
              </div><button class="btn btn-info col-sm-2 px-2" type="button" data-toggle="modal" 
              data-target="#addcat"> + Add New Category</button>
            </div>
          </div>
          <table class="table table-striped table-hover table-bordered">
            <thead>
              <tr>
                <th>#</th>
                <th>Name <i class="fa fa-sort"></i></th>                
              </tr>
            </thead>
            <tbody>
              <?php
              $categories = $categoryController->getCategories();
              foreach ($categories as $category) {
                  echo "<tr>
                      <td>{$category['category_id']}</td>
                      <td>{$category['name']}</td>
                  </tr>";
              }
              ?>
              </tbody>
              
          </table>
        </div>
      </div>
    </div>
<!-- add caregoryy form start -->
<?php  include 'addCategory.php'; ?>
<!-- add category form end -->

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>


</body>
</html>
