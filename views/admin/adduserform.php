<?php
session_start();
require_once __DIR__ . "/../../classes/db/Database.php";
require_once __DIR__ . "/../../classes/admin/room.php";
require_once __DIR__ . "/../../middleware/authMiddleware.php";

$room = new Room();

// Check if admin is logged in
requireAuthAdmin();

$db = new Database();
$conn = $db->connect();

// Fetch admin details
$admin_id = $_SESSION["admin_id"];
$stmt = $conn->prepare("SELECT name, profile_image FROM admins WHERE admin_id = :admin_id");
$stmt->execute(['admin_id' => $admin_id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

// Get all rooms
$all_rooms = $room->get_all_rooms();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Add User</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
     <!-- favicon -->
    <link rel="icon" href="../../static_images/favicon.ico" type="image/ico" />
    <link href="../../css/global_style.css" rel="stylesheet" />
    <link href="../../css/adminNavbar.css" rel="stylesheet" />
    <style>
        .main-content {
            /* margin-left: 250px; */
            padding: 20px;
            height: 100%;
        }

        
        .btn-add-user {
            background-color: #7E5A3C !important; 
            color: white !important; 
            border-radius: 25px;
            padding: 0.6rem 1.2rem;
            font-size: 1rem;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .btn-add-user:hover {
            background-color: #D76F32 !important;  
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
    </style>
</head>
<body>


<?php include "navbar.php"; ?>

<!-- Main Content -->
<div class="main-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 col-xl-11">
                <div class="card text-black shadow custom-card">
                    <div class="card-body">

                        <!-- Form -->
                        <div class="form-container">
                            <p class="text-center h3 fw-bold mb-3">Add User</p>

                            <form method="POST" action="../../controllers/admin/addUserController.php" enctype="multipart/form-data">

                                <!-- Full Name -->
                                <div class="input-container">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" name="name" class="form-control form-control-sm" id="name" required placeholder=" ">
                                    <i class="fas fa-user"></i>
                                </div>

                                <!-- Email -->
                                <div class="input-container">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control form-control-sm" id="email" required placeholder=" ">
                                    <i class="fas fa-envelope"></i>
                                </div>

                                <!-- Password -->
                                <div class="input-container">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control form-control-sm" id="password" required placeholder=" ">
                                    <i class="fas fa-lock"></i>
                                </div>

                                <!-- Confirm Password -->
                                <div class="input-container">
                                    <label for="confirmPassword" class="form-label">Confirm Password</label>
                                    <input type="password" name="confirmPassword" class="form-control form-control-sm" id="confirmPassword" required placeholder=" ">
                                    <i class="fas fa-lock"></i>
                                </div>

                                <!-- Extension -->
                                <div class="input-container">
                                    <label for="ext" class="form-label">Extension</label>
                                    <input type="text" name="ext" class="form-control form-control-sm" id="ext" placeholder=" ">
                                    <i class="fas fa-phone"></i>
                                </div>

                                <!-- Profile Image -->
                                <div class="input-container">
                                    <label for="profile_image" class="form-label">Profile Image</label>
                                    <input type="file" name="profile_image" class="form-control form-control-sm" id="profile_image" placeholder=" ">
                                    <i class="fas fa-image"></i>
                                </div>

                                <!-- Room -->
                                <div class="input-container">
                                    <label for="room_id" class="form-label">Room</label>
                                    <select name="room_id" class="form-select form-select-sm" id="room_id" required>
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
                                    <i class="fas fa-building"></i>
                                </div>

                                <div class="d-flex justify-content-center">
                                    <button type="submit" class="btn btn-add-user px-4">Add User</button>
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
