<?php
require_once __DIR__ . "/../../classes/db/Database.php";


class Category{
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
    // Function to fetch all categories
    public function deleteCategory($category_id){
        try{
            
            $query = "DELETE FROM categories WHERE category_id = :category_id";
            $stmt = $this->db->connect()->prepare($query);
            $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
            return $stmt->execute() ? true : false;
        }catch (PDOException $e) {
            error_log("Database query error: " . $e->getMessage());
            return false;
        }

    }
}

?>
