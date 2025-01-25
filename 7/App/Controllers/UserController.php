<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Session;
use Framework\Validation;

class UserController
{
    /** */

    /**
     * @var Database
     */
    protected $db;

    public function __construct()
    {
        $config = basePath('config/db.php');
        $this->db = new Database($config);
    }

    /**
     * @return void
     */
    public function login()
    {
        loadView('users/login');
    }

    /**
     * @return void
     */
    public function create()
    {
        loadView('users/create');
    }

    public function store()
    {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $city = $_POST['city'] ?? '';
        $state = $_POST['state'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['PasswordConfirmation'] ?? '';
        
        $errors = [];

        if (!Validation::email($email)){
            $errors['email'] = 'Please enter a valid email.';
        }
        if (!Validation::string($name,2,50)){
            $errors['name'] = 'Name must be between two and fifty characters.';
        }
        if (!Validation::string($password,2,50)){
            $errors['password'] = 'Name must be between two and fifty characters.';
        }
        if (!Validation::match($password,$confirmPassword)){
            $errors['confirmPassword'] = 'Password confirmation dose not match.';
        }
        if (!empty($errors)){
            loadView('users/create',[
                'errors'=>$errors,
                'users' => [
                    'name' => $name,
                    'email'=> $email,
                    'city'=> $city,
                    'state'=> $state,
                ]
                ]);
                exit;
        }
        
        $params = ['email'=>$email];
        $user = $this->db->query('SELECT * FROM user WHERE email = :email', $params)->fetch();
        if ($user){
            $errors['email'] = 'That email already exists.';
            loadView('users/create',[
                'errors'=> $errors
            ]);
            exit;
        }

        $params = [
            'name' => $name,
            'email'=> $email,
            'city'=> $city,
            'state'=> $state,
            'password'=> password_hash($password, PASSWORD_DEFAULT)];

        
        $this->db->query('INSERT INTO users (name, email, city, state, password) VALUES (:name, :email, :city, :state, :password)', $params);

        $userId = $this->db->conn->lastInsertId();

        Session::set('user', [
            'id' => $userId,
            'name' => $name,
            'email' => $email,
            'city' => $city,
            'state' => $state,
        ]);

        redirect('/');

    }

    public function logout()
        {
         Session::clearAll();
         
         $past = 86400;
         $params = session_get_cookie_params();
         setcookie('PHPSESSID', '', time() - $past, $params['path'], $params['domain']);

         redirect('/');

        }

    public function authenticated()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $errors = [];

        // Validation
        if (!Validation::email($email)) {
            $errors['email'] = 'Please enter a valid email';
        }
        if (!Validation::string($password, 6, 50)) {
            $errors['password'] = 'Password must be at least 6 characters';
        }

        // Check for errors
        if (!empty($errors)) {
            loadView('users/login', [
                'errors' => $errors
            ]);
            exit;
        }

        $params = ['email' => $email];

        $user = $this->db->query('SELECT * FROM users WHERE email = :email',$params)->fetch();

        if (!$user){
            $errors['login'] = 'Incorrect credentials';
            loadView('users/login',[
                'errors'=>$errors
            ]);
            exit;
        }
        if (!password_verify($password, $user->password)){
            $errors['login'] = 'Incorrect credentials';
            loadView('users/login',[
                'errors'=>$errors
            ]);
            exit;            
        }

        
        // Set user session
        Session::set('user', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'city' => $user->city,
            'state' => $user->state
        ]);

        redirect('/');
    }
}