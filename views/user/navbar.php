<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>navbar</title>
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
                        <form method="POST" action="../../controllers/logout.php">
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>



 
