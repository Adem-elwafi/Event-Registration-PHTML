<?php
// Login handler
require_once __DIR__ . '/dbconnect.php';
require_once __DIR__ . '/auth.php';

redirectIfLoggedIn();

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username/email and password from POST
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Find user by username or email
        $query = $bdd->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $query->execute([$username, $username]);
        $user = $query->fetch();

        // Verify password
        if ($user && password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            // For security, regenerate session ID after login
            session_regenerate_id(true);
                // Route to correct dashboard
                if ($user['role'] === 'admin') {
                    header("Location: index.php");
                } else {
                    header("Location: client_dashboard.php");
                }
                exit();
        } else {
            $error = "Nom d'utilisateur ou mot de passe incorrect !";
        }
    } catch (PDOException $e) {
        $error = "Erreur de connexion : " . $e->getMessage();
    }
}

$template = "login";
require_once __DIR__ . '/../phtml/layout.phtml';
?>