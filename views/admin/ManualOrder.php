<?php
session_start();
require_once __DIR__ . "/../../classes/order/Order.php";
require_once __DIR__ . "/../../classes/product/product.php";
require_once __DIR__ . "/../../classes/user/user.php";
require_once __DIR__ . "/../../classes/admin/room.php";
require_once __DIR__ . "/../../middleware/authMiddleware.php";

requireAuthAdmin();
$order = new Order();
$product = new Product();
$user = new User();
$room = new Room();

// Fetch users, products, and rooms
$users = $user->get_all_users();
$products = $product->get_all_products();
$rooms = $room->get_all_rooms();

// Filter products to show only those with status "available"
$availableProducts = array_filter($products, function($product) {
    return $product['status'] === 'available'; // Assuming 'status' is a field in the product table
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manual Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Manual Order</h2>
        
        <!-- User Selection -->
        <form method="POST" action="../../controllers/order/place_order.php">
            <label for="userSelect" class="form-label">Add to User:</label>
            <select id="userSelect" name="user_id" class="form-select">
                <option value="">Select User</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?= $user['user_id'] ?>" data-room="<?= $user['room_id'] ?>"><?= htmlspecialchars($user['name']) ?></option>
                <?php endforeach; ?>
            </select>

            <!-- Product Selection -->
            <div class="row mt-4">
                <?php foreach ($availableProducts as $product): ?>
                    <div class="col-md-3 text-center">
                        <button type="button" class="btn btn-outline-primary product-btn" data-id="<?= $product['product_id'] ?>" data-name="<?= htmlspecialchars($product['name']) ?>" data-price="<?= $product['price'] ?>" data-stock="<?= $product['stock_quantity'] ?>">
                            <img src="../../uploads/products/<?= $product['image_url'] ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="img-fluid mb-2" width="80">
                            <p><?= htmlspecialchars($product['name']) ?> - <?= $product['price'] ?> LE</p>
                            <p><?= htmlspecialchars($product['stock_quantity']) ?> Stock Quantity</p>
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Order Summary -->
            <h3 class="mt-4">Order Summary</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody id="orderSummary"></tbody>
            </table>

            <!-- Hidden Inputs for Order Items -->
            <div id="orderItemsInputs"></div>

            <!-- Room Selection -->
            <div class="mb-3">
                <label for="roomSelect" class="form-label">Room:</label>
                <select id="roomSelect" name="room_id" class="form-select">
                    <option value="">Select Room</option>
                    <?php foreach ($rooms as $room): ?>
                        <option value="<?= $room['room_id'] ?>"><?= $room['room_number'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Total Price & Confirm Button -->
            <h4>Total: <span id="totalPrice">0</span> LE</h4>
            <button type="submit" id="confirmOrder" class="btn btn-success">Confirm</button>
        </form>
    </div>

    <script>
        let orderItems = {};
        
        // Auto-select room based on selected user
        $("#userSelect").change(function() {
            let roomId = $(this).find(":selected").data("room");
            $("#roomSelect").val(roomId);
        });

        // Add product to order
        $(".product-btn").click(function() {
            let id = $(this).data("id");
            let name = $(this).data("name");
            let price = $(this).data("price");
            let stockQty = parseInt($(this).data("stock")); // Get stock quantity from data attribute

            if (!orderItems[id]) {
                orderItems[id] = { name, price, quantity: 1, stockQty };
            } else if (orderItems[id].quantity < orderItems[id].stockQty) {
                orderItems[id].quantity++;
            } else {
                alert("Cannot add more. Stock limit reached.");
                return;
            }

            updateOrderSummary();
        });

        // Update order summary
        function updateOrderSummary() {
            let total = 0;
            $("#orderSummary").empty();
            $("#orderItemsInputs").empty();

            for (let id in orderItems) {
                let item = orderItems[id];
                total += item.price * item.quantity;

                $("#orderSummary").append(`
                    <tr>
                        <td>${item.name}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-secondary changeQty" data-id="${id}" data-change="-1">-</button>
                            ${item.quantity}
                            <button type="button" class="btn btn-sm btn-secondary changeQty" data-id="${id}" data-change="1">+</button>
                        </td>
                        <td>${item.price * item.quantity} LE</td>
                        <td><button type="button" class="btn btn-sm btn-danger removeItem" data-id="${id}">X</button></td>
                    </tr>
                `);

                // Add hidden inputs for each product
                $("#orderItemsInputs").append(`
                    <input type="hidden" name="products[${id}][product_id]" value="${id}">
                    <input type="hidden" name="products[${id}][quantity]" value="${item.quantity}">
                    <input type="hidden" name="products[${id}][price]" value="${item.price}">
                `);
            }

            $("#totalPrice").text(total);
        }

        // Change quantity
        $(document).on("click", ".changeQty", function() {
            let id = $(this).data("id");
            let change = parseInt($(this).data("change"));

            if (orderItems[id]) {
                let newQuantity = orderItems[id].quantity + change;
                if (newQuantity > 0 && newQuantity <= orderItems[id].stockQty) {
                    orderItems[id].quantity = newQuantity;
                } else if (newQuantity > orderItems[id].stockQty) {
                    alert("Cannot increase quantity. Stock limit reached.");
                    return;
                } else if (newQuantity <= 0) {
                    delete orderItems[id];
                }
            }

            updateOrderSummary();
        });

        // Remove item
        $(document).on("click", ".removeItem", function() {
            let id = $(this).data("id");
            delete orderItems[id];
            updateOrderSummary();
        });
    </script>
</body>
</html>
