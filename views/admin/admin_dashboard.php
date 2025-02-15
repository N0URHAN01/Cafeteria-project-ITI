<?php
session_start();
require_once __DIR__ . "/../../classes/db/Database.php";

// Check if admin is logged in
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
        <img src="../../uploads/<?= $admin['profile_image']; ?>" alt="Admin">
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
    
   
    
    <label>Extension:</label>
    <input type="text" name="ext">
    
    <label>Profile Image:</label>
    <input type="file" name="profile_image">
    
    <button type="submit">Add User</button>
</form>

</body>
</html>
