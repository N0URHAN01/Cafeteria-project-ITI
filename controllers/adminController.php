<?php

require_once __DIR__ ."/../classes/admin/admin-auth.php";
require_once __DIR__ . "/../utils/validator.php";

$auth = new AdminAuth();
$admin_login_errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
   
    $post_data = validate_posted_data($_POST);

    $admin_login_errors = array_merge($admin_login_errors, $post_data['errors']);

        $email = filter_var($post_data["data"]["email"], FILTER_SANITIZE_EMAIL);
        $password = $post_data["data"]["password"];
        

        // auth admin
        $admin_id = $auth->auth_admin($email, $password);
       
        if ($admin_id !== false) {
            session_start();
            
            $_SESSION["is_admin"] = true;
            $_SESSION["admin_id"] = $admin_id;
            
            header("Location: ../views/admin/admin_dashboard.php");
            exit;
        } else {
            
            $admin_login_errors["auth_errors"] = "invalid email or password";
            
            $errors_json = urlencode(json_encode($admin_login_errors));
            $old_data_json = urlencode(json_encode($post_data["data"]));
            
            header("Location: ../views/admin/admin_login.php?errors={$errors_json}&old_data={$old_data_json}");
            exit;
        }
    }

?>
