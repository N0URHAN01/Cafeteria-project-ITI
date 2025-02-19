<?php
session_start();
require_once __DIR__ . "/../../middleware/authMiddleware.php";
requireAuthUser(); // Ensure user is authenticated

require_once __DIR__ . "/../../classes/order/UserOrder.php";

if (!isset($_SESSION['user_id']) || !isset($_POST['order_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized request']);
    exit;
}

$user_id = $_SESSION['user_id'];
$order_id = $_POST['order_id'];

$orderModel = new UserOrder();
$result = $orderModel->cancelOrder($order_id, $user_id);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Order canceled successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Cannot cancel this order']);
}
?>
