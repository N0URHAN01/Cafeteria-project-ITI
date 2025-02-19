<?php
session_start();
require_once __DIR__ . "/../../classes/order/Order.php";
require_once __DIR__ . "/../../middleware/authMiddleware.php";

requireAuthAdmin();
$orderModel = new Order();
$orders = $orderModel->get_all_orders();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .order-card {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .order-header span {
            font-size: 14px;
            color: #555;
        }
        .order-items {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 15px;
        }
        .item {
            text-align: center;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #f9f9f9;
            flex: 1 1 calc(25% - 15px);
        }
        .item p {
            margin: 5px 0;
            font-size: 14px;
            color: #333;
        }
        .total-price {
            font-weight: bold;
            font-size: 16px;
            color: #333;
            margin-top: 15px;
        }
        .status {
            font-weight: bold;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            display: inline-block;
            font-size: 14px;
        }
        .processing {
            background-color: #ffc107;
        }
        .out-for-delivery {
            background-color: #0d6efd;
        }
        .done {
            background-color: #198754;
        }
        .form-select {
            width: 200px;
            display: inline-block;
            margin-right: 10px;
        }
        .btn-primary {
            background-color: #0d6efd;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            font-size: 14px;
        }
        .btn-primary:hover {
            background-color: #0b5ed7;
        }
    </style>
</head>
<body>

<?php include "navbar.php"; ?>
    <div class="container mt-5">
        <h2 class="mb-4">Admin Order Management</h2>
        

        <?php foreach ($orders as $order): ?>
            <div class="order-card">
                <div class="order-header">
                    <span><i class="fas fa-calendar-alt"></i> <?= htmlspecialchars($order['created_at']) ?></span>
                    <span><i class="fas fa-user"></i> <?= htmlspecialchars($order['user_name']) ?></span>
                    <span><i class="fas fa-door-open"></i> <?= htmlspecialchars($order['room_number']) ?></span>
                </div>
                <div class="order-items">
                    <?php $items = $orderModel->get_order_items($order['order_id']); ?>
                    <?php foreach ($items as $item): ?>
                        <div class="item">
                            <span class="badge bg-secondary"><?= htmlspecialchars($item['price']) ?> LE</span>
                            <p><?= htmlspecialchars($item['product_name']) ?></p>
                            <span>x<?= htmlspecialchars($item['quantity']) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="mt-3 total-price">
                    <i class="fas fa-receipt"></i> Total: EGP <?= htmlspecialchars($order['total_price']) ?>
                </div>
                <div class="mt-3">
                    <strong>Status:</strong> 
                    <span class="status <?= strtolower(str_replace(' ', '-', $order['status'])) ?>">
                        <?= htmlspecialchars($order['status']) ?>
                    </span>
                </div>
                <form action="../../controllers/order/AllOrdersController.php" method="POST" class="mt-3">
                    <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['order_id']) ?>">
                    <label for="status" class="form-label"><strong>Update Status:</strong></label>
                    <select name="status" id="status" class="form-select">
                        <option value="processing" <?= $order['status'] == 'processing' ? 'selected' : '' ?>>Processing</option>
                        <option value="out for delivery" <?= $order['status'] == 'out for delivery' ? 'selected' : '' ?>>Out for Delivery</option>
                        <option value="done" <?= $order['status'] == 'done' ? 'selected' : '' ?>>Done</option>
                    </select>
                    <button type="submit" class="btn btn-primary mt-2">Update</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

</body>
</html>