<?php
session_start();
require_once __DIR__ . "/../../classes/product/product.php";

$productObj = new Product();
$response = ["status" => "error", "message" => "Invalid request"];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["product_id"])) {
    $product_id = $_POST["product_id"];
    $product = $productObj->get_product_by_id($product_id);

    if ($product) {
        if (!isset($_SESSION["cart"][$product_id])) {
            $_SESSION["cart"][$product_id] = [
                "name" => $product["name"],
                "image_url" => $product["image_url"],
                "price" => $product["price"],
                "quantity" => 1
            ];
        } else {
            $_SESSION["cart"][$product_id]["quantity"]++;
        }

        $_SESSION["cart_count"] = array_sum(array_column($_SESSION["cart"], "quantity"));
        $response = ["status" => "success", "cart_count" => $_SESSION["cart_count"], "message" => "{$product['name']} added to cart!", "cart_html" => getCartHTML()];
    }
}

echo json_encode($response);

// Function to get cart dropdown HTML
function getCartHTML() {
    $html = "";
    if (!empty($_SESSION["cart"])) {
        $html .= '<ul class="list-unstyled">';
        foreach ($_SESSION["cart"] as $id => $item) {
            $html .= '<li class="d-flex align-items-center mb-2">
                        <img src="../../uploads/products/' . htmlspecialchars($item["image_url"]) . '" class="me-2" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                        <div>
                            <p class="m-0">' . htmlspecialchars($item["name"]) . '</p>
                            <p class="m-0">$' . number_format($item["price"], 2) . ' x ' . $item["quantity"] . ' = $' . number_format($item["price"] * $item["quantity"], 2) . '</p>
                            <button class="btn btn-sm btn-warning update-cart" data-product-id="' . $id . '" data-action="decrease">-</button>
                            <span class="mx-2 cart-item-quantity">' . $item["quantity"] . '</span>
                            <button class="btn btn-sm btn-success update-cart" data-product-id="' . $id . '" data-action="increase">+</button>
                            <button class="btn btn-sm btn-danger remove-from-cart" data-product-id="' . $id . '">Remove</button>
                        </div>
                      </li>';
        }
        $html .= '</ul><div class="text-center mt-2"><a href="cart.php" class="btn btn-primary btn-sm">Checkout</a></div>';
    } else {
        $html = '<p class="text-center text-muted">Your cart is empty.</p>';
    }
    return $html;
}
