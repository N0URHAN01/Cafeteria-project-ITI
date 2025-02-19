<?php
require_once __DIR__ . "/../../classes/product/product.php";
session_start();

$productObj = new Product();
$products = $productObj->get_all_products();

// Initialize the cart session if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle cart actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $action = $_POST['action'];

    if (isset($_SESSION['cart'][$product_id])) {
        if ($action === 'increase') {
            $_SESSION['cart'][$product_id]['quantity']++;
        } elseif ($action === 'decrease' && $_SESSION['cart'][$product_id]['quantity'] > 1) {
            $_SESSION['cart'][$product_id]['quantity']--;
        } elseif ($action === 'remove') {
            unset($_SESSION['cart'][$product_id]);
        }
    } else {
        $product = $productObj->get_product_by_id($product_id);
        if ($product) {
            $_SESSION['cart'][$product_id] = [
                'name' => $product['name'],
                'image_url' => $product['image_url'],
                'price' => $product['price'],
                'quantity' => 1
            ];
        }
    }

    $_SESSION['cart_count'] = array_sum(array_column($_SESSION['cart'], 'quantity'));
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }
        .product-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
            overflow: hidden;
        }
        .product-card:hover {
            transform: translateY(-5px);
        }
        .product-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 10px 10px 0 0;
        }
        .product-details {
            padding: 20px;
            text-align: center;
        }
        .product-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        .product-price {
            font-size: 16px;
            color: #28a745;
            margin-top: 10px;
        }
        .btn-add {
            background: #007bff;
            color: white;
            border-radius: 5px;
            padding: 8px 15px;
            transition: 0.3s;
        }
        .btn-add:hover {
            background: #0056b3;
        }
        .cart-container {
            margin-top: 40px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .cart-item img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2 class="text-center mb-4">Our Products</h2>
    <div class="row">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="col-md-4 mb-4">
                    <div class="product-card">
                        <img src="../../uploads/products/<?= htmlspecialchars($product['image_url']); ?>" alt="<?= htmlspecialchars($product['name']); ?>">
                        <div class="product-details">
                            <h5 class="product-title"> <?= htmlspecialchars($product['name']); ?> </h5>
                            <p class="product-price"> $<?= number_format($product['price'], 2); ?> </p>
                            <form method="POST" action="">
                                <input type="hidden" name="product_id" value="<?= $product['product_id']; ?>">
                                <button type="submit" name="action" value="add" class="btn btn-add">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">No products available.</p>
        <?php endif; ?>
    </div>

    <!-- Cart Section -->
    <?php if (!empty($_SESSION['cart'])): ?>
        <div class="cart-container mt-4">
            <h3>Your Cart</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                        <tr>
                            <td><img src="../../uploads/products/<?= htmlspecialchars($item['image_url']); ?>" class="cart-item"></td>
                            <td><?= htmlspecialchars($item['name']); ?></td>
                            <td>$<?= number_format($item['price'], 2); ?></td>
                            <td>
                                <form method="POST" action="" class="d-inline">
                                    <input type="hidden" name="product_id" value="<?= $id; ?>">
                                    <button type="submit" name="action" value="decrease" class="btn btn-sm btn-warning">-</button>
                                    <span class="mx-2"> <?= $item['quantity']; ?> </span>
                                    <button type="submit" name="action" value="increase" class="btn btn-sm btn-success">+</button>
                                </form>
                            </td>
                            <td>$<?= number_format($item['price'] * $item['quantity'], 2); ?></td>
                            <td>
                                <form method="POST" action="" class="d-inline">
                                    <input type="hidden" name="product_id" value="<?= $id; ?>">
                                    <button type="submit" name="action" value="remove" class="btn btn-sm btn-danger">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="text-end">
                <button class="btn btn-primary">Confirm Order</button>
            </div>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
