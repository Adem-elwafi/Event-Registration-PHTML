<?php
require_once __DIR__ . '/auth.php';
redirectIfNotLoggedIn();
require_once __DIR__ . '/dbconnect.php';

$query = $bdd->prepare('SELECT * FROM events WHERE event_id = ?');
$query->execute([$_GET['event_id']]);
$event = $query->fetch();

// Récupérer les participants inscrits
$participantsQuery = $bdd->prepare('SELECT p.* FROM participants p 
                                   JOIN registrations r ON p.participant_id = r.participant_id 
                                   WHERE r.event_id = ?');
$participantsQuery->execute([$_GET['event_id']]);
$participants = $participantsQuery->fetchAll();

$template = "event_details";
require_once __DIR__ . '/../phtml/layout.phtml';
?>