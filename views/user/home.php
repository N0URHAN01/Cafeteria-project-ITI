<?php
require_once __DIR__ . "/../../middleware/authMiddleware.php";
require_once __DIR__ . "/../../classes/product/product.php";

session_start();
requireAuthUser();

$productObj = new Product();
$products = $productObj->get_all_products();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/home.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="home.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="latest_orders.php">Latest Orders</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="orders.php">My Orders</a>
                </li>
            </ul>
        </div>
        <div class="navbar-nav-right d-flex align-items-center">
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle user-dropdown d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                    <img src="../../uploads/users/<?= $_SESSION['user_image']; ?>" alt="User">
                    <span><?= htmlspecialchars($_SESSION['user_name']); ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
                    <li>
                        <form method="POST" action="../../controllers/user/logout.php">
                            <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt"></i> Logout</button>
                        </form>
                    </li>
                </ul>
            </div>

            <!-- Cart Dropdown -->
            <div class="nav-item dropdown position-relative">
                <a class="nav-link dropdown-toggle" href="#" id="cartDropdown" role="button" data-bs-toggle="dropdown">
                    <i class="fas fa-shopping-cart cart-icon"></i> Cart
                    <span class="cart-badge"><?= $_SESSION['cart_count'] ?? 0; ?></span>
                </a>

                <div class="dropdown-menu dropdown-menu-end p-3" style="width: 300px;">
                    <?php if (!empty($_SESSION['cart'])): ?>
                        <ul class="list-unstyled">
                            <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                                <li class="d-flex align-items-center mb-2">
                                    <img src="../../uploads/products/<?= htmlspecialchars($item['image_url']); ?>" class="me-2" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                    <div>
                                        <p class="m-0"><?= htmlspecialchars($item['name']); ?></p>
                                        <p class="m-0">$<?= number_format($item['price'], 2); ?> x <?= $item['quantity']; ?> = $<?= number_format($item['price'] * $item['quantity'], 2); ?></p>
                                        <button class="btn btn-sm btn-warning update-cart" data-product-id="<?= $id; ?>" data-action="decrease">-</button>
<span class="mx-2 cart-item-quantity"><?= $item['quantity']; ?></span>
<button class="btn btn-sm btn-success update-cart" data-product-id="<?= $id; ?>" data-action="increase">+</button>
<button class="btn btn-sm btn-danger remove-from-cart" data-product-id="<?= $id; ?>">Remove</button>

                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="text-center mt-2">
                            <a href="cart.php" class="btn btn-primary btn-sm">Checkout</a>
                        </div>
                    <?php else: ?>
                        <p class="text-center text-muted">Your cart is empty.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</nav>

<div class="header">
    <div class="header-text">
        <h1>Welcome to Our Cafeteria – Everything You Need to Feel Better!</h1>
        <h4>Delicious meals and drinks await you! <br> Enjoy fresh, healthy food in a comfortable and welcoming environment.</h4>
    </div>
    <img src="image2.png" alt="Cafeteria Image" class="header-image">
</div>

<!-- Display Products -->
<div class="container mt-5">
    <h2 class="text-center">Our Products</h2>
    <div class="row">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="../../uploads/products/<?= htmlspecialchars($product['image_url']); ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($product['name']); ?></h5>
                            <p class="card-text">$<?= htmlspecialchars(number_format($product['price'], 2)); ?></p>
                            <p class="card-text">Stock: <?= htmlspecialchars($product['stock_quantity']); ?></p>
                            
                            <!-- Add to Cart Form -->
                            <form method="POST" action="cart.php">
                                <input type="hidden" name="product_id" value="<?= $product['product_id']; ?>">
                                <button class="btn btn-success w-100 add-to-cart" data-product-id="<?= $product['product_id']; ?>">
                                            Add to Cart  </button>

                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">No products available.</p>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Add to Cart
    $(".add-to-cart").click(function() {
        var productId = $(this).data("product-id");

        $.ajax({
            url: "add_to_cart.php",
            type: "POST",
            data: { product_id: productId },
            dataType: "json",
            success: function(response) {
                if (response.status === "success") {
                    $(".cart-badge").text(response.cart_count);
                    alert(response.message);
                    updateCartDropdown(response.cart_html);
                }
            }
        });
    });

    // Update Cart Quantity
    $(".update-cart").click(function() {
        var productId = $(this).data("product-id");
        var action = $(this).data("action");

        $.ajax({
            url: "update_cart.php",
            type: "POST",
            data: { product_id: productId, action: action },
            dataType: "json",
            success: function(response) {
                if (response.status === "success") {
                    $(".cart-badge").text(response.cart_count);
                    updateCartDropdown(response.cart_html);
                }
            }
        });
    });

    // Remove Item from Cart
    $(".remove-from-cart").click(function() {
        var productId = $(this).data("product-id");

        $.ajax({
            url: "remove_from_cart.php",
            type: "POST",
            data: { product_id: productId },
            dataType: "json",
            success: function(response) {
                if (response.status === "success") {
                    $(".cart-badge").text(response.cart_count);
                    updateCartDropdown(response.cart_html);
                }
            }
        });
    });

    // Function to update the cart dropdown
    function updateCartDropdown(cartHtml) {
        $("#cartDropdown .dropdown-menu").html(cartHtml);
    }
});
</script>

</body>
</html>
