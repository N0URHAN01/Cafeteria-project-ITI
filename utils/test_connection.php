<?php

function test_db_connection($connection): bool {
    try {
        $pdo = $connection->connect(); 
        

        if (!$pdo) {
            return false;
        }

        $stmt = $pdo->prepare("SELECT NOW()");
        $stmt->execute();
        return $stmt->fetch() ? true : false;
    } catch (PDOException $e) {
        error_log("Database connection error: " . $e->getMessage());
        return false;
    }
}
