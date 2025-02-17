<?php
require_once __DIR__ . "/../../middleware/authMiddleware.php";
require_once __DIR__ . "/../../classes/db/Database.php";
require_once __DIR__ . "/../../controllers/admin/categoryController.php";

session_start();
requireAuthUser();

$categoryController = new CategoryController();
$categories = $categoryController->getCategories();
?>

<div class="container mt-4">
    <h2 class="text-center mb-4">Explore Our Categories</h2>
    <div class="row justify-content-center">
        <?php 
     
        $categoryIcons = [
            "Fresh Juices" => "fa-glass-whiskey",
            "Hot drinks" => "fa-mug-hot",
            "Ice Drinks" => "fa-snowflake",
            "Pastries & Baked Goods" => "fa-bread-slice",
            "Sandwiches & Wraps" => "fa-hamburger"
        ];
        ?>

        <?php if (!empty($categories)): ?>
            <?php foreach ($categories as $category): ?>
                <div class="col-md-3 col-sm-6">
                    <div class="category-card text-center">
                        <div class="icon-container">
                            <i class="fas <?= $categoryIcons[$category['name']] ?? 'fa-utensils' ?>"></i>
                        </div>
                        <h5 class="category-title"><?= htmlspecialchars($category['name']); ?></h5>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">No categories available at the moment.</p>
        <?php endif; ?>
    </div>
</div>
