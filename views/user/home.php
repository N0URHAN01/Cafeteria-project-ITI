<?php
require_once __DIR__ . "/../../middleware/authMiddleware.php";

session_start();
requireAuthUser();
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

            <div class="nav-item position-relative">
                <a class="nav-link" href="cart.php">
                    <i class="fas fa-shopping-cart cart-icon"></i> Cart
                    <span class="cart-badge"><?= $_SESSION['cart_count'] ?? 0; ?></span>
                </a>
            </div>
        </div>
    </div>
</nav>
<div class="header">
    <div class="header-text">
        <h1>Welcome to Our Cafeteria – Everything You Need to Feel Better! </h1>
        <h4>Delicious meals and drinks await you! <br> Enjoy fresh, healthy food in a comfortable and welcoming environment.</h4>
    </div>
    <img src="image2.png" alt="Cafeteria Image" class="header-image">
</div>

<?php include "category.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
