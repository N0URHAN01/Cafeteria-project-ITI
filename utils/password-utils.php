<?php


function confirm_registration_password($password, $confirmation_password):bool{
    if($password === $confirmation_password){
        return true;
    }else{
        return false;
    }
}

function hash_password($plain_text_password, $user_email):string{
    
    return md5($plain_text_password.$user_email);
    
}

