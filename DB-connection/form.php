<?php
require_once "connaction.php";
require_once "Database.php";

$db = new Database($pdo);
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $room_no = $_POST["room_no"];
    $ext = $_POST["ext"];
    $profile_picture = $_FILES["profile_picture"];

    // Validation
    if (empty($name) || !preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $errors['name'] = "Name must contain only letters and spaces!";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format!";
    }
    if (!preg_match("/^[a-z0-9_]{8}$/", $password)) {
        $errors['password'] = "Password must be 8 characters (letters, numbers, underscore).";
    }
    if ($password !== $confirm_password) {
        $errors['confirm_password'] = "Passwords do not match!";
    }

    // Profile picture
    $file_path = null;
    if ($profile_picture['error'] === UPLOAD_ERR_OK) {
        $allowed_extensions = ["jpg", "jpeg", "png", "gif"];
        $file_extension = strtolower(pathinfo($profile_picture["name"], PATHINFO_EXTENSION));
        if (!in_array($file_extension, $allowed_extensions)) {
            $errors['profile_picture'] = "Allowed formats: JPG, JPEG, PNG, GIF!";
        } else {
            $upload_dir = "uploads/";
            $file_name = uniqid() . "_" . basename($profile_picture["name"]);
            $file_path = $upload_dir . $file_name;
            move_uploaded_file($profile_picture["tmp_name"], $file_path);
        }
    }

    // Insert data
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $db->insert("users", ["name", "email", "password", "room_no", "ext", "profile_picture"], [$name, $email, $hashed_password, $room_no, $ext, $file_path]);

        header("Location: user-table.php");
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ADD user</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card mx-auto shadow p-4" style="max-width: 500px;">
            <h2 class="text-center mb-4">Add User</h2>
            <form action="form.php" method="POST" enctype="multipart/form-data">
                
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" required>
                    <?php if (isset($errors['name'])) echo "<div class='text-danger'>{$errors['name']}</div>"; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                    <?php if (isset($errors['email'])) echo "<div class='text-danger'>{$errors['email']}</div>"; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                    <?php if (isset($errors['password'])) echo "<div class='text-danger'>{$errors['password']}</div>"; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                    <?php if (isset($errors['confirm_password'])) echo "<div class='text-danger'>{$errors['confirm_password']}</div>"; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label">Room No.</label>
                    <input type="text" name="room_no" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Extension</label>
                    <input type="text" name="ext" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Profile Picture</label>
                    <input type="file" name="profile_picture" class="form-control">
                    <?php if (isset($errors['profile_picture'])) echo "<div class='text-danger'>{$errors['profile_picture']}</div>"; ?>
                </div>

                <button type="submit" class="btn btn-primary w-100">Add User</button>
            </form>
        </div>
    </div>
</body>
</html>

