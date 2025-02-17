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

if (!init()) {
    die(" some services dose not work ");
}