<?php
session_start();
require_once __DIR__ . "/../../classes/db/Database.php";
require_once __DIR__ . "/../../classes/admin/room.php";
require_once __DIR__ . "/../../middleware/authMiddleware.php";

$room = new Room();
requireAuthAdmin();

$db = new Database();
$conn = $db->connect();

//  admin details
$admin_id = $_SESSION["admin_id"];
$stmt = $conn->prepare("SELECT name, profile_image FROM admins WHERE admin_id = :admin_id");
$stmt->execute(['admin_id' => $admin_id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

// Get all rooms
$all_rooms = $room->get_all_rooms();

//  error messages and old input data
$errors = [];
$old_data = [];
$success = "";

if (isset($_GET['errors'])) {
    $errors = json_decode(urldecode($_GET['errors']), true);
}

if (isset($_GET['old'])) {
    $old_data = json_decode(urldecode($_GET['old']), true);
}

if (isset($_GET['success'])) {
    $success = urldecode($_GET['success']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Add User</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .container-wrapper {
            max-width: 600px; 
            margin: auto;
            padding: 30px;
        }
        .form-container {
            padding: 30px;
            border-radius: 15px;
            background: #f9f9f9;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
        }
        .input-field {
            font-size: 18px;
            height: 45px;
        }
        .btn-sm {
            background-color: #8B5E3B !important; 
            color: #fff;
            font-size: 14px;
            padding: 10px 15px;
            border-radius: 5px;
            width: 100%;
        }
    </style>
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container container-wrapper">
    <div class="form-container">
        <h2 class="text-center">Add User</h2>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success text-center">
                <?= htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="../../controllers/admin/addUserController.php" enctype="multipart/form-data">
            <div class="mb-3 input-group">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
                <input type="text" name="name" class="form-control input-field" placeholder="Full Name" 
                       value="<?= htmlspecialchars($old_data['name'] ?? ''); ?>" required>
            </div>

            <div class="mb-3 input-group">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                <input type="email" name="email" class="form-control input-field" placeholder="Email" 
                       value="<?= htmlspecialchars($old_data['email'] ?? ''); ?>" required>
            </div>

            <div class="mb-3 input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" name="password" class="form-control input-field" placeholder="Password" required>
            </div>

            <div class="mb-3 input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" name="confirmPassword" class="form-control input-field" placeholder="Confirm Password" required>
            </div>

            <div class="mb-3 input-group">
                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                <input type="text" name="ext" class="form-control input-field" placeholder="Extension" 
                       value="<?= htmlspecialchars($old_data['ext'] ?? ''); ?>">
            </div>

            <div class="mb-3 input-group">
                <span class="input-group-text"><i class="fas fa-image"></i></span>
                <input type="file" name="profile_image" class="form-control input-field">
            </div>

            <div class="mb-3 input-group">
                <span class="input-group-text"><i class="fas fa-building"></i></span>
                <select name="room_id" class="form-select input-field" required>
                    <option value="">Select a Room</option>
                    <?php foreach ($all_rooms as $room): ?>
                        <option value="<?= htmlspecialchars($room['room_id']); ?>" 
                            <?= (isset($old_data['room_id']) && $old_data['room_id'] == $room['room_id']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($room['room_number']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="text-center">
            <button type="submit" class="btn btn-md px-4 py-2" style="background-color: #8B5E3B; color: #fff; font-size: 16px; border-radius: 6px;">
    <i class="fas fa-user-plus"></i> Add User
</button>



            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
