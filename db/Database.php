<?php
require_once "db_connaction.php";

class Database {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    // Connect to the database
    public function connect() {
        return $this->connection;
    }
/*
    // Insert 
    public function insert($table, $columns, $values) {
        $colStr = implode(", ", $columns);
        $placeholders = implode(", ", array_fill(0, count($values), "?"));
        
        $sql = "INSERT INTO $table ($colStr) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($values);
    }

    // Select 
    public function select($table) {
        $sql = "SELECT * FROM $table";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update 
    public function update($table, $id, $data) {
        $setStr = implode(", ", array_map(fn($col) => "$col = ?", array_keys($data)));
        $values = array_values($data);
        $values[] = $id;
        
        $sql = "UPDATE $table SET $setStr WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($values);
    }

    // Delete 
    public function delete($table, $id) {
        $sql = "DELETE FROM $table WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
*/
    }    
?>
