<?php


require_once '../../classes/db/Database.php';
require_once '../../controllers/admin/usersController.php';
require_once __DIR__ . "/../../middleware/authMiddleware.php";

session_start();

// // Check if admin is logged in
requireAuthAdmin();




// Create database connection
$database = new Database();
$db = $database->connect(); // Ensure connect() is used

// Fetch admin details
$admin_id = $_SESSION["admin_id"];
$stmt = $db->prepare("SELECT name, profile_image FROM admins WHERE admin_id = :admin_id");
$stmt->execute(['admin_id' => $admin_id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

// Pass the database connection to UsersController
$usersController = new UsersController($db);

// Fetch all users
$users = $usersController->getAllUsers();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <title>Admin Dashboard - Users</title>
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
    <!--  -->
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


      .admin-dropdown {
    position: relative;
}

.admin-info {
    display: flex;
    align-items: center;
    cursor: pointer;

    border-radius: 25px;
}

.profile-img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 10px;
    border: 2px solid #b08968;
}

.dropdown-menu {
    display: none;
    position: absolute;
    top: 50px;
    right: 0;
    background: #fff;
    border-radius: 8px;
    list-style: none;
    padding: 10px 0;
    width: 70px;
    text-align: center;
    min-width:auto !important;
}

.dropdown-menu li {
    padding: 5px;
}

.dropdown-menu li a {
    text-decoration: none;
    color: #b08968;
    font-weight: bold;
    display:inline-block;
    width: 100%;
    text-align:left;
}

.dropdown-menu li:hover {
    background: #f4f4f4;
    cursor: pointer;
}
.admin-header .dropdown-menu  li a:hover {
  color: rgb(75, 24, 18) !important;
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
            <div class="row">
              <div class="col-sm-8">
                <h2>Customer <b>Details</b></h2>
              </div>
            </div>
          </div>
          <table class="table table-striped table-hover table-bordered">
            <thead>
              <tr>
                <th>#</th>
                <th>Name <i class="fa fa-sort"></i></th>
                <th>Room</th>
                <th>Image</th>
                <th>Ext.</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $users = $usersController->getAllUsers();
              foreach ($users as $user) {
                  echo "<tr>
                      <td>{$user['user_id']}</td>
                      <td>{$user['name']}</td>
                      <td>{$user['room_number']}</td>
                      <td><img src='../../uploads/{$user['profile_image']}' width='50'></td>
                      <td>{$user['ext']}</td>
                      <td>
                          <a href='userDetails.php?id={$user['user_id']}' class='view'><i class='material-icons'>&#xE417;</i></a>
                          <a href='editUser.php?id={$user['user_id']}' class='edit'><i class='material-icons'>&#xE254;</i></a>
                          <form action='../../controllers/admin/usersController.php' method='POST' style='display:inline;'>
                              <input type='hidden' name='user_id' value='{$user['user_id']}'>
                              <input type='hidden' name='action' value='delete'>
                              <button type='submit' class='delete' style='border:none; background:none;'><i class='material-icons'>&#xE872;</i></button>
                          </form>
                      </td>
                  </tr>";
              }
              ?>
              </tbody>
              
          </table>
        </div>
      </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>


  </body>
</html>
