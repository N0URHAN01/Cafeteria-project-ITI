<?php
session_start();
require_once __DIR__ . "/../../middleware/authMiddleware.php";
requireAuthUser(); // Ensure user is authenticated

require_once __DIR__ . "/../../classes/order/UserOrder.php";

if (!isset($_GET['order_id'])) {
    echo "<p>Invalid order request.</p>";
    exit;
}

$order_id = $_GET['order_id'];
$orderModel = new UserOrder();
$orderDetails = $orderModel->getOrderedProducts($order_id);
$orderInfo = $orderModel->getOrderDetails($order_id);

if (!$orderDetails) {
    echo "<p>No details available.</p>";
    exit;
}

// Display order information
$total_price = 0;
echo "<h5>Order #{$order_id} - Status: <span class='badge bg-info'>" . htmlspecialchars($orderInfo['status']) . "</span></h5>";
echo "<ul class='list-group'>";

// Display ordered products
foreach ($orderDetails as $item) {
    $item_total = $item['price'] * $item['quantity'];
    $total_price += $item_total;
    echo "<li class='list-group-item d-flex align-items-center'>";
    echo '<img src="../../uploads/products/' . htmlspecialchars($item['image_url']) . '" 
    alt="' . htmlspecialchars($item['name']) . '" 
    class="me-3" 
    style="width: 50px; height: 50px; object-fit: cover;">';


    echo "<span><strong>" . htmlspecialchars($item['name']) . "</strong> - Quantity: " . htmlspecialchars($item['quantity']) . " - Price: EGP " . htmlspecialchars($item['price']) . "</span>";
    echo "</li>";
}
echo "</ul>";

// Display total price
echo "<h5 class='mt-3'>Total Price: EGP " . htmlspecialchars($total_price) . "</h5>";
