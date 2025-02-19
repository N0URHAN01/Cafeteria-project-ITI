<?php
require_once '../../classes/db/Database.php';
require_once '../../utils/password-utils.php';
require_once '../../classes/admin/users.php';

// Database connection
$database = new Database();
$db = $database->connect();
$usersModal = new Users($db);

// Handling edit , delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $usersModal->deleteUser($_POST['user_id']);
    header("Location: ../../views/admin/users.php");
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_user"])) {
    $user_id = $_POST["user_id"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $ext = $_POST["ext"];
    $room_id = $_POST["room_id"];
    $password = !empty($_POST["password"]) ?  hash_password($_POST["password"],$email) : null;
    // Handle profile image upload
    if (!empty($_FILES["profile_image"]["name"])) {
        $targetDir = "../../uploads/users/";
        $fileName = basename($_FILES["profile_image"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        move_uploaded_file($_FILES["profile_image"]["tmp_name"], $targetFilePath);
        $profile_image = $fileName;
    } else {
        $profile_image = null;
    }
    // Call update function
    $usersModal->updateUser($user_id, $name, $email, $password, $ext, $profile_image, $room_id);
    header("Location: ../../views/admin/users.php");
}

?>
