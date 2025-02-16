<?php

require_once __DIR__ . "/../db/Database.php";
require_once __DIR__ . "/../../utils/password-utils.php";


class Room {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function get_all_rooms(){
        try{
            $stmt = $this->db->connect()->prepare("SELECT * FROM rooms");
            $stmt->execute();
            $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $rooms;
        }catch(PDOException $e){
            error_log("Database connection error: " . $e->getMessage());
            return false;
        }
    }
}

