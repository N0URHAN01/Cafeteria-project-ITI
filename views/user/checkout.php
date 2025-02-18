<?php
session_start();
require_once __DIR__ . "/../../middleware/authMiddleware.php";
require_once __DIR__ . "/../../classes/order/UserOrder.php";
require_once __DIR__ . "/../../classes/product/product.php";
require_once __DIR__ . "/../../classes/admin/room.php";

// Debugging: Print session data if needed
if (!isset($_SESSION['user_id'])) {
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";
    die("Error: User ID is missing from session.");
}

// Ensure cart is not empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

requireAuthUser();
$userOrder = new UserOrder();
$product = new Product();
$room = new Room();

$rooms = $room->get_all_rooms();
$userId = $_SESSION['user_id'];
$userRoomId = $_SESSION['room_id'] ?? null;
$totalPrice = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $_SESSION['cart']));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $selectedRoom = $_POST['room_id'] ?? $userRoomId;
    $notes = $_POST['notes'] ?? '';

    // Create order and get order ID
    $orderId = $userOrder->createOrder($userId, $selectedRoom, $notes, $totalPrice);

    // Add ordered products
    foreach ($_SESSION['cart'] as $product_id => $item) {
        $userOrder->addOrderedProduct($orderId, $product_id, $item['quantity'], $item['price']);
    }

    // Clear cart and redirect
    unset($_SESSION['cart']);
    header("Location: orders.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Checkout</h2>
    <form method="POST" action="checkout.php">
        <table class="table text-center">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                    <tr>
                        <td><img src="../../uploads/products/<?= htmlspecialchars($item['image_url']) ?>" width="50"></td>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td><?= number_format($item['price'] * $item['quantity'], 2) ?> LE</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="mb-3">
            <label for="roomSelect" class="form-label">Select Room:</label>
            <select id="roomSelect" name="room_id" class="form-select">
                <?php foreach ($rooms as $r): ?>
                    <option value="<?= $r['room_id'] ?>" <?= $r['room_id'] == $userRoomId ? 'selected' : '' ?>>
                        <?= htmlspecialchars($r['room_number']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="notes" class="form-label">Additional Notes:</label>
            <textarea id="notes" name="notes" class="form-control"></textarea>
        </div>
        <h4>Total: <?= number_format($totalPrice, 2) ?> LE</h4>
        <button type="submit" class="btn btn-success">Confirm Order</button>
    </form>
</div>
</body>
</html>
