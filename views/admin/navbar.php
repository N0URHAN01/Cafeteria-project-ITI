<?php
// session_start();
require_once __DIR__ . "/../../classes/db/Database.php";
require_once __DIR__ . "/../../middleware/authMiddleware.php";

requireAuthAdmin();

$db = new Database();
$conn = $db->connect();
$admin_id = $_SESSION["admin_id"];
$stmt = $conn->prepare("SELECT name, profile_image FROM admins WHERE admin_id = :admin_id");
$stmt->execute(['admin_id' => $admin_id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="admin_dashboard.php">Admin Panel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="ManualOrder.php"><i class="fas fa-home"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="products.php"><i class="fas fa-box"></i> Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="users.php"><i class="fas fa-users"></i> Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="ManualOrder.php"><i class="fas fa-receipt"></i> Manual Order</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="AllOrders.php"><i class="fas fa-receipt"></i> All Order</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="checks_view.php"><i class="fas fa-check"></i> Checks</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Categories.php"><i class="fas fa-list"></i> Categories</a>
                </li>
            </ul>

            <!-- User Dropdown -->
            <div class="dropdown">
                <button class="btn dropdown-toggle d-flex align-items-center" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="../../uploads/<?= htmlspecialchars($admin['profile_image']); ?>" alt="Admin Image" class="rounded-circle me-2" width="40" height="40">
                    <span class="fw-bold"><?= htmlspecialchars($admin['name']); ?></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li>
                        <form method="POST" action="../../controllers/logout.php">
                            <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt"></i> Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<!-- Navbar CSS -->
<style>
    .navbar {
        background: none !important;
        font-family: 'Poppins', sans-serif;
        font-weight: 500;
    }

    .navbar-brand {
        color: #7E5A3C !important;
        font-size: 20px;
    }

    .navbar-nav .nav-link {
        color: #7E5A3C !important;
        font-size: 16px;
        transition: 0.3s;
    }

    .navbar-nav .nav-link:hover {
        background-color: #D76F32;
        border-radius: 5px;
        color: white !important;
    }

    .dropdown-toggle::after {
        display: none;
    }

    .dropdown-menu {
        min-width: 120px;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
