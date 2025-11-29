<?php
include 'dbconnect.php';
include 'auth.php';
redirectIfNotLoggedIn();

if (isset($_GET['participant_id'])) {
    // Les inscriptions seront automatiquement supprimées grâce à ON DELETE CASCADE
    $query = $bdd->prepare("DELETE FROM participants WHERE participant_id = ?");
    $query->execute([$_GET['participant_id']]);
}

header("Location: participants.php");
exit();
?>