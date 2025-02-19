<?php
session_start();
require_once __DIR__ . "/../../classes/db/Database.php";
require_once __DIR__ . "/../../classes/admin/category.php";

// Ensure the user is an admin
if (!isset($_SESSION["is_admin"]) || !isset($_SESSION["admin_id"])) {
    header("Location: ../../views/admin/login.php");
    exit;
}


$categoryController = new Category();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["category_name"])) {
    $name = trim($_POST["category_name"]);

    if (!empty($name)) {
        $success = $categoryController->addCategory($name);
        if ($success) {
            $_SESSION["success"] = "Category added successfully!";
        } else {
            $_SESSION["error"] = "Failed to add category. It may already exist.";
        }
    } else {
        $_SESSION["error"] = "Category name is required!";
    }

    header("Location: ../../views/admin/Categories.php");
    exit;
}
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $categoryController->deleteCategory($_POST['category_id']);
    header("Location: ../../views/admin/categories.php");
}

