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


function validate_file($file, $allowed_extensions) {
    $errors = [];

    if (!$file || !isset($file['name']) || empty($file['name'])) {
        $errors["file_error"] = "Please upload an image.";
        return $errors;
    }

    $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    if (!in_array(strtolower($file_extension), $allowed_extensions)) {
        $errors["file_extension"] = "Not allowed file type of .{$file_extension}";
    }

    return $errors;
}