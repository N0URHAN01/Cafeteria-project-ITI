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
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <title>Admin Dashboard - user details</title>
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
        .container {
            max-width: 600px;
            background: white;
            padding: 20px;
            margin-top: 50px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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
        .profile-img1 {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: block;
            margin: 0 auto 15px;
            border: 3px solid #b08968;
        }
        .user-info {
            text-align: center;
        }
        .user-info h3 {
            margin-bottom: 10px;
        }
        .user-info p {
            font-size: 18px;
            margin-bottom: 5px;
        }
    </style>
        <script>
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
            <img src="../../uploads/users/<?= $admin['profile_image']; ?>" alt="Admin" class="profile-img" />
            <span><?= htmlspecialchars($admin['name']); ?></span>
        </div>
        <ul class="dropdown-menu" id="dropdownMenu">
            <li><a href="../../controllers/admin/logoutController.php">Logout</a></li>
        </ul>
     </li>
    </ul>
  </div>
</nav>
    <div class="container">
        <h2 class="text-center">User Details</h2>
        <img src="../../uploads/users/<?= htmlspecialchars($user['profile_image']); ?>" alt="User Image" class="profile-img">
        <div class="user-info">
            <h3><?= htmlspecialchars($user['name']); ?></h3>
            <p><strong>Ext:</strong> <?= htmlspecialchars($user['ext']); ?></p>
            <p><strong>Room Number:</strong> <?= htmlspecialchars($user['room_number'] ?? 'N/A'); ?></p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

</body>
</html>
