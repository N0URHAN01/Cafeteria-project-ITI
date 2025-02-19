<?php
require_once '../../classes/db/Database.php';
require_once '../../utils/password-utils.php';
class UsersController {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all users
    public function getAllUsers() {
        $query = "SELECT users.*, rooms.room_number FROM users 
                  LEFT JOIN rooms ON users.room_id = rooms.room_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get single user by ID
    public function getUserById($user_id) {
        $query = "SELECT * FROM users WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update user
    public function updateUser($user_id, $name, $email,$password, $ext, $profile_image = null, $room_id) {

        // Check if the room_id exists before updating the user
        if (!empty($room_id)) {
            $stmt = $this->conn->prepare("SELECT room_id FROM rooms WHERE room_id = ?");
            $stmt->execute([$room_id]);
            
            $room_exists = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$room_exists) {
                die("Error: The selected room does not exist.");
            }
        }

        $query = "UPDATE users SET name = ?, email = ?, room_id = ?, ext = ?, password = ?";
        if ($profile_image) {
            $query .= ", profile_image = ?";
        }
        $query .= " WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $params = [$name, $email, $room_id, $ext, $password];
        if ($profile_image) {
            $params[] = $profile_image;
        }
        $params[] = $user_id;

        return $stmt->execute($params);
   }

    // Delete user
    public function deleteUser($user_id) {
        $query = "DELETE FROM users WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$user_id]);
    }
}
// Database connection
$database = new Database();
$db = $database->connect();
$usersController = new UsersController($db);

// Handling edit , delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $usersController->deleteUser($_POST['user_id']);
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
    $usersController->updateUser($user_id, $name, $email, $password, $ext, $profile_image, $room_id);
    header("Location: ../../views/admin/users.php");
}

?>
