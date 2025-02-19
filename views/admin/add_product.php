<?php
session_start();
require_once __DIR__ . "/../../classes/db/Database.php";
require_once __DIR__ . "/../../classes/product/product.php";
require_once __DIR__ . "/../../middleware/authMiddleware.php";

$product = new Product();

requireAuthAdmin();

// Fetch admin details
$db = new Database();
$conn = $db->connect();
$admin_id = $_SESSION["admin_id"];
$stmt = $conn->prepare("SELECT name, profile_image FROM admins WHERE admin_id = :admin_id");
$stmt->execute(['admin_id' => $admin_id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch all categories for the dropdown
$stmt = $conn->prepare("SELECT * FROM categories");
$stmt->execute();
$all_categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get errors if redirected
$errors = isset($_GET['errors']) ? json_decode(urldecode($_GET['errors']), true) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Add Product</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { background-color: #F6E2B3; }
        .sidebar {
            position: fixed; top: 0; left: 0; bottom: 0; width: 250px;
            background-color: #7E5A3C; color: white; padding-top: 30px;
        }
        .sidebar .admin-info { text-align: center; margin-bottom: 20px; }
        .sidebar .admin-info img { border-radius: 50%; width: 60px; height: 60px; }
        .sidebar .admin-info p { margin-top: 10px; }
        .sidebar a { color: white; text-decoration: none; padding: 10px; display: block; font-size: 16px; }
        .sidebar a:hover { background-color: #D76F32; }
        .main-content { margin-left: 250px; padding: 20px; height: 100%; }
        .btn-add-product { background-color: #7E5A3C; color: white; border-radius: 25px; }
        .btn-add-product:hover { background-color: #D76F32; }
        .custom-card { border-radius: 15px; padding: 20px; width: 60%; max-width: 100%; margin: auto; }
        .form-container { display: flex; flex-direction: column; align-items: center; }
        .form-control-sm { border: none; border-bottom: 1px solid #ddd; background-color: transparent; }
        .form-control-sm:focus { outline: none; border-color: #7E5A3C; }
        .input-container { position: relative; margin-bottom: 20px; width: 100%; display: flex; align-items: center; }
        .input-container i { margin-left: 10px; color: #aaa; font-size: 1.2rem; }
        .text-dark { color: #333333 !important; }
        @media (max-width: 768px) { .form-container { width: 90%; } .main-content { margin-left: 0; } }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="admin-info">
        <img src="../../uploads/<?= htmlspecialchars($admin['profile_image']); ?>" alt="Admin">
        <p><?= htmlspecialchars($admin['name']); ?></p>
    </div>
    <a href="admin_dashboard.php">Home</a>
    <a href="products.php">Products</a>
    <a href="users.php">Users</a>
    <a href="#">Manual Order</a>
    <a href="#">Checks</a>
    <a href="Categories.php">Categories</a>
    <form method="POST" action="../../controllers/logout.php">
        <button type="submit" class="btn btn-danger w-100 mt-3">Logout</button>
    </form>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 col-xl-11">
                <div class="card text-black shadow custom-card">
                    <div class="card-body">
                        <div class="form-container">
                            <p class="text-center h3 fw-bold mb-3">Add Product</p>

                            <?php if (!empty($errors)): ?>
                                <div class="alert alert-danger">
                                    <ul>
                                        <?php foreach ($errors as $error): ?>
                                            <li><?= htmlspecialchars($error); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <form method="POST" action="../../controllers/admin/addProductController.php" enctype="multipart/form-data">
                                
                                <!-- Product Name -->
                                <div class="input-container">
                                    <label for="name" class="form-label">Product Name</label>
                                    <input type="text" name="name" class="form-control form-control-sm" id="name" required>
                                    <i class="fas fa-box"></i>
                                </div>

                                <!-- Price -->
                                <div class="input-container">
                                    <label for="price" class="form-label">Price</label>
                                    <input type="number" name="price" step="0.01" class="form-control form-control-sm" id="price" required>
                                    <i class="fas fa-dollar-sign"></i>
                                </div>

                                <!-- Category -->
                                <div class="input-container">
                                    <label for="category_id" class="form-label">Category</label>
                                    <select name="category_id" class="form-select form-select-sm" id="category_id" required>
                                        <option value="">Select a Category</option>
                                        <?php foreach ($all_categories as $category): ?>
                                            <option value="<?= htmlspecialchars($category['category_id']); ?>">
                                                <?= htmlspecialchars($category['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <i class="fas fa-tags"></i>
                                </div>

                                <!-- Stock Quantity -->
                                <div class="input-container">
                                    <label for="stock_quantity" class="form-label">Stock Quantity</label>
                                    <input type="number" name="stock_quantity" class="form-control form-control-sm" id="stock_quantity" required>
                                    <i class="fas fa-boxes"></i>
                                </div>

                                <!-- Status -->
                                <div class="input-container">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" class="form-select form-select-sm" id="status">
                                        <option value="available">Available</option>
                                        <option value="out of stock">Out of Stock</option>
                                    </select>
                                    <i class="fas fa-check-circle"></i>
                                </div>

                                <!-- Product Image -->
                                <div class="input-container">
                                    <label for="image_url" class="form-label">Product Image</label>
                                    <input type="file" name="image_url" class="form-control form-control-sm" id="image_url">
                                    <i class="fas fa-image"></i>
                                </div>

                                <div class="d-flex justify-content-center">
                                    <button type="submit" class="btn btn-add-product px-4">Add Product</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
