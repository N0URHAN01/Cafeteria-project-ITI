<?php
    require_once __DIR__ .  "/utils/test_connection.php";
    require_once __DIR__ .  "/classes/db/Database.php";

   

    function init():bool{
        $connection = new Database();
        $db_live = test_db_connection($connection);
        if($db_live){
            return true;
        }
        return false;
}

if (init()) {
    $request_uri = $_SERVER['REQUEST_URI'];
    switch ($request_uri) {
        case '/':
            header('Location: /views/user/home.php');
            break;
        case '/admin':
            header('location: /views/admin/ManualOrder.php');
            break;
        default:
            header('Location: /views/user/home.php');
            break;
    }

}else{
    die(" some services dose not work ");
}