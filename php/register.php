<?php
require_once __DIR__ . '/dbconnect.php';
require_once __DIR__ . '/auth.php';

redirectIfLoggedIn();

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($username) || empty($email) || empty($password)) {
        $error = "Tous les champs sont obligatoires !";
    } elseif ($password !== $confirm_password) {
        $error = "Les mots de passe ne correspondent pas !";
    } elseif (strlen($password) < 6) {
        $error = "Le mot de passe doit contenir au moins 6 caractères !";
    } else {
        try {
            $checkQuery = $bdd->prepare("SELECT user_id FROM users WHERE username = ? OR email = ?");
            $checkQuery->execute([$username, $email]);
            
            if ($checkQuery->fetch()) {
                $error = "Ce nom d'utilisateur ou email est déjà utilisé !";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // TOUS LES NOUVEAUX COMPTES SONT DES CLIENTS
                $insertQuery = $bdd->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'client')");
                $insertQuery->execute([$username, $email, $hashed_password]);
                
                $success = "Compte client créé avec succès ! Vous pouvez maintenant vous connecter.";
            }
        } catch (PDOException $e) {
            $error = "Erreur lors de la création du compte : " . $e->getMessage();
        }
    }
}

$template = "register";
require_once __DIR__ . '/../phtml/layout.phtml';
?>