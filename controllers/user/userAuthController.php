<?php
 session_start();
require_once __DIR__ . "/../../classes/user/user.php";
require_once __DIR__ . "/../../utils/validator.php";


$auth = new User();
$user_login_errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $post_data = validate_posted_data($_POST);
    $user_login_errors = array_merge($user_login_errors, $post_data['errors']);

    $email = filter_var($post_data["data"]["email"], FILTER_SANITIZE_EMAIL);
    $password = $post_data["data"]["password"];

    // auth user
   $user_id = $auth->auth_user($email, $password); 

    if ($user_id !== false) {
        $user = $auth->get_user_by_user_id($user_id);
        $_SESSION["user_id"] = $user_id;
        $_SESSION["user_name"] = $user["name"];
        $_SESSION["user_image"] = $user["profile_image"];

        header("Location: ../../views/user/home.php");
        exit;
    } else {
        $user_login_errors["auth_errors"] = "Invalid email or password";
        $errors_json = urlencode(json_encode($user_login_errors));
        $old_data_json = urlencode(json_encode($post_data["data"]));
        header("Location: ../../views/user/login.php?errors={$errors_json}&old_data={$old_data_json}");
        exit;
    }
}
?>
