<?php
require_once __DIR__ . "/../db/Database.php";

class UserOrder {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    // Create a new order
    public function createOrder($user_id, $room_id, $notes, $total_price) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO orders (user_id, room_id, total_price, status, notes, created_at) 
                                          VALUES (:user_id, :room_id, :total_price, 'processing', :notes, NOW())");

            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindValue(':room_id', $room_id, PDO::PARAM_INT);
            $stmt->bindValue(':total_price', $total_price, PDO::PARAM_STR);
            $stmt->bindValue(':notes', $notes, PDO::PARAM_STR);

            $stmt->execute();
            return $this->conn->lastInsertId(); // Return the new order ID
        } catch (PDOException $e) {
            die("Error creating order: " . $e->getMessage());
        }
    }

    // Add products to ordered_products table
    public function addOrderedProduct($order_id, $product_id, $quantity, $price) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO ordered_products (order_id, product_id, quantity, price) 
                                          VALUES (:order_id, :product_id, :quantity, :price)");

            $stmt->bindValue(':order_id', $order_id, PDO::PARAM_INT);
            $stmt->bindValue(':product_id', $product_id, PDO::PARAM_INT);
            $stmt->bindValue(':quantity', $quantity, PDO::PARAM_INT);
            $stmt->bindValue(':price', $price, PDO::PARAM_STR);

            $stmt->execute();
        } catch (PDOException $e) {
            die("Error adding product to order: " . $e->getMessage());
        }
    }

    // Retrieve all orders for a specific user
    public function getUserOrders($user_id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC");
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error fetching user orders: " . $e->getMessage());
        }
    }

    // Get details of a specific order
    public function getOrderDetails($order_id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM orders WHERE order_id = :order_id");
            $stmt->bindValue(':order_id', $order_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error fetching order details: " . $e->getMessage());
        }
    }

    // Get ordered products for a specific order
    public function getOrderedProducts($order_id) {
        try {
            $stmt = $this->conn->prepare("SELECT p.name, op.quantity, op.price 
                                          FROM ordered_products op 
                                          JOIN products p ON op.product_id = p.product_id 
                                          WHERE op.order_id = :order_id");
            $stmt->bindValue(':order_id', $order_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error fetching ordered products: " . $e->getMessage());
        }
    }
}
