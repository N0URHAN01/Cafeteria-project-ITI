<?php
session_start();

$whoami = $_SESSION['is_admin'] ? "admin" : "user";

session_destroy();

if ($whoami === "admin") {
    header("Location: ../views/admin/login.php");
    exit();
}
header("Location: ../views/user/login.php");
exit();