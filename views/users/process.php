<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $room_no = $_POST["room_no"];
    $ext = $_POST["ext"];

    // Check if passwords match
    if ($password !== $confirm_password) {
        die("Passwords do not match!");
    }

    // File upload
    if (isset($_FILES["profile_picture"])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file);
    }

    // Save to database (Example Connection)
    $conn = new mysqli("localhost", "root", "", "users_db");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO users (name, email, password, room_no, ext, profile_picture)
            VALUES ('$name', '$email', '" . md5($password) . "', '$room_no', '$ext', '$target_file')";
    
    if ($conn->query($sql) === TRUE) {
        echo "User added successfully!";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>






