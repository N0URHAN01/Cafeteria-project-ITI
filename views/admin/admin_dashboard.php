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
    <style>
        .admin-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
            background: #5c3d2e;
            color: white;
        }
        .admin-info {
            display: flex;
            align-items: center;
        }
        .admin-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .logout-btn {
            background: red;
            color: white;
            border: none;
            padding: 8px 15px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="admin-header">
    <div class="admin-info">
        <img src="../../uploads/admins<?= htmlspecialchars($admin['profile_image']); ?>" alt="Admin">
        <span>Welcome, <?= htmlspecialchars($admin['name']); ?></span>
    </div>
    <form method="POST" action="../../controllers/admin/logout.php">
        <button type="submit" class="logout-btn">Logout</button>
    </form>
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