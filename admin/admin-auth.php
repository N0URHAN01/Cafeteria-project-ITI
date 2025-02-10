<?php

require_once "./includes/Database.php";
require_once "./utils/password-utils.php";

class auth{
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // create new user 
    public function create_user($name,$password,$email,$room_id, $ext,$profile_image){
        try{
            // check if user exist 
            $user_exist =  $this->db->connect()->prepare("SELECT user_id FROM users WHERE email = :email");
            $user_exist->execute(['email' => $email]);
            
            if($user_exist->rowCount()>0){
                return "email exist";
            }

            $hashed_password = hash_password($password,$email);

            $insert_user = $this->db->connect()->prepare(
                "INSERT INTO users (name, email, password, room_id, ext, profile_image) 
                 VALUES (:name, :email, :password, :room_id, :ext, :profile_image)"
            );

            $insert_user->bindParam(':name', $name);
            $insert_user->bindParam(':email', $email);
            $insert_user->bindParam(':password', $hashed_password);
            $insert_user->bindParam(':room_id', $room_id, PDO::PARAM_INT);
            $insert_user->bindParam(':ext', $ext);
            $insert_user->bindParam(':profile_image', $profile_image);
    
            $insert_user->execute();

            return "user created";
        }catch(PDOException $e){
            error_log("Database connection error: " . $e->getMessage());
            return false;
        }
    }


}

$user = new auth();
var_dump($user->create_user("init0x1","init0x1Password","init0x1@email.com",1,"ext","null.png"));