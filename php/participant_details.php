<?php
require_once __DIR__ . '/auth.php';
redirectIfNotLoggedIn();
require_once __DIR__ . '/dbconnect.php';

$query = $bdd->prepare('SELECT * FROM participants WHERE participant_id = ?');
$query->execute([$_GET['participant_id']]);
$participant = $query->fetch();

// Récupérer les événements auxquels le participant est inscrit
$eventsQuery = $bdd->prepare('SELECT e.* FROM events e 
                             JOIN inscriptions r ON e.event_id = r.event_id 
                             WHERE r.participant_id = ? 
                             ORDER BY e.event_date DESC');
$eventsQuery->execute([$_GET['participant_id']]);
$events = $eventsQuery->fetchAll();

$template = "participant_details";
require_once __DIR__ . '/../phtml/layout.phtml';
?>