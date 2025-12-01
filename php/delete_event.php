<?php
// Event deletion handler
require_once __DIR__ . '/auth.php';
redirectIfNotLoggedIn();
require_once __DIR__ . '/dbconnect.php';

if (isset($_GET['event_id'])) {
    try {
        // Delete registrations linked to this event
        $deleteRegistrations = $bdd->prepare("DELETE FROM inscriptions WHERE event_id = ?");
        $deleteRegistrations->execute([$_GET['event_id']]);
        // Delete the event itself
        $deleteEvent = $bdd->prepare("DELETE FROM events WHERE event_id = ?");
        $deleteEvent->execute([$_GET['event_id']]);
    } catch (PDOException $e) {
        // Log error or display message
        $error = "Erreur lors de la suppression de l'événement.";
    }
}

header("Location: events.php");
exit();
?>