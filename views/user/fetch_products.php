<?php
require_once __DIR__ . "/../../classes/db/Database.php";
require_once __DIR__ . "/../../classes/product/product.php";

session_start();

$database = new Database();
$conn = $database->connect();

if (!$conn) {
    die("Database connection failed in fetch_products.php.");
}



$productObj = new Product($conn);


$categoryId = isset($_POST['category_id']) && $_POST['category_id'] !== "all" ? $_POST['category_id'] : null;


$products = $categoryId ? $productObj->get_products_by_category($categoryId) : $productObj->get_all_products();

if (!empty($products)): ?>
    <?php foreach ($products as $product): ?>
        <div class="product-card">
            <img src="../../uploads/products/<?= htmlspecialchars($product['image_url']); ?>" alt="<?= htmlspecialchars($product['name']); ?>">
            <div class="product-info">
                <h5><?= htmlspecialchars($product['name']); ?></h5>
                <p>$<?= number_format($product['price'], 2); ?></p>
                <button type="button" class="add-to-cart" data-product-id="<?= $product['product_id']; ?>">
                    Add to Cart
                </button>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p class="text-center text-muted">No products available in this category.</p>
<?php endif; ?>
