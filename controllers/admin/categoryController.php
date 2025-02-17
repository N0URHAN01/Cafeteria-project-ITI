<?php
session_start();
require_once __DIR__ . "/../../classes/db/Database.php";

// Ensure the user is an admin
if (!isset($_SESSION["is_admin"]) || !isset($_SESSION["admin_id"])) {
    header("Location: ../../views/admin/login.php");
    exit;
}

class CategoryController {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->connect();
    }

    // Function to add a new category
    public function addCategory($name) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO categories (name) VALUES (:name)");
            $stmt->execute(['name' => $name]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Function to fetch all categories
    public function getCategories() {
        $stmt = $this->conn->query("SELECT * FROM categories ORDER BY category_id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["category_name"])) {
    $categoryController = new CategoryController();
    $name = trim($_POST["category_name"]);

    if (!empty($name)) {
        $success = $categoryController->addCategory($name);
        if ($success) {
            $_SESSION["success"] = "Category added successfully!";
        } else {
            $_SESSION["error"] = "Failed to add category. It may already exist.";
        }
    } else {
        $_SESSION["error"] = "Category name is required!";
    }

    header("Location: ../../views/admin/Categories.php");
    exit;
}
?>
