<?php

require_once __DIR__ . "/db/Database.php";


class OrderProduct {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }


    public function getAllProductOfSpecificOrder($order_id) {
        try {
           
            $stmt = $this->db->connect()->prepare(
                "SELECT * FROM ordered_products WHERE  order_id = :order_id"
            );
           
            $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database query error: " . $e->getMessage());
            return [];
        }
    }

}