<?php
require_once __DIR__ . '/auth.php';
redirectIfNotLoggedIn();
require_once __DIR__ . '/dbconnect.php';

$query = $bdd->prepare('SELECT r.*, 
                       p.name as participant_name, 
                       p.email as participant_email,
                       e.title as event_title,
                       e.event_date as event_date
                       FROM registrations r
                       JOIN participants p ON r.participant_id = p.participant_id
                       JOIN events e ON r.event_id = e.event_id
                       ORDER BY r.registration_date DESC');
$query->execute();
$inscriptions = $query->fetchAll();

$template = "inscriptions";
require_once __DIR__ . '/../phtml/layout.phtml';
?>