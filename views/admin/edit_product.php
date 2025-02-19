<?php
session_start();
require_once '../../classes/db/Database.php';
require_once '../../classes/product/product.php';
require_once __DIR__ . "/../../middleware/authMiddleware.php";

// Check if admin is logged in
requireAuthAdmin();
// Get product ID from URL
$product_id = $_GET['id'] ?? null;
if (!$product_id) {
    header("Location: products.php?error=Invalid product ID");
    exit;
}

// Fetch product details
$product = new Product();
$product_details = $product->get_product_by_id($product_id);
if (!$product_details) {
    header("Location: products.php?error=Product not found");
    exit;
}

// Fetch all categories for dropdown
$db = new Database();
$conn = $db->connect();
$stmt = $conn->prepare("SELECT * FROM categories");
$stmt->execute();
$all_categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get errors from URL
$errors = isset($_GET['errors']) ? json_decode(urldecode($_GET['errors']), true) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            background-color: #F6E2B3; 
            font-family: "Roboto", sans-serif;
        }
        .container {
            max-width: 600px;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.2);
            margin-top: 50px;
        }
        .form-group label {
            font-weight: bold;
        }
        .image-preview {
            width: 100px;
            height: 100px;
            border-radius: 8px;
            object-fit: cover;
            display: block;
            margin-bottom: 10px;
        }
        .btn-primary {
            background-color: #5c3d2e;
            border: none;
        }
        .btn-primary:hover {
            background-color: #D76F32;
        }
    </style>
</head>
<body>
<?php include "navbar.php"; ?>
<div class="container">
    <h2 class="text-center">Edit Product</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="../../controllers/admin/editProductController.php" enctype="multipart/form-data">
        <input type="hidden" name="product_id" value="<?= $product_details['product_id']; ?>">
        <input type="hidden" name="existing_image_url" value="<?= $product_details['image_url']; ?>">

        <!-- Name -->
        <div class="form-group">
            <label>Product Name:</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product_details['name']); ?>" >
        </div>

        <!-- Price -->
        <div class="form-group">
            <label>Price ($):</label>
            <input type="number" name="price" step="0.01" class="form-control" value="<?= $product_details['price']; ?>" required>
        </div>

        <!-- Category -->
        <div class="form-group">
            <label>Category:</label>
            <select name="category_id" class="form-control" required>
                <?php foreach ($all_categories as $category): ?>
                    <option value="<?= $category['category_id']; ?>" <?= ($category['category_id'] == $product_details['category_id']) ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Stock Quantity -->
        <div class="form-group">
            <label>Stock Quantity:</label>
            <input type="number" name="stock_quantity" class="form-control" value="<?= $product_details['stock_quantity']; ?>" required>
        </div>

        <!-- Status -->
        <div class="form-group">
            <label>Status:</label>
            <select name="status" class="form-control">
                <option value="available" <?= ($product_details['status'] === 'available') ? 'selected' : ''; ?>>Available</option>
                <option value="out of stock" <?= ($product_details['status'] === 'out of stock') ? 'selected' : ''; ?>>Out of Stock</option>
            </select>
        </div>

        <!-- Image Upload -->
        <div class="form-group">
            <label>Product Image:</label>
            <img src="../../uploads/products/<?= htmlspecialchars($product_details['image_url']); ?>" 
                 onerror="this.src='../../uploads/products/default.png'" 
                 class="image-preview" id="imagePreview">
            <input type="file" name="image_url" class="form-control-file" accept="image/*" onchange="previewImage(event)">
        </div>

        <button type="submit" class="btn btn-primary btn-block">Update Product</button>
    </form>
</div>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('imagePreview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

</body>
</html>
