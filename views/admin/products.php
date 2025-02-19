<?php
session_start();
require_once '../../classes/db/Database.php';
require_once '../../classes/product/product.php';
require_once __DIR__ . "/../../middleware/authMiddleware.php";

// Check if admin is logged in
requireAuthAdmin();

// Create database connection
$database = new Database();
$db = $database->connect(); 

// Fetch admin details
$admin_id = $_SESSION["admin_id"];
$stmt = $db->prepare("SELECT name, profile_image FROM admins WHERE admin_id = :admin_id");
$stmt->execute(['admin_id' => $admin_id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

// Get all products
$product = new Product();
$all_products = $product->get_all_products();

// Get success/error messages
$success_message = $_GET['success'] ?? null;
$error_message = $_GET['error'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Products</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" href="../../static_images/favicon.ico" type="image/ico" />
   
    <style>
        body {background: #f5f5f0;  font-family: "Roboto", sans-serif; }
        .table-wrapper { background: #fff; padding: 20px; box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05); }
        .table-title { margin-bottom: 10px; }
        .table-title h2 { font-size: 22px; }
        table.table td img { width: 50px; height: 50px; border-radius: 5px; }
        table.table td a { margin: 0 5px; }
        .edit { color: #ffc107; } 
        .delete { color: #e34724; cursor: pointer; }
        .navbar { background-color: #5c3d2e; }
        .profile-img { width: 40px; height: 40px; border-radius: 50%; margin-right: 10px; border: 2px solid #b08968; }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <a class="navbar-brand" href="#"><img src="../../static_images/logo.png" style="width:50px; height:50px;"></a>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav w-100">
            <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="products.php">Products</a></li>
            <li class="nav-item"><a class="nav-link" href="users.php">Users</a></li>
            <li class="nav-item"><a class="nav-link" href="Categories.php">Categories</a></li>
            <li class="ml-auto">
                <div class="admin-info" onclick="toggleDropdown()">
                    <img src="../../uploads/<?= htmlspecialchars($admin['profile_image']); ?>" class="profile-img" />
                    <span><?= htmlspecialchars($admin['name']); ?></span>
                </div>
                <ul class="dropdown-menu" id="dropdownMenu">
                    <li><a href="../../controllers/logout.php">Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<!-- Main Content -->
<div class="container-xl">
    <div class="table-responsive">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-8"><h2>Product <b>List</b></h2></div>
                    <div class="col-sm-4 text-right">
                        <a href="add_product.php" class="btn btn-success"><i class="fa fa-plus"></i> Add Product</a>
                        <a href="Categories.php" class="btn btn-primary">
                      <i class="fa fa-plus"></i> Add New Category
                        </a>
                    </div>
                </div>
            </div>

            <!-- Success/Error Messages -->
            <?php if ($success_message): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success_message); ?></div>
            <?php endif; ?>
            <?php if ($error_message): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error_message); ?></div>
            <?php endif; ?>

            <table class="table table-striped table-hover table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name <i class="fa fa-sort"></i></th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($all_products as $product): ?>
                    <tr>
                        <td><?= $product['product_id']; ?></td>
                        <td><?= htmlspecialchars($product['name']); ?></td>
                        <td><?= htmlspecialchars($product['category_name']); ?></td>
                        <td>$<?= number_format($product['price'], 2); ?></td>
                        <td><?= $product['stock_quantity']; ?></td>
                        <td><span class="badge badge-<?= $product['status'] === 'available' ? 'success' : 'danger'; ?>">
                            <?= ucfirst($product['status']); ?>
                        </span></td>
                        <td><img src='../../uploads/products/<?= htmlspecialchars($product['image_url']); ?>' 
                                 onerror="this.src='../../uploads/products/default.png'"></td>
                        <td>
                            <a href="edit_product.php?id=<?= $product['product_id']; ?>" class="edit"><i class="fas fa-edit"></i></a>
                            <button class="delete" onclick="confirmDelete(<?= $product['product_id']; ?>)"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delete Confirmation -->
<script>
function confirmDelete(productId) {
    if (confirm("Are you sure you want to delete this product?")) {
        window.location.href = "../../controllers/admin/deleteProductController.php?action=delete&product_id=" + productId;
    }
}
</script>

<script>
function toggleDropdown() {
    var dropdown = document.getElementById("dropdownMenu");
    dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
}
</script>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

</body>
</html>
