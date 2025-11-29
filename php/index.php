<?php
// AJOUTEZ CES 2 LIGNES AU DÉBUT
include 'auth.php';
redirectIfNotLoggedIn();

include 'dbconnect.php';
$query = $bdd->prepare('SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC LIMIT 6');
$query->execute();
$evenements = $query->fetchAll();

$statsQuery = $bdd->query('SELECT 
    (SELECT COUNT(*) FROM events) as total_events,
    (SELECT COUNT(*) FROM participants) as total_participants,
    (SELECT COUNT(*) FROM registrations) as total_inscriptions');
$stats = $statsQuery->fetch();

$template = "index";
require_once __DIR__ . '/../phtml/layout.phtml';
?>