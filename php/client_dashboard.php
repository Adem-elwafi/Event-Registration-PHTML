<?php
require_once __DIR__ . '/auth.php';
redirectIfNotLoggedIn();
require_once __DIR__ . '/dbconnect.php';

// Récupérer les événements auxquels le client est inscrit
$query = $bdd->prepare('SELECT e.* FROM events e 
                       JOIN inscriptions r ON e.event_id = r.event_id 
                       JOIN participants p ON r.participant_id = p.participant_id
                       WHERE p.email = ?
                       ORDER BY e.event_date DESC');
$query->execute([$_SESSION['email']]);
$mes_inscriptions = $query->fetchAll();

// Récupérer tous les événements disponibles
$eventsQuery = $bdd->prepare('SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC');
$eventsQuery->execute();
$evenements_disponibles = $eventsQuery->fetchAll();

$template = "client_dashboard";
require_once __DIR__ . '/../phtml/layout.phtml';
?>