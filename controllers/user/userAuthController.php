<?php
session_start();
require_once __DIR__ . "/../../classes/db/Database.php";
require_once __DIR__ . "/../../utils/password-utils.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $db = new Database();
    $conn = $db->connect();

    $email = $_POST["email"];
    $password = $_POST["password"];

    
    $stmt = $conn->prepare("SELECT user_id, password, name, profile_image FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $user["password"] === hash_password($password, $email)) {
        $_SESSION["user_id"] = $user["user_id"];
        $_SESSION["user_name"] = $user["name"];
        $_SESSION["user_image"] = $user["profile_image"];

        header("Location: ../../views/user/home.php");
        exit;
    } else {
        header("Location: ../../views/user/login.php?error=Invalid credentials");
        exit;
    }
}
?>
