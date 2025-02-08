<?php
require_once "connaction.php";
require_once "Database.php";

$db = new Database($pdo);

if (isset($_GET["id"])) {
    $user_id = $_GET["id"];
    $user = $db->select("users");

    
    $current_user = null;
    foreach ($user as $u) {
        if ($u['id'] == $user_id) {
            $current_user = $u;
            break;
        }
    }

    
    if (!$current_user) {
        header("Location: user-table.php");
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    
    $name = $_POST["name"];
    $email = $_POST["email"];
    $room_no = $_POST["room_no"];
    $ext = $_POST["ext"];

    
    $file_path = $current_user['profile_picture']; 

    if (isset($_FILES["profile_picture"]) && $_FILES["profile_picture"]['error'] === UPLOAD_ERR_OK) {
        $profile_picture = $_FILES["profile_picture"];
        $allowed_extensions = ["jpg", "jpeg", "png", "gif"];
        $file_extension = strtolower(pathinfo($profile_picture["name"], PATHINFO_EXTENSION));

        if (in_array($file_extension, $allowed_extensions)) {
            $upload_dir = "uploads/";
            $file_name = uniqid() . "_" . basename($profile_picture["name"]);
            $file_path = $upload_dir . $file_name;
            move_uploaded_file($profile_picture["tmp_name"], $file_path);
        } else {
            $errors['profile_picture'] = "Allowed formats: JPG, JPEG, PNG, GIF!";
        }
    }

    
    $db->update("users", $_POST["id"], [
        "name" => $name,
        "email" => $email,
        "room_no" => $room_no,
        "ext" => $ext,
        "profile_picture" => $file_path
    ]);

    header("Location: user-table.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Edit User</h2>
    <form action="update.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $current_user['id'] ?>">

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($current_user['name']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($current_user['email']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Room No.</label>
            <input type="text" name="room_no" class="form-control" value="<?= htmlspecialchars($current_user['room_no']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Extension</label>
            <input type="text" name="ext" class="form-control" value="<?= htmlspecialchars($current_user['ext']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Profile Picture</label>
            <input type="file" name="profile_picture" class="form-control">
            <img src="<?= $current_user['profile_picture'] ?>" width="100" height="100" class="mt-2" alt="Current Profile Picture">
        </div>

        <button type="submit" class="btn btn-success">Update User</button>
    </form>
</div>
</body>
</html>
