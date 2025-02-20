<?php

require_once __DIR__ . "/../db/Database.php";
require_once __DIR__ . "/../../utils/password-utils.php";

class AdminAuth{
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // create new user 
    public function create_user($name,$password,$email,$room_id, $ext,$profile_image){
        try{
            
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

    // create new admin 
    public function create_admin($name,$password,$email,$profile_image){
        try{
            
            $hashed_password = hash_password($password,$email);

            $insert_user = $this->db->connect()->prepare(
                "INSERT INTO admins (name, email, password, profile_image) 
                 VALUES (:name, :email, :password,:profile_image)"
            );

            $insert_user->bindParam(':name', $name);
            $insert_user->bindParam(':email', $email);
            $insert_user->bindParam(':password', $hashed_password);
    
            $insert_user->bindParam(':profile_image', $profile_image);
    
            $insert_user->execute();

            return "user created";
        }catch(PDOException $e){
            error_log("Database connection error: " . $e->getMessage());
            return false;
        }
    }

// auth admin 

        public function auth_admin($email,$password):string | false{
            try{

                $stmt = $this->db->connect()->prepare("SELECT admin_id, password FROM admins WHERE email = :email");
                $stmt->bindParam(':email', $email);
                $stmt->execute();
                $admin = $stmt->fetch(PDO::FETCH_ASSOC);

                if(!$admin){
                    return false;
                }

                $hashed_password = hash_password($password,$email);

               if($admin['password'] === $hashed_password){
                    return $admin['admin_id'];
               }
               
               return false;
            }catch(PDOException $e){
                error_log("Database connection error: " . $e->getMessage());
                return false;
            }
            return true;
        }

}
//for test
 $user = new AdminAuth();
$admin = new AdminAuth();
//var_dump($admin->auth_admin("init0x1@email.com","init0x10Password"));
//var_dump($user->create_user("init0x1","init0x1Password","init0x1@email.com",1,"ext","null.png"));
$admin ->create_admin("admin","1234","admin@gmail.com","null.png");
$admin->auth_admin("admin@gmail.com","1234");
