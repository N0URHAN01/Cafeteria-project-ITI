<?php
require_once __DIR__ . "/../../classes/db/Database.php";

class Users {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }
    //check email exist for update
    public function updated_user_email_exist($email, $current_user_id) {
        try {
            $query = "SELECT user_id FROM users WHERE email = :email AND user_id != :current_user_id LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':current_user_id', $current_user_id, PDO::PARAM_INT);
            $stmt->execute();
    
            return $stmt->rowCount() > 0; // true if email exists (excluding the current user)
        } catch (PDOException $e) {
            error_log("Database query error: " . $e->getMessage());
            return false;
        }
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