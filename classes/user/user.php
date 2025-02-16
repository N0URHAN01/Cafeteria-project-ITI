<?php

require_once __DIR__ . "/../db/Database.php";
require_once __DIR__ . "/../../utils/password-utils.php";


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

   // get user by user_id
    public function get_user_by_user_id($user_id){
        try{
            $stmt = $this->db->connect()->prepare("SELECT * FROM users WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return $user;
        }catch(PDOException $e){
            error_log("Database connection error: " . $e->getMessage());
            return false;
        }
    }
   
   
   
    //user authentication
    public function auth_user($email,$password):string | false{
        try{

            $stmt = $this->db->connect()->prepare("SELECT user_id, password FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if(!$user){
                return false;
            }

            $hashed_password = hash_password($password,$email);

           if($user['password'] === $hashed_password){
                return $user['user_id'];
           }
           
           return false;
        }catch(PDOException $e){
            error_log("Database connection error: " . $e->getMessage());
            return false;
        }
    }

}