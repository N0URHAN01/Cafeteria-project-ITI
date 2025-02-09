<?php
$host = "localhost"; 
$dbname = "iti_cafeteria"; 
$username = "init0x1"; 
$password = "init0x1"; 

try {
    $connection = new PDO("mysql:host={$host};dbname={$dbname}", $username, $password);
    return $connection;
} catch (PDOException $e) {
    error_log("Database connection error: " . $e->getMessage());
    return null; 
}
?>
