<?php

require_once __DIR__ . "/../../classes/order/Order.php";
require_once __DIR__ . "/../../utils/validator.php";

$orderModel = new Order();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    
    $order_data = validate_posted_data($_POST);
    $errors = array_merge($errors, $order_data['errors']);

    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    if (in_array($status, ['processing', 'out for delivery', 'done'])) {
        $orderModel->update_order_status($order_id, $status);

          header("Location: ../../views/admin/AllOrders.php?success=Product added successfully");
        exit;
    }else{
        $errors['order_status'] = "invalid status";
        $errors_json = urlencode(json_encode($errors));
        header("Location: ../../views/admin/AllOrders.php?errors=$errors_json");
    }
}