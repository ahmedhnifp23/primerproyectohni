<?php

require_once MODELS_PATH . "User.php";
require_once DAOS_PATH . "UserDAO.php";
require_once CORE_PATH . "SessionManager.php";
require_once CORE_PATH . "Auth.php";


class UserController
{
    //Variable will save the instance of the UserDAO.
    private UserDAO $userDAO;

    public function __construct()
    {
        $this->userDAO = new UserDAO();
    }


    public function showRegister()
    {

        require_once VIEWS_PATH . "/auth/register.php";
    }
    //Function that validates the data received fron the register form and creates and saves the new user.
    public function storeRegister()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $first_name = trim($_POST['first_name']);
            $last_name_raw = trim($_POST['last_name'] ?? '');
            $last_name = $last_name_raw === '' ? null : $last_name_raw;
            $email = trim($_POST['email']);
            $username = trim($_POST['username']);
            $password = $_POST['password'];
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $phone_raw = trim($_POST['phone'] ?? '');
            $phone = $phone_raw === '' ? null : $phone_raw;
            $addresses = null; // Addresses can be implemented later
            $birth_date_raw = trim($_POST['birth_date'] ?? '');
            $birth_date = $birth_date_raw === '' ? null : $birth_date_raw;

            SessionManager::start();

            //Validate the required fields
            if (empty($first_name) || empty($email) || empty($username) || empty($password)) {
                SessionManager::set('error_register', 'Por favor, complete todos los campos obligatorios.');
                header('Location: index.php?controller=user&action=showRegister');
                exit();
            }
            //Validate the email.
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                SessionManager::set('error_register', 'Por favor, ingrese un correo electrónico válido.');
                header('Location: index.php?controller=user&action=showRegister');
                exit();
            }

            //Check if the email or username already exists.
            if ($this->userDAO->findByEmail($email)) {
                SessionManager::set('error_register', 'El correo electrónico ya está en uso. Por favor, elija otro.');
                header('Location: index.php?controller=user&action=showRegister');
                exit();
            }
            if ($this->userDAO->findByUsername($username)) {
                SessionManager::set('error_register', 'El nombre de usuario ya está en uso. Por favor, elija otro.');
                header('Location: index.php?controller=user&action=showRegister');
                exit();
            }
            //HardCoded array of addresses to implement later
            $hardcodedAddresses = [
                [
                    'street' => 'Calle Falsa 123',
                    'city' => 'Registerland',
                    'state' => 'CAT',
                    'zip' => '08759',
                    'country' => 'España'
                ]
            ];

            //Create a new user instance
            $newUser = new User(
                first_name: $first_name,
                last_name: $last_name,
                email: $email,
                username: $username,
                password_hash: $password_hash,
                phone: $phone,
                addresses: $hardcodedAddresses,
                birth_date: $birth_date
            );

            //Try to save the user in the db
            try {
                $this->userDAO->create($newUser);
                SessionManager::set('success_register', 'Usuario registrado con éxito. Ahora puede iniciar sesión.');
                header('Location: index.php?controller=user&action=showLogin');
                exit();
            } catch (Exception $e) {
                SessionManager::set('error_register', 'Error al registrar el usuario: ' . $e->getMessage());
                header('Location: index.php?controller=user&action=showRegister');
                exit();
            }
        }
    }

    public function showLogin()
    {
        /* //Verify if is dere any error stored to show it and delete it.
        $error = SessionManager::get('error_login');
        if ($error) SessionManager::remove('error_login');
        //The same if the user comes from a succesfull register.
        $success = SessionManager::get('success_register');
        if ($success) SessionManager::remove('success_register');*/

        require_once VIEWS_PATH . "/auth/login.php";
    }

    public function storeLogin()
    {
        //Check if the requested metod is POST.
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //Save data into varables.
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            //Checks if the user exists and the password of the input and the user obtained match.
            if (Auth::login($email, $password)) {
                header('Location: index.php?controller=home&action=index');
                exit();
            } else {
                //If the user don't exists or the passowrd its incorrect, redirects to the login page with the error.
                SessionManager::start();
                SessionManager::set('error_login', 'Credenciales incorrectas!');
                header('Location: index.php?controller=user&action=showLogin');
                exit();
            }
        }
    }

    public function logout()
    {
        Auth::logout();
        header('Location: index.php?controller=home&action=index');
        exit();
    }
}
