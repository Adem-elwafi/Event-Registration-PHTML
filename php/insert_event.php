<?php
// Event creation handler
require_once __DIR__ . '/auth.php';
redirectIfNotLoggedIn();
require_once __DIR__ . '/dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input (basic example)
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $event_date = trim($_POST['event_date']);
    $lieu = trim($_POST['lieu']);
    $prix = trim($_POST['prix']);

    if ($title && $event_date && $lieu && $prix) {
        try {
            $sql = "INSERT INTO events (title, description, event_date, lieu, prix) VALUES (?, ?, ?, ?, ?)";
            $query = $bdd->prepare($sql);
            $query->execute([$title, $description, $event_date, $lieu, $prix]);
            header("Location: events.php");
            exit();
        } catch (PDOException $e) {
            $error = "Erreur lors de la création de l'événement : " . $e->getMessage();
        }
    } else {
        $error = "Veuillez remplir tous les champs obligatoires.";
    }
}

$template = "insert_event";
require_once __DIR__ . '/../phtml/layout.phtml';
?>