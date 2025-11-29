<?php
// Participant creation handler
require_once __DIR__ . '/auth.php';
redirectIfNotLoggedIn();
require_once __DIR__ . '/dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    if ($name && $email && $phone && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            $sql = "INSERT INTO participants (name, email, phone) VALUES (?, ?, ?)";
            $query = $bdd->prepare($sql);
            $query->execute([$name, $email, $phone]);
            header("Location: participants.php");
            exit();
        } catch (PDOException $e) {
            $error = "Erreur lors de la création du participant : " . $e->getMessage();
        }
    } else {
        $error = "Veuillez remplir tous les champs et fournir un email valide.";
    }
}

$template = "insert_participant";
require_once __DIR__ . '/../phtml/layout.phtml';
?>