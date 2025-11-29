<?php
include 'auth.php';
redirectIfNotLoggedIn();
include 'dbconnect.php';

// Récupérer la liste des événements et participants
$eventsQuery = $bdd->query('SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC');
$events = $eventsQuery->fetchAll();

$participantsQuery = $bdd->query('SELECT * FROM participants ORDER BY name ASC');
$participants = $participantsQuery->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $participant_id = $_POST['participant_id'];
    $event_id = $_POST['event_id'];

    try {
        // Vérifier si l'inscription existe déjà
        $checkQuery = $bdd->prepare('SELECT * FROM registrations WHERE participant_id = ? AND event_id = ?');
        $checkQuery->execute([$participant_id, $event_id]);
        
        if ($checkQuery->fetch()) {
            $error = "Ce participant est déjà inscrit à cet événement.";
        } else {
            $sql = "INSERT INTO registrations (participant_id, event_id) VALUES (?, ?)";
            $query = $bdd->prepare($sql);
            $query->execute([$participant_id, $event_id]);

            $success = "Inscription réussie !";
        }
    } catch (PDOException $e) {
        $error = "Erreur lors de l'inscription : " . $e->getMessage();
    }
}

$template = "inscrire_participant";
require_once __DIR__ . '/../phtml/layout.phtml';
?>