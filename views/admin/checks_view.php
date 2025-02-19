<?php
session_start();
require_once __DIR__ . "/../../middleware/authMiddleware.php";
require_once __DIR__ . "/../../classes/user/user.php";
require_once __DIR__ . "/../../controllers/admin/CheckOrder.php";

requireAuthAdmin();
$orderController = new CheckOrderController();
$orders = $orderController->get_all_orders_with_items(); 
$all_users = $orderController->get_all_users(); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date_from = $_POST['date_from'] ?? null;
    $date_to = $_POST['date_to'] ?? null;
    $user_id = $_POST['user_id'] ?? null;

    if ($date_from && $date_to && $user_id) {
        $orders = $orderController->filter_orders_by_date_and_user($date_from, $date_to, $user_id);
    } elseif ($date_from && $date_to) {
        $orders = $orderController->filter_orders_by_date($date_from, $date_to);
    } elseif ($user_id) {
        $orders = $orderController->filter_orders_by_user($user_id);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 8px;
        }
        .btn-filter {
            margin-top: 32px;
        }
        .order-collapse {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4 text-center">📜 Order History</h2>

    <!-- Filter Form inside a Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title">🔍 Filter Orders</h5>
            <form method="POST">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">From Date:</label>
                        <input type="date" name="date_from" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">To Date:</label>
                        <input type="date" name="date_to" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Select User:</label>
                        <select name="user_id" class="form-select">
                            <option value="">All Users</option>
                            <?php foreach ($all_users as $user) : ?>
                                <option value="<?= $user['user_id'] ?>"><?= htmlspecialchars($user['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-outline-primary w-100 btn-filter">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
            <thead class="table-dark">
                <tr>
                    <th>🆔 Order ID</th>
                    <th>👤 User Name</th>
                    <th>🏠 Room</th>
                    <th>💰 Total Price</th>
                    <th>📌 Status</th>
                    <th>📅 Created At</th>
                    <th>📜 Details</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($orders) > 0) : ?>
                    <?php foreach ($orders as $order) : ?>
                        <tr>
                            <td><?= $order['order_id'] ?></td>
                            <td><?= htmlspecialchars($order['user_name']) ?></td>
                            <td><?= htmlspecialchars($order['room_number']) ?></td>
                            <td><?= number_format($order['total_price'], 2) ?> EGP</td>
                            <td>
                                <span class="badge bg-<?= $order['status'] === 'completed' ? 'success' : 'warning' ?>">
                                    <?= ucfirst($order['status']) ?>
                                </span>
                            </td>
                            <td><?= $order['created_at'] ?></td>
                            <td>
                                <button class="btn btn-outline-info btn-sm" data-bs-toggle="collapse" data-bs-target="#order-<?= $order['order_id'] ?>" aria-expanded="false">
                                    📄 View Items
                                </button>
                            </td>
                        </tr>
                        <tr id="order-<?= $order['order_id'] ?>" class="collapse order-collapse">
                            <td colspan="7">
                                <table class="table table-sm table-bordered mt-2">
                                    <thead class="table-light">
                                        <tr>
                                            <th>🛒 Product Name</th>
                                            <th>📦 Quantity</th>
                                            <th>💲 Price (EGP)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($order['items'] as $item) : ?>
                                            <tr>
                                                <td><?= htmlspecialchars($item['product_name']) ?></td>
                                                <td><?= $item['quantity'] ?></td>
                                                <td><?= number_format($item['price'], 2) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">No orders found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
