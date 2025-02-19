<?php
session_start();
require_once __DIR__ . "/../../classes/order/UserOrder.php";
require_once __DIR__ . "/../../middleware/authMiddleware.php";
requireAuthUser();

$user_id = $_SESSION['user_id'];
$orderModel = new UserOrder();

// Pagination setup
$orders_per_page = 3;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $orders_per_page;

// Date filter setup
$date_from = isset($_GET['date_from']) ? $_GET['date_from'] : null;
$date_to = isset($_GET['date_to']) ? $_GET['date_to'] : null;

// Get user orders based on date range and pagination
$orders = $orderModel->getUserOrdersByDate($user_id, $date_from, $date_to, $orders_per_page, $offset);
$total_orders = $orderModel->countUserOrders($user_id, $date_from, $date_to);
$total_pages = ceil($total_orders / $orders_per_page);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-5">
    <h2>My Orders</h2>
    <form method="GET" class="mb-4">
        <label for="date_from">From:</label>
        <input type="date" name="date_from" id="date_from" value="<?= htmlspecialchars($date_from) ?>">
        <label for="date_to">To:</label>
        <input type="date" name="date_to" id="date_to" value="<?= htmlspecialchars($date_to) ?>">
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>
    
    <?php foreach ($orders as $order): ?>
        <div class="card mb-3">
            <div class="card-body">
                <h5>Order #<?= htmlspecialchars($order['order_id']) ?> - <?= htmlspecialchars($order['created_at']) ?></h5>
                <p>Status: <span class="badge bg-<?= $order['status'] == 'processing' ? 'warning' : ($order['status'] == 'out for delivery' ? 'primary' : 'success') ?>">
                        <?= htmlspecialchars($order['status']) ?></span></p>
                <p>Total Price: EGP <?= htmlspecialchars($order['total_price']) ?></p>
                <button class="btn btn-info" onclick="showOrderDetails(<?= $order['order_id'] ?>)">View Details</button>
                <?php if ($order['status'] == 'processing'): ?>
                    <button class="btn btn-danger" onclick="cancelOrder(<?= $order['order_id'] ?>)">Cancel</button>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
    
    <!-- Pagination -->
    <nav>
        <ul class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>&date_from=<?= htmlspecialchars($date_from) ?>&date_to=<?= htmlspecialchars($date_to) ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<!-- Order Details Modal -->
<div id="orderDetailsModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="orderDetailsBody">
                <!-- Order details will be loaded here via AJAX -->
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function showOrderDetails(orderId) {
        fetch(`order_details.php?order_id=${orderId}`)
            .then(response => response.text())
            .then(data => {
                document.getElementById("orderDetailsBody").innerHTML = data;
                new bootstrap.Modal(document.getElementById("orderDetailsModal")).show();
            });
    }

    function cancelOrder(orderId) {
        if (!confirm("Are you sure you want to cancel this order?")) {
            return;
        }

        fetch("cancel_order.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `order_id=${orderId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Order canceled successfully!");
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error("Error:", error));
    }
</script>
</body>
</html>
