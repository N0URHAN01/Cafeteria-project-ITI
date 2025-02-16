<?php
require_once '../../classes/db/Database.php';

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

    // Add new user
    public function addUser($name, $email, $password, $profile_image, $room_id, $ext) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO users (name, email, password, profile_image, room_id, ext) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$name, $email, $hashed_password, $profile_image, $room_id, $ext]);
    }

    // Update user
    public function updateUser($user_id, $name, $email, $room_id, $ext, $profile_image = null) {
        $query = "UPDATE users SET name = ?, email = ?, room_id = ?, ext = ?";
        if ($profile_image) {
            $query .= ", profile_image = ?";
        }
        $query .= " WHERE user_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $params = [$name, $email, $room_id, $ext];
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

// Handling requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $usersController->addUser($_POST['name'], $_POST['email'], $_POST['password'], $_FILES['profile_image']['name'], $_POST['room_id'], $_POST['ext']);
                header("Location: ../views/users.php");
                break;
            case 'edit':
                $usersController->updateUser($_POST['user_id'], $_POST['name'], $_POST['email'], $_POST['room_id'], $_POST['ext'], $_FILES['profile_image']['name'] ?? null);
                header("Location: ../views/users.php");
                break;
            case 'delete':
                $usersController->deleteUser($_POST['user_id']);
                header("Location: ../views/users.php");
                break;
        }
    }
}
?>
