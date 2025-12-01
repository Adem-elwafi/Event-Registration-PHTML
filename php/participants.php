<?php
include 'auth.php';
redirectIfNotLoggedIn();

include 'dbconnect.php';

$query = $bdd->prepare('SELECT p.*, 
                       COUNT(r.inscription_id) as nb_inscriptions
                       FROM participants p 
                       LEFT JOIN inscriptions r ON p.participant_id = r.participant_id 
                       GROUP BY p.participant_id 
                       ORDER BY p.name ASC');
$query->execute();
$participants = $query->fetchAll();

$template = "participants";
require_once __DIR__ . '/../phtml/layout.phtml';
?>