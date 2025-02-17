<?php
require_once __DIR__ . "/../../classes/product/product.php";
require_once __DIR__ . "/../../utils/validator.php";

$product = new Product();

$add_product_errors = [];
$images_dir = __DIR__ . '/../../uploads/products';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $product_data = validate_posted_data($_POST);
    $add_product_errors = array_merge($add_product_errors, $product_data['errors']);

    $name = trim($_POST["name"] ?? '');
    $price = filter_var($_POST['price'] ?? '', FILTER_VALIDATE_FLOAT);
    $category_id = $_POST["category_id"] ?? null;
    $stock_quantity = filter_var($_POST["stock_quantity"] ?? 0, FILTER_VALIDATE_INT);
    $status = $_POST["status"] ?? 'available';

    if (empty($name)) {
        $add_product_errors['name'] = "Product name is required.";
    }

    if ($product->is_product_name_used($name)) {
        $add_product_errors['name'] = "Product name is already in use";
    }

    if ($price === false || $price <= 0) {
        $add_product_errors['price'] = "Valid product price is required.";
    }
    if ($stock_quantity === false || $stock_quantity < 0) {
        $add_product_errors['stock_quantity'] = "Stock quantity must be a non-negative integer.";
    }


    // image
    $image_url = $_FILES['image_url'] ?? null;
    $image_name = $image_url['name'] ?? ''; 
    $image_tmp_name = $image_url['tmp_name'] ?? '';
    $allowed_extensions = ["jpg", "jpeg", "png"];

    if (!empty($image_tmp_name)) {
        $file_errors = validate_file($image_url, $allowed_extensions);
        $add_product_errors = array_merge($add_product_errors, $file_errors);
    } else {
        $add_product_errors['file_upload'] = "No image uploaded";
    }

    

 

    if (empty($add_product_errors)) {
        // upload image
        $image_path = null;
        $image_id = null;
        
        if (!empty($image_tmp_name)) {
            $image_id = uniqid();
            $new_image_name = $image_id . "_" . basename($image_name);
            $image_path = $images_dir . "/" . $new_image_name;
            var_dump($image_path);
            if (!move_uploaded_file($image_tmp_name, $image_path)) {
                $image_path = null; 
            }
        }

        // Create new Product 
        $new_product = $product->create_product($name, $price, $category_id, $new_image_name,$stock_quantity,$status);

        if ($new_product) {
            header("Location: ../../views/admin/products.php?success=Product added successfully");
            exit;
        }
    }

    // redirec with errors
    $errors_json = urlencode(json_encode($add_product_errors));
    $old_data_json = urlencode(json_encode($product_data["data"]));
    header("Location: ../../views/admin/add_product.php?errors={$errors_json}&old={$old_data_json}");
    exit;
}
