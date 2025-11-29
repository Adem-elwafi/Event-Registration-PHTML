<?php
include 'auth.php';
redirectIfNotLoggedIn();
include 'dbconnect.php';

if (isset($_GET['participant_id']) && isset($_GET['event_id'])) {
    $participant_id = $_GET['participant_id'];
    $event_id = $_GET['event_id'];

    $query = $bdd->prepare("DELETE FROM registrations WHERE participant_id = ? AND event_id = ?");
    $query->execute([$participant_id, $event_id]);

    // Redirection selon la provenance
    if (isset($_GET['from'])) {
        header("Location: " . $_GET['from'] . "?participant_id=" . $participant_id);
    } else {
        header("Location: inscriptions.php");
    }
    exit();
} else {
    header("Location: inscriptions.php");
    exit();
}
?>