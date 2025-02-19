<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


function requireAuthUser() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../../views/user/login.php?error=Unauthorized access");
        exit;
    }
}


function requireAuthAdmin() {
    if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
        header("Location: ../../views/admin/login.php?error=Unauthorized admin access");
        exit;
    }
}
