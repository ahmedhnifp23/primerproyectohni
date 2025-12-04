<?php

class SessionManager{


//Function to start a session.
public static function start(){
    session_start();
}
//function to set a session variable by receiving the key and the value.
public static function set($key, $value){
    $_SESSION[$key] = $value;
}
//Function that returns the session variable by the key.
public static function get($key){
    return $_SESSION[$key] ?? null;
}
//Function to verify if a session variable exists receiving the key.
public static function exists($key){
    return isset($_SESSION[$key]);
}
//Function to remove a session variable by the key
public static function remove($key){
    unset($_SESSION[$key]);
}
//Function to destroy the session.
public static function destroy(){
    session_unset();
    session_destroy();
}

//Function to verify if the user is logged, and if is not, redirects to the login.
public static function requireLogin(){

    self::start();
    if(!self::exists('user_id')){
        header('Location: /index.php');
    }
}

//Function to verify if the user_id is looged as an admin.
public static function requireAdmin(){
    self::requireLogin();

    if(!isset($_SESSION['user_id']['is_admin'])){
        header('Location: /index.php?controller=forbidden&action=show');
    }
}


}


?>