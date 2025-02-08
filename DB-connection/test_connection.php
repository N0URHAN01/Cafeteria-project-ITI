<?php
require_once "connaction.php"; // Include your connection file

try {
    // Test query to check if connection works
    $stmt = $pdo->query("SELECT 1");
    
    if ($stmt) {
        echo "✅ Database connection successful!";
    } else {
        echo "❌ Query failed!";
    }
} catch (PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage();
}
?>
