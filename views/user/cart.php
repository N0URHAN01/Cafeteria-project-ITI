<?php
session_start();
require_once __DIR__ . "/../../middleware/authMiddleware.php";
requireAuthUser();

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle Add, Increase, Decrease, and Remove Actions
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Load product data
    require_once __DIR__ . "/../../classes/product/product.php";
    $productObj = new Product();
    $product = $productObj->get_product_by_id($product_id);

    if (!$product) {
        header("Location: cart.php");
        exit();
    }

    switch ($_POST['action']) {
        case "add":
            if (!isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id] = [
                    "name" => $product['name'],
                    "price" => $product['price'],
                    "image_url" => $product['image_url'],
                    "quantity" => 1
                ];
            } else {
                $_SESSION['cart'][$product_id]['quantity'] += 1;
            }
            break;

        case "increase":
            $_SESSION['cart'][$product_id]['quantity'] += 1;
            break;

        case "decrease":
            if ($_SESSION['cart'][$product_id]['quantity'] > 1) {
                $_SESSION['cart'][$product_id]['quantity'] -= 1;
            } else {
                unset($_SESSION['cart'][$product_id]);
            }
            break;

        case "remove":
            unset($_SESSION['cart'][$product_id]);
            break;
    }

    $_SESSION['cart_count'] = array_sum(array_column($_SESSION['cart'], 'quantity'));
    header("Location: cart.php");
    exit();
}

// Calculate total price
$totalPrice = 0;
foreach ($_SESSION['cart'] as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/home.css">
</head>
<body>
<?php include 'navbar.php'; ?>


<div class="container mt-5">
    <h2 class="text-center">Your Cart</h2>
    
    <?php if (!empty($_SESSION['cart'])): ?>
        <table class="table table-bordered text-center mt-3">
            <thead class="table-dark">
                <tr>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                    <tr>
                        <td><img src="../../uploads/products/<?= htmlspecialchars($item['image_url']); ?>" class="cart-img" style="width: 70px; height: 70px;"></td>
                        <td><?= htmlspecialchars($item['name']); ?></td>
                        <td>$<?= number_format($item['price'], 2); ?></td>
                        <td>
                            <form method="POST" action="cart.php" class="d-inline">
                                <input type="hidden" name="product_id" value="<?= $id; ?>">
                                <button type="submit" name="action" value="decrease" class="btn btn-sm btn-warning">-</button>
                                <span class="mx-2"><?= $item['quantity']; ?></span>
                                <button type="submit" name="action" value="increase" class="btn btn-sm btn-success">+</button>
                            </form>
                        </td>
                        <td>$<?= number_format($item['price'] * $item['quantity'], 2); ?></td>
                        <td>
                            <form method="POST" action="cart.php">
                                <input type="hidden" name="product_id" value="<?= $id; ?>">
                                <button type="submit" name="action" value="remove" class="btn btn-sm btn-danger">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="text-end mt-3">
            <h4>Total: $<?= number_format($totalPrice, 2); ?></h4>
            <a href="checkout.php" class="btn btn-primary">Confirm Order</a>
        </div>

    <?php else: ?>
        <p class="text-center text-muted">Your cart is empty.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
