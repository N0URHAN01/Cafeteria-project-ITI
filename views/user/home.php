<?php
require_once __DIR__ . "/../../middleware/authMiddleware.php";

session_start();

requireAuthUser();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
        .user-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
            background: #5c3d2e;
            color: white;
        }
        .user-info {
            display: flex;
            align-items: center;
        }
        .user-info img {
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

<div class="user-header">
    <div class="user-info">
        <img src="../../uploads/<?= $_SESSION['user_image']; ?>" alt="User">
        <span>Welcome, <?= htmlspecialchars($_SESSION['user_name']); ?></span>
    </div>
    <form method="POST" action="../../controllers/user/logout.php">
        <button type="submit" class="logout-btn">Logout</button>
    </form>
</div>

<h2>Welcome to the Home Page!</h2>

</body>
</html>
