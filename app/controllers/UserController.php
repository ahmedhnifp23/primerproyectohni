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

        $view = VIEWS_PATH . "/auth/register.php";
        require_once __DIR__ . "/../views/main.php";
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

            //Capture address fields
            $street = trim($_POST['street']);
            $apartment = trim($_POST['apartment'] ?? '');
            $zip = trim($_POST['zip']);
            $city = trim($_POST['city']);
            $province = trim($_POST['province']);
            $country = trim($_POST['country']);

            SessionManager::start();

            //Validate the required fields
            if (empty($first_name) || empty($email) || empty($username) || empty($password) || empty($street) || empty($zip) || empty($city) || empty($province) || empty($country)) {
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
            //Array of addresses
            $addressData = [
                [
                    'street' => $street,
                    'apartment' => $apartment,
                    'zip' => $zip,
                    'city' => $city,
                    'province' => $province,
                    'country' => $country
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
                addresses: $addressData,
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

        $view = VIEWS_PATH . "/auth/login.php";
        require_once __DIR__ . "/../views/main.php";
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

    //Function to logout
    public function logout()
    {
        Auth::logout();
        header('Location: index.php?controller=home&action=index');
        exit();
    }

    //Function to redirect to the configure view based on the user role.
    public function redirectToConfigure()
    {
        SessionManager::redirectBasedOnRole();
    }

    //Function to show the user configure view
    public function showConfigure()
    {
        SessionManager::requireLogin();
        require_once DAOS_PATH . "OrderDAO.php";

        $page_id = "userConfigure";
        $page_title = "Configuración de usuario";

        $user_id = SessionManager::get('user_id');
        $user = $this->userDAO->findById($user_id);
        
        //Prepare data for the view
        $firstName = htmlspecialchars($user->getFirstName());
        $lastName = htmlspecialchars($user->getLastName() ?? '');
        $email = htmlspecialchars($user->getEmail());
        $username = htmlspecialchars($user->getUsername());
        $phone = htmlspecialchars($user->getPhone() ?? '');

        //Get de address and decode it
        $addresses = $user->getAddresses();
        if (is_string($addresses)) {
            $addresses = json_decode($addresses, true);
        }
        if (!is_array($addresses)) {
            $addresses = [];
        }

        $defaultAddr = !empty($addresses) ? $addresses[0] : null;
        
        $displayLine1 = $firstName . ' ' . $lastName;
        $displayLine2 = '';
        
        if ($defaultAddr) {
            $countryName = (isset($defaultAddr['country']) && $defaultAddr['country'] === 'ES') ? 'España' : ($defaultAddr['country'] ?? '');
            $displayLine2 = $countryName;
        }

        $orderDAO = new OrderDAO();
        $orders = $orderDAO->findByUserId($user_id);

        //Check for feedback messages to show in modal
        $feedback = null;
        if (SessionManager::get('profile_success')) {
            $feedback = [
                'type' => 'success',
                'title' => '¡Guardado!',
                'message' => SessionManager::get('profile_success'),
                'icon' => 'bi-check-circle-fill',
                'color' => 'text-success'
            ];
            SessionManager::remove('profile_success');
        } elseif (SessionManager::get('profile_error')) {
            $feedback = [
                'type' => 'error',
                'title' => 'Error',
                'message' => SessionManager::get('profile_error'),
                'icon' => 'bi-x-circle-fill',
                'color' => 'text-danger'
            ];
            SessionManager::remove('profile_error');
        }

        $view = VIEWS_PATH . "/config/profile_config.php";
        require_once __DIR__ . "/../views/main.php";
    }

    //Function to update the user profile data
    public function updateProfile()
    {
        SessionManager::requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $user_id = SessionManager::get('user_id');
            $user = $this->userDAO->findById($user_id);
            
            if (!$user) {
                header('Location: index.php?controller=user&action=login');
                exit();
            }

            //Get data from the form
            $data = $_POST;
            
            //Clears the data and saves into variables
            $first_name = trim($data['first_name'] ?? '');
            $last_name = trim($data['last_name'] ?? '');
            $email = trim($data['email'] ?? '');
            $username = trim($data['username'] ?? '');
            $phone = trim($data['phone'] ?? '');

            //The same with address fields
            $addressInput = $data['address'] ?? [];
            $street = trim($addressInput['street'] ?? '');
            $apartment = trim($addressInput['apartment'] ?? '');
            $zip = trim($addressInput['zip'] ?? '');
            $city = trim($addressInput['city'] ?? '');
            $province = trim($addressInput['province'] ?? '');
            $country = trim($addressInput['country'] ?? '');

            //Validate required fields
            if (empty($first_name) || empty($last_name) || empty($email) || empty($username) || empty($phone) ||
                empty($street) || empty($zip) || empty($city) || empty($province) || empty($country)) {
                 
                 SessionManager::set('profile_error', 'Todos los campos son obligatorios!');
                 header('Location: index.php?controller=user&action=showConfigure');
                 exit();
            }

            //Prepare new addresses array
            $newAddresses = [];
            $newAddresses[] = [
                'street' => $street,
                'apartment' => $apartment,
                'zip' => $zip,
                'city' => $city,
                'province' => $province,
                'country' => $country
            ];

            //Update user object
            $user->setFirstName($first_name);
            $user->setLastName($last_name);
            $user->setEmail($email);
            $user->setUsername($username);
            $user->setPhone($phone);
            $user->setAddresses($newAddresses);

            //Try to save the changes
            try {
                $this->userDAO->update($user);
                
                SessionManager::set('profile_success', 'Tu perfil ha sido actualizado correctamente.');
                header('Location: index.php?controller=user&action=showConfigure');
                exit();
            } catch (Exception $e) {
                SessionManager::set('profile_error', 'Error al guardar los cambios: ' . $e->getMessage());
                header('Location: index.php?controller=user&action=showConfigure');
                exit();
            }
        }
    }
}
