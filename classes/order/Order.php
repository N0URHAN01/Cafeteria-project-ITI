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


    public function get_all_orders() {
        try {
            $stmt = $this->db->prepare(
                "SELECT o.order_id, o.user_id, u.name AS user_name, r.room_number, o.total_price, o.status, o.created_at 
                 FROM orders o
                 JOIN users u ON o.user_id = u.user_id
                 JOIN rooms r ON o.room_id = r.room_id
                 ORDER BY o.created_at DESC"
            );
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching orders: " . $e->getMessage());
        }
    }

    public function get_order_items($order_id) {
        try {
            $stmt = $this->db->prepare(
                "SELECT p.name AS product_name, op.quantity, op.price 
                 FROM ordered_products op
                 JOIN products p ON op.product_id = p.product_id
                 WHERE op.order_id = :order_id"
            );
            $stmt->execute(['order_id' => $order_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching order items: " . $e->getMessage());
        }
    }

    // update order status
    public function update_order_status($order_id, $status) {
        try {
            $stmt = $this->db->prepare("UPDATE orders SET status = :status WHERE order_id = :order_id");
            return $stmt->execute(['status' => $status, 'order_id' => $order_id]);
        } catch (PDOException $e) {
            throw new Exception("Error updating order status: " . $e->getMessage());
        }
    }

}
?>
