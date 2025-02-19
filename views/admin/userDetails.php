<?php
require_once '../../classes/db/Database.php';
require_once '../../controllers/admin/usersController.php';
require_once __DIR__ . "/../../middleware/authMiddleware.php";

// Check if admin is logged in
requireAuthAdmin();

$db = new Database();
$conn = $db->connect();

// Fetch admin details
$admin_id = $_SESSION["admin_id"];
$stmt = $conn->prepare("SELECT name, profile_image FROM admins WHERE admin_id = :admin_id");
$stmt->execute(['admin_id' => $admin_id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

// Get user ID from GET request
if (!isset($_GET['user_id']) || empty($_GET['user_id'])) {
    die("User ID not provided.");
}
$user_id = $_GET['user_id'];

$usersController = new UsersController($conn);

// Fetch single user
$user = $usersController->getUserById($user_id);

if (!$user) {
    die("User not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Admin Dashboard - user Details</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />

    <!-- favicon -->
    <link rel="icon" href="../../static_images/favicon.ico" type="image/ico" />
    <link href="../../css/global_style.css" rel="stylesheet" />
    <link href="../../css/adminNavbar.css" rel="stylesheet" />
    <link href="../../css/table.css" rel="stylesheet" />
    
    <style>
      body {
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;
        background: #f5f5f0;
      }
      .sidebar {
        position: fixed;
        z-index: 99999;
        top: 0;
        left: 0;
        bottom: 0;
        width: 250px;
        background-color: #7e5a3c;
        color: white;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
      }
      .sidebar .admin-info {
        text-align: center;
        margin-bottom: 20px;
      }
      .sidebar .admin-info img {
        border-radius: 50%;
        width: 60px;
        height: 60px;
      }
      .sidebar .admin-info p {
        margin-top: 10px;
      }
      .sidebar a {
        color: white;
        text-decoration: none;
        padding: 10px;
        display: block;
        font-size: 16px;
      }
      .sidebar a:hover {
        background-color: #d76f32;
      }
      .btn-add-user {
        background-color: #7e5a3c;
        color: white;
        border-radius: 25px;
        padding: 0.6rem 1.2rem;
        font-size: 1rem;
        font-weight: bold;
        transition: background-color 0.3s ease;
      }
      .btn-add-user:hover {
        background-color: #d76f32;
      }
      .btn-logout {
        background-color: #5c3d2e;
        color: white;
        border-radius: 25px;
        padding: 0.6rem 1.2rem;
        font-size: 1rem;
        font-weight: bold;
        transition: background-color 0.3s ease;
      }
      .btn-logout:hover {
        background-color: rgb(201, 43, 38);
      }
      .admin-dropdown {
        position: relative;
      }
      .wrapper {
        width: calc(50% - 250px);
        margin: 50px auto 20px;
        text-align: center;
        padding: 10px 5px;
        color: rgb(92,91,46);
        height:500px;
        background: #fff;
        box-shadow: 0 0 10px rgba(55, 55, 55, 0.5);
        border-radius: 4px;
      }

      .header {
        font-size: 30px;
        padding: 5px;
        font-family: "PT Sans Narrow", sans-serif;
      }

      .profile-pic {
        width: 150px;
        height: 150px;
        margin: 10px auto;
        margin-top: -30px; 
        border-radius: 50%;
        border: 2px solid #fff;
        transition: all 0.3s ease-in-out;
      }
      .profile-pic:hover {
        cursor: pointer;
        transform: translateY(-10px) scale(1.3);
      }
      .info {
        padding-top:4%;
        padding-left:5%;

      }
      .name {
        font-size: 22px;
        font-weight: 500;
      }

      .username {
        font-size: 18px;
        font-weight: 300;
      }
      </style>
  
  </head>
  <body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="admin-info">
            <!-- Admin Info (profile image) -->
            <img src="../../uploads/<?= htmlspecialchars($admin['profile_image']); ?>" alt="Admin Image">
            <p><?= htmlspecialchars($admin['name']); ?></p>
        </div>
        <a href="admin_dashboard.php">Home</a>
        <a href="products.php">Product</a>
        <a href="users.php">Users</a>
        <a href="ManualOrder.php">Manual Order</a>
        
        <a href="#">Checks</a>
        <a href="Categories.php">Categories</a>

        <form method="POST" action="../../controllers/admin/logout.php">
            <button type="submit" class="btn btn-logout w-100 mt-3">Logout</button>
        </form>
    </div>

    <div class="main-content">
    
      <div class="wrapper">
      <div class="profile-pic" style="background:url('../../uploads/users/<?= htmlspecialchars($user['profile_image']); ?>');background-size: cover;">
      </div>
      <div class="info">
        <div class="name"><?= htmlspecialchars($user['name']); ?></div>
        <div class="username pt-2">Ext: <?= htmlspecialchars($user['ext']); ?></div>
        <div class="pt-2">Room: <?= htmlspecialchars($user['room_id']); ?></div>
      </div>
      </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>
