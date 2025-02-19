<?php
session_start();
require_once __DIR__ . "/../../middleware/authMiddleware.php";
requireAuthUser(); // Ensure user is authenticated

require_once __DIR__ . "/../../classes/order/UserOrder.php";

$orderModel = new UserOrder();
$latestOrder = $orderModel->getLatestCompletedOrder();

if (!$latestOrder) {
    echo "<div class='alert alert-warning text-center mt-5'>No completed orders found.</div>";
    exit;
}

$order_id = $latestOrder['order_id'];
$orderDetails = $orderModel->getOrderedProducts($order_id);

if (!$orderDetails) {
    echo "<div class='alert alert-danger text-center mt-5'>No details available for this order.</div>";
    exit;
}

$total_price = $latestOrder['total_price'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Latest Completed Order</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            font-family: 'Poppins', sans-serif;
        }
        .order-container {
            max-width: 700px;
            margin: 50px auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.1);
        }
        .order-header {
            font-size: 24px;
            font-weight: bold;
            color: #444;
            text-align: center;
        }
        .order-items .item-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        .order-items img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #ddd;
            transition: transform 0.3s ease-in-out;
        }
        .order-items img:hover {
            transform: scale(1.1);
        }
        .product-info {
            flex-grow: 1;
            font-size: 16px;
            color: #333;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .product-info strong {
            font-size: 18px;
            color: #222;
        }
        .total-price {
            font-size: 22px;
            font-weight: bold;
            color: #28a745;
            text-align: right;
        }
        .footer-text {
            text-align: center;
            font-size: 14px;
            color: #888;
            margin-top: 15px;
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
    <div class="order-container">
        <h3 class="order-header mb-4">Latest Completed Order</h3>
    
        <hr>

        
        <div class="order-items">
            <?php foreach ($orderDetails as $item): ?>
                <div class="item-container">
                    <img src="../../uploads/products/<?= htmlspecialchars($item['image_url']) ?>" 
                         alt="<?= htmlspecialchars($item['name']) ?>">
                    <div class="product-info">
                        <strong><?= htmlspecialchars($item['name']) ?></strong>
                        <span> Quantity: <?= htmlspecialchars($item['quantity']) ?></span>
                        <span> Price: EGP <?= htmlspecialchars($item['price']) ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <hr>

        <h5 class="total-price">Total: EGP <?= htmlspecialchars($total_price) ?></h5>

     
    </div>
</div>

<script src="../../js/bootstrap.bundle.min.js"></script>
</body>
</html>
