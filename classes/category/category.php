<?php
require_once __DIR__ . '/../db/Database.php';

class Category {
    private $conn;

    public function __construct($conn) {
        if (!$conn) {
            die("Database connection failed in Category class.");
        }
        $this->conn = $conn;
    }

    public function get_all_categories() {
        try {
            $sql = "SELECT category_id, name FROM categories";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($categories)) {
                die("Error: No categories found in database.");
            }

            return $categories;
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }
    }
}
?>
