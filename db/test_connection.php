<?php
require_once "db_connaction.php"; 

function test_db_connection($connection):bool{
    try {

        $stmt = $connection->query("SELECT now()");
        
        if ($stmt) {
            return true;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        error_log("Database connection error: " . $e->getMessage());
        return false;
    }
}
?>
    