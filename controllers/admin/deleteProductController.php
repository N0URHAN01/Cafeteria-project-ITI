<?php
require_once __DIR__ . "/../../classes/product/product.php";

$product = new Product();



if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['action']) && $_GET['action'] === "delete") {
    $product_id = $_GET['product_id'] ?? null;
  
    if (!$product_id) {
        header("Location: ../../views/admin/products.php?error=Invalid product ID");
        exit;
    }

    $current_product = $product->get_product_by_id($product_id);

    var_dump($current_product);  

    if (!$current_product) {
        header("Location: ../../views/admin/products.php?error=Product not found");
        exit;
    }

    $delete_result = $product->delete_product($product_id);

    if ($delete_result) {
        $image_path = __DIR__ . "/../../uploads/products/" . $current_product['image_url'];
        if (!empty($current_product['image_url']) && file_exists($image_path)) {
            unlink($image_path);
        }

        header("Location: ../../views/admin/products.php?success=Product deleted successfully");
        exit;
    } else {
        header("Location: ../../views/admin/products.php?error=Failed to delete product");
        exit;
    }
}
?>
