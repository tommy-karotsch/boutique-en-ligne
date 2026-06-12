<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\CartModel;

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

            } elseif ($data['password'] !== ($_POST['password_confirm'] ?? '')){
                $error = "Les deux mots de passe doivent être identiques.";

            } elseif ($userModel->findByEmail($data['email'])){
                $error = "Cet email est déjà utilisé.";

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
                
                $cartModel = new CartModel();
                $cartId = $cartModel->getOrCreateCartId($user['id']);

                $sessionCart = $_SESSION['cart'] ?? [];
                $dbCart = $cartModel->getQuantities($cartId);

                $merged = $dbCart;
                foreach ($sessionCart as $itemId => $quantity){
                    $merged[$itemId] = max($quantity, $dbCart[$itemId] ?? 0);
                }

                $cartModel->save($cartId, $merged);
                $_SESSION['cart'] = $merged;
                
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

            $errors = [];

            if ($userModel->updateProfile($_SESSION['user_id'], $data)){
                $_SESSION['username'] = $data['username'];
            } else {
                $errors[] = "Une erreur est survenue lors de la mise à jour du profil.";
            }

            $password = $_POST['password'] ?? '';

            if (!empty($password)){

                $passwordConfirm = $_POST['password_confirm'] ?? '';

                if (strlen($password) < 8){
                    $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
                } elseif ($password !== $passwordConfirm){
                    $errors[] = "Les deux mots de passe doivent être identiques.";
                } else {
                    $userModel->updatePassword($_SESSION['user_id'], $password);
                }
            }

            if (empty($errors)){
                $success = "Modifications enregistrées avec succès.";
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
