<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserController
{
    private function checkLogin()
    {
        if(!isset($_SESSION['user_id'])){
            header('Location: /boutique-en-ligne/public/user/login');
            exit;
        }
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = new UserModel();
            
            $data = [
                'username' => $_POST['username'] ?? '',
                'email'    => $_POST['email'] ?? '',
                'password' => $_POST['password'] ?? ''
            ];

            if(empty($data['username']) || empty($data['email']) || empty($data['password'])){
                $error = "Tous les champs sont obligatoires.";

            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
                $error = "L'address email n'est pas valide.";

            } elseif ( strlen($data['password']) < 8){
                $error = "Le mot de passe doit contenir au minimum 8 caractères.";

            } elseif ($userModel->findByEmail($data['email'])){
                $error = "Cet email est déjà utilisé.";
                
            } elseif ($data['password'] !== ($_POST['password_confirm'] ?? '')){
                $error = "Les deux mots de passe doivent être identiques.";
            } else {
                if($userModel->create($data)){
                    header('Location: /boutique-en-ligne/public/user/login');
                    exit;
                } else {
                    $error = "Erreur lors de l'inscription.";
                }
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

    public function profile()
    {
        $this->checkLogin();

        $userModel = new UserModel();
        $user = $userModel->findById($_SESSION['user_id']);

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $data = [
                'username'      => $_POST['username'] ?? '',
                'email'         => $_POST['email'] ?? '',
                'address'       => $_POST['address'] ?? ''
            ];

            if ($userModel->updateProfile($_SESSION['user_id'], $data)){
                $_SESSION['username'] = $data['username'];
                $success = "Profil mis à jour avec succès.";
            } else {
                $error = "Une erreur est survenue.";
            }

            $password = $_POST['password'] ?? '';

            if (!empty($password)){

                $passwordConfirm = $_POST['password_confirm'] ?? '';

                if (strlen($password) < 8){
                    $error = "Le mot de passe doit contenir au moins 8 caractères.";
                } elseif ($password !== $passwordConfirm){
                    $error = "Les deux mots de passe doivent être identiques.";
                } else {
                    $userModel->updatePassword($_SESSION['user_id'], $password);
                }
            }

        }
        require_once __DIR__ . '/../Views/user/profile.php';
    }

    public function delete()
    {
        $this->checkLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $userModel = new UserModel();
            $user = $userModel->deleteAccount($_SESSION['user_id']);

            if($user){
                session_destroy();
                header('Location: /boutique-en-ligne/public/');
                exit;
            }
        }

        header('Location: /boutique-en-ligne/public/user/profile');
        exit;
    }
}
