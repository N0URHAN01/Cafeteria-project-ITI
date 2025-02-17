<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $imagePath = "";

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $imagePath = $targetDir . basename($_FILES["image"]["name"]);
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)) {
            die("Error uploading file.");
        }
    }

    // Insert into database
    $sql = "INSERT INTO products (name, price, category, image) VALUES ('$name', '$price', '$category', '$imagePath')";
    if ($conn->query($sql) === TRUE) {
        header("Location: products.php"); // Redirect to products page
        exit();
    } else {
        die("Error: " . $conn->error);
    }
}
$conn->close();

?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "Form Submitted!";
} else {
    echo "Form Not Submitted!";
}

?>
