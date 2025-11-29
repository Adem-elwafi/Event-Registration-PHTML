<?php
include 'auth.php';
redirectIfNotLoggedIn();

if (!isAdmin()) { // Seulement pour les clients
    include 'dbconnect.php';
    
    $event_id = $_GET['event_id'];
    
    // Vérifier si l'événement existe
    $eventQuery = $bdd->prepare("SELECT * FROM events WHERE event_id = ?");
    $eventQuery->execute([$event_id]);
    $event = $eventQuery->fetch();
    
    if ($event) {
        // Vérifier si le participant existe déjà (basé sur l'email de l'utilisateur connecté)
        $participantQuery = $bdd->prepare("SELECT * FROM participants WHERE email = ?");
        $participantQuery->execute([$_SESSION['email']]);
        $participant = $participantQuery->fetch();
        
        if (!$participant) {
            // Créer le participant s'il n'existe pas
            $insertParticipant = $bdd->prepare("INSERT INTO participants (name, email) VALUES (?, ?)");
            $insertParticipant->execute([$_SESSION['username'], $_SESSION['email']]);
            $participant_id = $bdd->lastInsertId();
        } else {
            $participant_id = $participant['participant_id'];
        }
        
        // Vérifier si déjà inscrit
        $checkInscription = $bdd->prepare("SELECT * FROM registrations WHERE participant_id = ? AND event_id = ?");
        $checkInscription->execute([$participant_id, $event_id]);
        
        if ($checkInscription->fetch()) {
            $error = "Vous êtes déjà inscrit à cet événement !";
        } else {
            // Créer l'inscription
            $insertInscription = $bdd->prepare("INSERT INTO registrations (participant_id, event_id) VALUES (?, ?)");
            $insertInscription->execute([$participant_id, $event_id]);
            
            $success = "Félicitations ! Vous êtes maintenant inscrit à l'événement : " . $event['title'];
        }
    } else {
        $error = "Événement non trouvé !";
    }
    
    $template = "sinscrire_client";
    require_once __DIR__ . '/../phtml/layout.phtml';
} else {
    header("Location: index.php");
    exit();
}
?>