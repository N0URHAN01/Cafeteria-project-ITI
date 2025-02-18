<?php
require_once __DIR__ . "/../db/Database.php";

class Order {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    // Create a new order
    public function create_order($user_id, $room_id) {
        try {
            $stmt = $this->db->prepare("INSERT INTO orders (user_id, room_id, total_price) VALUES (:user_id, :room_id, 0)");
            $stmt->execute(['user_id' => $user_id, 'room_id' => $room_id]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Error creating order: " . $e->getMessage());
        }
    }

    // get product details (price ,quantity)
    public function get_product_details($product_id) {
        try {
            $stmt = $this->db->prepare("SELECT price, stock_quantity FROM products WHERE product_id = :product_id");
            $stmt->execute(['product_id' => $product_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching product details: " . $e->getMessage());
        }
    }

    // create ordered product to the order
    public function add_ordered_product($order_id, $product_id, $quantity, $price) {
        try {
            $total_price = $price * $quantity;

            $stmt = $this->db->prepare("INSERT INTO ordered_products (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)");
            return $stmt->execute([
                'order_id' => $order_id,
                'product_id' => $product_id,
                'quantity' => $quantity,
                'price' => $total_price
            ]);
        } catch (PDOException $e) {
            throw new Exception("Error adding ordered product: " . $e->getMessage());
        }
    }

    // update product stock after an order
    public function update_product_stock($product_id, $quantity) {
        try {
            $stmt = $this->db->prepare("UPDATE products SET stock_quantity = stock_quantity - :quantity WHERE product_id = :product_id");
            return $stmt->execute(['quantity' => $quantity, 'product_id' => $product_id]);
        } catch (PDOException $e) {
            throw new Exception("Error updating product stock: " . $e->getMessage());
        }
    }

    // uodate product status to 'out of stock' if quantity is zero
    public function update_product_status($product_id) {
        try {
            $stmt = $this->db->prepare("UPDATE products SET status = 'out of stock' WHERE product_id = :product_id AND stock_quantity = 0");
            return $stmt->execute(['product_id' => $product_id]);
        } catch (PDOException $e) {
            throw new Exception("Error updating product status: " . $e->getMessage());
        }
    }

    // update the total price of the order
    public function update_order_total($order_id, $total_price) {
        try {
            $stmt = $this->db->prepare("UPDATE orders SET total_price = :total_price WHERE order_id = :order_id");
            return $stmt->execute(['total_price' => $total_price, 'order_id' => $order_id]);
        } catch (PDOException $e) {
            throw new Exception("Error updating order total: " . $e->getMessage());
        }
    }
}
?>
