<?php

require_once __DIR__ . "/../db/Database.php";

class User{
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    function email_used($email):bool{
        try{
            
            $user_exist =  $this->db->connect()->prepare("SELECT user_id FROM users WHERE email = :email");
            $user_exist->execute(['email' => $email]);
            
            if($user_exist->rowCount()>0){
                return true;
            }

            return false;

        }catch(PDOException $e){
            error_log("Database connection error: " . $e->getMessage());
            throw $e;
        }
    }

}