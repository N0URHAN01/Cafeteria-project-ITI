<?php
require_once __DIR__ . "/../../classes/admin/admin-auth.php";
require_once __DIR__ . "/../../classes/user/user.php";
require_once __DIR__ . "/../../utils/validator.php";
require_once __DIR__ . "/../../utils/password-utils.php";

$auth = new AdminAuth();
$user = new User();
$create_user_errors = [];
$images_dir = __DIR__ . '/../../uploads/users';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $post_data = validate_posted_data($_POST);
    $create_user_errors = array_merge($create_user_errors, $post_data['errors']);

    $name = $_POST["name"] ?? '';
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $password = $_POST["password"] ?? '';
    $confirmation_password = $_POST['confirmPassword'] ?? '';
    $ext = $_POST["ext"] ?? null;
    $room_id = $_POST["room_id"] ?? null;

    // image
    $profile_image = $_FILES['profile_image'] ?? null;
    $image_name = $profile_image['name'] ?? ''; 
    $image_tmp_name = $profile_image['tmp_name'] ?? '';
    $allowed_extensions = ["jpg", "jpeg", "png"];

    if (!empty($image_tmp_name)) {
        $file_errors = validate_file($profile_image, $allowed_extensions);
        $create_user_errors = array_merge($create_user_errors, $file_errors);
    } else {
        $create_user_errors['file_upload'] = "No image uploaded";
    }

    
    if (!confirm_registration_password($password, $confirmation_password)) {
        $create_user_errors['password_mismatch'] = "Passwords do not match";
    }

    //check email
    if ($user->email_used($email)) {
        $create_user_errors['user_email'] = "Email is already in use";
    }

    if (empty($create_user_errors)) {
        // upload image
        $image_path = null;
        $image_id = null;
        
        if (!empty($image_tmp_name)) {
            $image_id = uniqid();
            $new_image_name = $image_id . "_" . basename($image_name);
            $image_path = $images_dir . "/" . $new_image_name;
            var_dump($image_path);
            if (!move_uploaded_file($image_tmp_name, $image_path)) {
                $image_path = null; 
            }
        }

        // Create user
        $new_user = $auth->create_user($name, $password, $email, $room_id, $ext, $new_image_name);

        if ($new_user) {
            header("Location: ../../views/user/admin_dashboard.php?success=User added successfully");
            exit;
        }
    }

    // redirec with errors
    $errors_json = urlencode(json_encode($create_user_errors));
    $old_data_json = urlencode(json_encode($post_data["data"]));
    header("Location: ../../views/admin/admin_dashboard.php?errors={$errors_json}&old={$old_data_json}");
    exit;
}
