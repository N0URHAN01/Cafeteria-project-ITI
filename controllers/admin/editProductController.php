<?php
require_once __DIR__ . "/../../classes/product/product.php";
require_once __DIR__ . "/../../utils/validator.php";


$product = new Product();
$errors = [];
$images_dir = __DIR__ . "/../../uploads/products";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $product_id = $_POST['product_id'] ?? null;
    $name = trim($_POST["name"] ?? '');
    $price = filter_var($_POST['price'] ?? '', FILTER_VALIDATE_FLOAT);
    $category_id = $_POST["category_id"] ?? null;
    $stock_quantity = filter_var($_POST["stock_quantity"] ?? 0, FILTER_VALIDATE_INT);
    $status = $_POST["status"] ?? 'available';

    if (empty($product_id)) {
        $errors['product_id'] = "Invalid product ID.";
    }

    $current_product = $product->get_product_by_id($product_id);

    if (!$current_product) {
        $errors['product_id'] = "Product not found";
    }

    if (empty($name)) {
        $errors['name'] = "Product name is required.";
    } 

    if ($price === false || $price <= 0) {
        $errors['price'] = "Valid product price is required.";
    }
    if ($stock_quantity === false || $stock_quantity < 0) {
        $errors['stock_quantity'] = "Stock quantity must be a non-negative integer.";
    }

    $image_name = $current_product['image_url']; 
    $product_image = $_FILES['image_url'] ?? null;
    $allowed_extensions = ["jpg", "jpeg", "png"];

    if (!empty($product_image['tmp_name'])) {
        $file_errors = validate_file($product_image, $allowed_extensions);
        $errors = array_merge($errors, $file_errors);

        if (empty($file_errors)) {
            $image_id = uniqid();
            $new_image_name = $image_id . "_" . basename($product_image['name']);
            $new_image_path = $images_dir . "/" . $new_image_name;

            if (move_uploaded_file($product_image['tmp_name'], $new_image_path)) {
                $image_name = $new_image_name; 
            } else {
                $errors['file_upload'] = "Failed to upload image.";
            }
        }
    }

   
    if (empty($errors)) {
        $update_result = $product->update_product($product_id, $name, $price, $category_id, $image_name, $stock_quantity, $status);
        if ($update_result) {
            header("Location: ../../views/admin/products.php?success=Product updated successfully");
            exit;
        } else {
            $errors['db_error'] = "Failed to update product.";
        }
    }

    $errors_json = urlencode(json_encode($errors));
    header("Location: ../../views/admin/edit_product.php?id={$product_id}&errors={$errors_json}");
    exit;
}
?>