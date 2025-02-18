<?php
session_start();
require_once __DIR__ . "/../../classes/order/Order.php";
require_once __DIR__ . "/../../utils/validator.php";

$order = new Order();
$errors = [];

$who_is_placing_order = null;

if (isset($_SESSION['user_id'])) {
    $who_is_placing_order = 'user';
}

if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
    $who_is_placing_order = 'admin';
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    // validate user input
    $order_data = validate_posted_data($_POST);
    $errors = array_merge($errors, $order_data['errors']);

    $user_id = $_POST["user_id"] ?? null;
    $room_id = $_POST["room_id"] ?? null;
    $products = $_POST["products"] ?? [];

    if (empty($user_id)) {
        $errors['user_id'] = "Select user ";
    }

    if (empty($room_id)) {
        $errors['room_id'] = "select room ";
    }

    if (empty($products)) {
        $errors['products'] = "At least one product must be ordered";
    }

    // check stock availability before proceeding with the order
    foreach ($products as $product) {
        $product_id = $product['product_id'];
        $quantity = $product['quantity'];

        // product details
        $product_data = $order->get_product_details($product_id);
        if (!$product_data || $product_data['stock_quantity'] < $quantity) {
            $errors['products'] = "Not enough stock for product: {$product_data['name']} (requested: {$quantity}, available: {$product_data['stock_quantity']})";
            break;
        }
    }

    if (empty($errors)) {
        try {
            $db = (new Database())->connect();
            $db->beginTransaction();

            // create order
            $order_id = $order->create_order($user_id, $room_id);
            $total_price = 0;

            // add ordered products and calculate total price
            foreach ($products as $product) {
                $product_id = $product['product_id'];
                $quantity = $product['quantity'];

                // get product details
                $product_data = $order->get_product_details($product_id);
                $product_price = $product_data['price'];
                $total_price += $product_price * $quantity;

                // add ordered product
                $order->add_ordered_product($order_id, $product_id, $quantity, $product_price);

                // update stock and product status
                $order->update_product_stock($product_id, $quantity);
                $order->update_product_status($product_id);
            }

            // update total price
            $order->update_order_total($order_id, $total_price);
            $db->commit();

            header("Location: ../../views/{$who_is_placing_order}/orders.php?success=Order placed successfully");
            exit;
        } catch (Exception $e) {
            $db->rollBack();
            error_log("Order error: " . $e->getMessage());
            $errors['order'] = "Failed to place order.";
        }
    }

    $errors_query = http_build_query(['errors' => $errors]);
    header("Location: ../../views/{$who_is_placing_order}/place_order.php?{$errors_query}");
    exit;
}
?>
