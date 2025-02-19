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
            return $this->conn->lastInsertId(); 
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
            $stmt = $this->conn->prepare("SELECT p.name, p.image_url, op.quantity, op.price 
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
    

    public function getUserOrdersByDate($user_id, $from_date, $to_date, $limit, $offset) {
        try {
            $query = "SELECT order_id, created_at, status, total_price 
                      FROM orders 
                      WHERE user_id = :user_id 
                      AND (:from_date IS NULL OR created_at >= :from_date) 
                      AND (:to_date IS NULL OR created_at <= :to_date) 
                      ORDER BY created_at DESC 
                      LIMIT :limit OFFSET :offset";
    
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':from_date', $from_date, PDO::PARAM_STR);
            $stmt->bindParam(':to_date', $to_date, PDO::PARAM_STR);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
    
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return [];
        }
    }
    
    
    public function countUserOrders($user_id, $from_date, $to_date) {
        try {
            $query = "SELECT COUNT(*) as total FROM orders WHERE user_id = :user_id AND created_at BETWEEN :from_date AND :to_date";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':from_date', $from_date, PDO::PARAM_STR);
            $stmt->bindParam(':to_date', $to_date, PDO::PARAM_STR);
            $stmt->execute();
    
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return 0;
        }
    }

    
    public function cancelOrder($order_id, $user_id) {
        try {
            // Check if order status is processing
            $stmt = $this->conn->prepare("SELECT status FROM orders WHERE order_id = :order_id AND user_id = :user_id");
            $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($order && $order['status'] === 'processing') {
                // Delete order from ordered_products
                $stmt = $this->conn->prepare("DELETE FROM ordered_products WHERE order_id = :order_id");
                $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
                $stmt->execute();
    
                // Delete order from orders
                $stmt = $this->conn->prepare("DELETE FROM orders WHERE order_id = :order_id");
                $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
                $stmt->execute();
    
                return true; // Success
            } else {
                return false; // Cannot cancel
            }
        } catch (PDOException $e) {
            error_log("Cancel Order Error: " . $e->getMessage());
            return false;
        }
    }
    

    public function getLatestCompletedOrder() {
        $sql = "SELECT * FROM orders WHERE status = 'done' ORDER BY created_at DESC LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
}
