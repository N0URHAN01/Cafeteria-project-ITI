<?php
require_once __DIR__ . "/../../middleware/authMiddleware.php";
require_once __DIR__ . "/../../classes/db/Database.php";
require_once __DIR__ . "/../../classes/category/category.php";
require_once __DIR__ . "/../../classes/product/product.php";

session_start();
requireAuthUser();

// Initialize Database connection
$database = new Database();
$conn = $database->connect();

if (!$conn) {
    die("Database connection failed in home.php.");
}

// Check if $conn is a PDO instance
if (!($conn instanceof PDO)) {
    die("Database connection object is invalid.");
}

// Fetch all categories
$categoryObj = new Category($conn);
$categories = $categoryObj->get_all_categories();
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>


<?php include 'navbar.php'; ?>

<div class="header">
    <div class="header-text">
        <h1>Welcome to Our Cafeteria – Everything You Need to Feel Better!</h1>
        <h4>Delicious meals and drinks await you! <br> Enjoy fresh, healthy food in a comfortable and welcoming environment.</h4>
    </div>
    <img src="image2.png" alt="Cafeteria Image" class="header-image">
</div>
<h1 class="section-title">Browse Our Delicious Selection</h1>

<!-- Category Navigation  -->
<div class="category-nav text-center mt-4">
    <button class="btn btn-outline-primary btn-category active" data-category-id="all">All</button>
    <?php if (!empty($categories)): ?>
        <?php foreach ($categories as $category): ?>
            <button class="btn btn-outline-primary btn-category" data-category-id="<?= htmlspecialchars($category['category_id']); ?>">
                <?= htmlspecialchars($category['name']); ?>
            </button>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-danger">No categories found.</p>
    <?php endif; ?>
</div>

<!-- Product List -->
<div class="container mt-4">
    <div id="product-list" class="row">
    </div>
</div>

<script>

$(document).ready(function() {
    function loadProducts(categoryId) {
        $.ajax({
            url: "fetch_products.php",
            type: "POST",
            data: { category_id: categoryId },
            success: function(response) {
                $("#product-list").html(response);
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error, "Response:", xhr.responseText);
                $("#product-list").html("<p class='text-danger text-center'>Error loading products.</p>");
            }
        });
    }

    // Load all products 
    loadProducts("all");

    // Category Click Event
    $(".btn-category").on("click", function() {
        var categoryId = $(this).data("category-id");

        // Remove "active" class from all buttons, then add to clicked button
        $(".btn-category").removeClass("active");
        $(this).addClass("active");

        // Load products based on category
        loadProducts(categoryId);
    });

    //  Handle "Add to Cart" (for dynamically loaded products)
    $(document).on("click", ".add-to-cart", function() {
        var productId = $(this).data("product-id");

        $.ajax({
            url: "add_to_cart.php",
            type: "POST",
            data: { product_id: productId },
            dataType: "json",
            success: function(response) {
                if (response.status === "success") {
                    $(".cart-badge").text(response.cart_count); // Update Cart Count
                    alert(response.message);
                    updateCartDropdown(response.cart_html); // Refresh Cart Dropdown
                }
            },
            error: function(xhr) {
                alert("Error adding product to cart.");
            }
        });
    });

    // Function to Update Cart Dropdown (Live Update)
    function updateCartDropdown(cartHtml) {
        $("#cartDropdown .dropdown-menu").html(cartHtml);
    }
});


    </script>

</body>
</html>
