
<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["product_id"], $_POST["action"])) {
    $product_id = $_POST["product_id"];
    $action = $_POST["action"];

    if (isset($_SESSION["cart"][$product_id])) {
        if ($action === "increase") {
            $_SESSION["cart"][$product_id]["quantity"]++;
        } elseif ($action === "decrease") {
            $_SESSION["cart"][$product_id]["quantity"]--;
            if ($_SESSION["cart"][$product_id]["quantity"] <= 0) {
                unset($_SESSION["cart"][$product_id]);
            }
        }
    }

    $_SESSION["cart_count"] = array_sum(array_column($_SESSION["cart"], "quantity"));
    echo json_encode(["status" => "success", "cart_count" => $_SESSION["cart_count"], "cart_html" => getCartHTML()]);
}
?>
