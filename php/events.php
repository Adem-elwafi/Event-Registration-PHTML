<?php
require_once __DIR__ . '/auth.php';
redirectIfNotLoggedIn();
require_once __DIR__ . '/dbconnect.php';

$query = $bdd->prepare('SELECT e.*, 
                       COUNT(r.registration_id) as nb_inscriptions 
                       FROM events e 
                       LEFT JOIN registrations r ON e.event_id = r.event_id 
                       GROUP BY e.event_id 
                       ORDER BY e.event_date DESC');
$query->execute();
$events = $query->fetchAll();

$template = "events";
require_once __DIR__ . '/../phtml/layout.phtml';
?>