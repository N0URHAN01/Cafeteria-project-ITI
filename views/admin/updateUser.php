<?php
// session_start();
require_once __DIR__ . "/../../classes/db/Database.php";
require_once __DIR__ . "/../../classes/admin/room.php";
require_once '../../classes/admin/users.php';

require_once __DIR__ . "/../../middleware/authMiddleware.php";
// require_once '../../classes/admin/users.php';
$room = new Room();

// Check if admin is logged in
requireAuthAdmin();

$db = new Database();
$conn = $db->connect();
$usersModal = new Users($conn);

// Fetch admin details
$admin_id = $_SESSION["admin_id"];
$stmt = $conn->prepare("SELECT name, profile_image FROM admins WHERE admin_id = :admin_id");
$stmt->execute(['admin_id' => $admin_id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

// Get all rooms
$all_rooms = $room->get_all_rooms();
// Get user ID from GET request
if (!isset($_GET['user_id']) || empty($_GET['user_id'])) {
    die("User ID not provided.");
}
$user_id = $_GET['user_id'];
// Fetch single user
$user = $usersModal->getUserById($user_id);
if (!$user) {
    die("User not found.");
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - update User</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="../../css/global_style.css"   rel="stylesheet" />

    <link href="../../css/adminNavbar.css"   rel="stylesheet" />

    <style>


        .sidebar {
            position: fixed;
            z-index:99999;
            top: 0;
            left: 0;
            bottom: 0;
            width: 250px;
            background-color: #7E5A3C; 
            color: white;
            /* padding-top: 30px; */
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
            background-color: #D76F32; 
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
            height: 100%;
        }

        
     
       
        .custom-card {
            border-radius: 15px;
            padding: 20px;
            width: 60%;
            max-width: 100%;
            height: auto;
            margin: auto;
        }
        .form-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .card-body {
            padding: 15px;
        }
        .form-control-sm {
            font-size: 0.85rem;
            padding: 0.75rem;
            border: none;
            border-bottom: 1px solid #ddd;
            transition: all 0.3s ease;
            background-color: transparent;
        }
        .form-control-sm:focus {
            outline: none;
            border-color: #7E5A3C; 
        }
        .form-label {
            font-size: 1rem;
            margin-bottom: 5px;
            display: inline-block;
        }
        .input-container {
            position: relative;
            margin-bottom: 20px;
            width: 100%;
            display: flex;
            align-items: center;
        }
        .input-container i {
            margin-left: 10px; 
            color: #aaa;
            font-size: 1.2rem;
        }
        .input-container input,
        .input-container select {
            padding-left: 10px;
            width: 100%;
            font-size: 1rem;
            border-bottom: 1px solid #ddd;
            padding-right: 40px; 
        }
        .input-container select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            padding-right: 30px; 
        }
        .text-dark {
            color: #333333 !important; 
        } 
        @media (max-width: 768px) {
            .form-container {
                width: 90%;
            }
            .main-content {
                margin-left: 0;
           }
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

</head>
<body>

<!-- Sidebar -->
<div class="sidebar pt-3">
<a class="navbar-brand" href="#" style="display:inline-block; width:60px; padding:0 !important; text-align:center"><img style="display:inline-block; width:100%; height:100%" src="../../static_images/logo.png" alt=""></a>

    <a href="admin_dashboard.php">Home</a>
    <a href="products.php">Product</a>
    <a href="users.php">Users</a>
    <a href="#">Manual Order</a>
    
    <a href="#">Checks</a>
    <a href="Categories.php">Categories</a>
</div>


<!-- Main Content -->
<div class="main-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 col-xl-11">
                <div class="card text-black shadow custom-card">
                    <div class="card-body">

                        <!-- Form -->
                        <div class="form-container">
                            <p class="text-center h3 fw-bold mb-3">Update User</p>

                            <form method="POST" action="../../controllers/admin/usersController.php" enctype="multipart/form-data">
                                <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['user_id']); ?>">

                                <!-- Full Name -->
                                <div class="input-container">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" name="name" class="form-control form-control-sm" id="name" required placeholder=" " value="<?= htmlspecialchars($user['name']); ?>">
                                    <i class="fas fa-user"></i>
                                </div>

                                <!-- Email -->
                                <div class="input-container">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control form-control-sm" id="email" required placeholder=" " value="<?= htmlspecialchars($user['email']); ?>">
                                    <i class="fas fa-envelope"></i>
                                </div>

                                <!-- Password (optional update) -->
                                <div class="input-container">
                                    <label for="password" class="form-label">New Password (Leave blank if unchanged)</label>
                                    <input type="password" name="password" class="form-control form-control-sm" id="password" placeholder=" ">
                                    <i class="fas fa-lock"></i>
                                </div>

                                <!-- Extension -->
                                <div class="input-container">
                                    <label for="ext" class="form-label">Extension</label>
                                    <input type="text" name="ext" class="form-control form-control-sm" id="ext" placeholder=" " value="<?= htmlspecialchars($user['ext']); ?>">
                                    <i class="fas fa-phone"></i>
                                </div>

                                <!-- Profile Image (Preview Current Image) -->
                                <div class="input-container">
                                    <label for="profile_image" class="form-label">Profile Image</label>
                                    <?php if (!empty($user['profile_image'])): ?>
                                        <img src="../../uploads/users/<?= htmlspecialchars($user['profile_image']); ?>" alt="User Image" width="80" height="80">
                                    <?php endif; ?>
                                    <input type="file" name="profile_image" class="form-control form-control-sm" id="profile_image">
                                    <i class="fas fa-image"></i>
                                </div>

                                <!-- Room Selection -->
                                <div class="input-container">
                                    <label for="room_id" class="form-label">Room</label>
                                    <select name="room_id" class="form-select form-select-sm" id="room_id" required>
                                        <option value="">Select a Room</option>
                                        <?php foreach ($all_rooms as $room): ?>
                                            <option value="<?= $room['room_id']; ?>"
                                             <?= ($room['room_id'] == $user['room_id']) ? 'selected' : ''; ?>>
                                                <?= $room['room_number']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>

                                    <i class="fas fa-building"></i>
                                </div>

                                <div class="d-flex justify-content-center">
                                    <button type="submit" name="update_user" class="btn btn-add-user px-4">Update User</button>
                                </div>
                            </form>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
