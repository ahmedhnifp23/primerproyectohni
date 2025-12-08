<?php

require_once MODELS_PATH . "User.php";
require_once DAOS_PATH . "UserDAO.php";
require_once CORE_PATH . "SessionManager.php";

class Auth
{


    public static function login($email, $password)
    {
        $dao = new UserDAO();
        $user = $dao->findByEmail($email);

        //Checks if the user exists and the password of the input and the user obtained match.
        if ($user && password_verify($password, $user->getPasswordHash())) {

            //Save the user_id into the session storage and redirect to the home page.
            SessionManager::start();
            SessionManager::set('user_id', $user->getUserId());
            SessionManager::set('username', $user->getUsername());
            $user->getIsAdmin() === 1 ? SessionManager::set('is_admin', true) : SessionManager::set('is_admin', false);
            return true;
        }

        return false;
    }

    //Function to check if is any user logged.
    public static function isLogged()
    {
        return SessionManager::exists('user_id');
    }

    //Function to get the user_id of the user logged.
    public static function id()
    {
        return SessionManager::get('user_id');
    }


    //Function to check if the logged user is admin or not.
    public static function isAdmin(){
        return SessionManager::get('is_admin') === true;
    }
    //Function to check if the user is admin but checking the database.
    public static function isAdminStrict()
    {
        $user_id = self::id();
        if(!$user_id) return false;

        $dao = new UserDAO();
        $user = $dao->findById($user_id);

        if ($user && $user->getIsAdmin() === 1) {
            return true;
        }
        SessionManager::set('is_admin', false);
        return false;
    }

    //Function to logout.
    public static function logout(){
        SessionManager::destroy();
    }
}
