<?php
require_once __DIR__ . "/../../classes/db/Database.php";
require_once __DIR__ . "/../../utils/password-utils.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $db = new Database();
    $conn = $db->connect();


    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $ext = $_POST["ext"] ?? null;
    $profile_image = "default.png"; 

 
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);

    if ($stmt->rowCount() > 0) {
        echo "Email already exists!";
        exit;
    }

    $hashed_password = hash_password($password, $email);

    
    if (!empty($_FILES["profile_image"]["name"])) {
        $upload_dir = __DIR__ . "/../../uploads/";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $profile_image = time() . "_" . basename($_FILES["profile_image"]["name"]);
        $upload_path = $upload_dir . $profile_image;

        move_uploaded_file($_FILES["profile_image"]["tmp_name"], $upload_path);
    }

   
    $stmt = $conn->prepare(
        "INSERT INTO users (name, email, password, ext, profile_image) 
         VALUES (:name, :email, :password, :ext, :profile_image)"
    );

    $stmt->execute([
        'name' => $name,
        'email' => $email,
        'password' => $hashed_password,
        'ext' => $ext,
        'profile_image' => $profile_image
    ]);

    
    header("Location: ../../views/user/login.php?success=User added successfully");
    exit;
}
?>
