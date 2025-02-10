<?php 

function validate_posted_data($post_request_data){
    $errors = [];
    $valid_data = [];
    foreach ($post_request_data as $key => $value) {
        if (! $value){
            $errors[$key] = "{$key} are required";
        }else{
            $valid_data[$key] = $value;
        }
    }
    return [
        "errors" => $errors,
        "data" => $valid_data 
    ];

}