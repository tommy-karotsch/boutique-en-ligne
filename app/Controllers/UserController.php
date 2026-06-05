<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserController
{
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = new UserModel();
            
            $data = [
                'username' => $_POST['username'] ?? '',
                'email'    => $_POST['email'] ?? '',
                'password' => $_POST['password'] ?? ''
            ];

            if ($userModel->create($data)) {
                header('Location: /boutique-en-ligne/public/user/login');
                exit;
            } else {
                $error = "Erreur lors de l'inscription.";
            }
        }
        
        require_once __DIR__ . '/../Views/user/register.php';
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $userModel = new UserModel();
            $user = $userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['username'] = $user['username'];
                
                header('Location: /boutique-en-ligne/public/');
                exit;
            } else {
                $error = "Identifiants incorrects.";
            }
        }
        
        require_once __DIR__ . '/../Views/user/login.php';
    }

    public function logout()
    {
        session_destroy();
        header('Location: /boutique-en-ligne/public/');
        exit;
    }
}
