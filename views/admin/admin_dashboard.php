<?php
session_start();
require_once __DIR__ . "/../../classes/db/Database.php";
require_once __DIR__ . "/../../classes/admin/room.php";
require_once __DIR__ . "/../../middleware/authMiddleware.php";


$room = new Room();

// Check if admin is logged in using auth middleware 
requireAuthAdmin();

$db = new Database();
$conn = $db->connect();

// Fetch admin details
$admin_id = $_SESSION["admin_id"];
$stmt = $conn->prepare("SELECT name, profile_image FROM admins WHERE admin_id = :admin_id");
$stmt->execute(['admin_id' => $admin_id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

// get rooms to let admin select room for the user 

$all_rooms = $room->get_all_rooms();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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

    </style>
        <script>

function toggleDropdown() {
    var dropdown = document.getElementById("dropdownMenu");
    dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
}

    </script>
</head>
<body>
<div class="admin-header  d-flex flex-row">
    <ul class="navbar-nav w-100 d-flex flex-row mx-2">
    <li class="nav-item px-2  "><a class="nav-link" href="admin_dashboard.php">Home</a></li>

      <li class="nav-item px-2  ">
        <a class="nav-link" href="products.php">Products</a>
      </li>
      <li class="nav-item px-2  ">
        <a class="nav-link" href="manual_order.php">Manual Order</a>
      </li>
      <li class="nav-item px-2  "><a class="nav-link" href="checks.php">Checks</a></li>
      <li class="nav-item px-2  "><a class="nav-link" href="users.php">Users</a></li>




    </ul>
      <div class=" admin-dropdown ml-auto">
        <div class="admin-info" onclick="toggleDropdown()">
            <img src="../../uploads/<?= $admin['profile_image']; ?>" alt="Admin" class="profile-img" />
            <span><?= htmlspecialchars($admin['name']); ?></span>
        </div>
        <ul class="dropdown-menu" id="dropdownMenu">
            <li><a href="../../controllers/admin/logoutController.php">Logout</a></li>
        </ul>
      </div>

    </div>

<h2>Add New User</h2>
<form method="POST" action="../../controllers/admin/addUserController.php" enctype="multipart/form-data">
    <label>Name:</label>
    <input type="text" name="name" required>
    
    <label>Email:</label>
    <input type="email" name="email" required>
    
    <label>Password:</label>
    <input type="password" name="password" required>
    
    <label>Confirm Password:</label>
    <input type="password" name="confirmPassword" required>
    
    <label>Extension:</label>
    <input type="text" name="ext">
    
    <label>Profile Image:</label>
    <input type="file" name="profile_image">

    <!-- Room Selection -->
    <label>Room:</label>
    <select name="room_id" required>
        <option value="">Select a Room</option>
        <?php if (!empty($all_rooms)): ?>
            <?php foreach ($all_rooms as $room): ?>
                <option value="<?= htmlspecialchars($room['room_id']); ?>">
                    <?= htmlspecialchars($room['room_number']); ?>
                </option>
            <?php endforeach; ?>
        <?php else: ?>
            <option value="">No rooms available</option>
        <?php endif; ?>
    </select>
    
    <button type="submit">Add User</button>
</form>

</body>
</html>