<?php
// Always start session before any output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/auth.php';
redirectIfNotLoggedIn();
if (!isAdmin()) {
    header('Location: index.php');
    exit();
}
require_once __DIR__ . '/dbconnect.php';

// Fetch stats for dashboard
$statsQuery = $bdd->query('SELECT 
    (SELECT COUNT(*) FROM events) as total_events,
    (SELECT COUNT(*) FROM participants) as total_participants,
    (SELECT COUNT(*) FROM registrations) as total_inscriptions');
$stats = $statsQuery->fetch();

$template = "admin_dashboard";
require_once __DIR__ . '/../phtml/layout.phtml';
?>
