require_once __DIR__ . "/../../classes/product/product.php";

if (isset($_POST['category_id'])) {
    $productObj = new Product();
    $products = $productObj->get_products_by_category($_POST['category_id']);

    foreach ($products as $product) {
        echo '
        <div class="col-md-4 mb-4">
            <div class="card">
                <img src="../../uploads/products/' . htmlspecialchars($product['image_url']) . '" class="card-img-top">
                <div class="card-body">
                    <h5 class="card-title">' . htmlspecialchars($product['name']) . '</h5>
                    <p class="price">$' . number_format($product['price'], 2) . '</p>
                    <button class="btn btn-success add-to-cart" data-product-id="' . $product['product_id'] . '">Add to Cart</button>
                </div>
            </div>
        </div>';
    }
}
