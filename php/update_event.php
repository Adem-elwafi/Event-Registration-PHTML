<?php
require_once __DIR__ . '/auth.php';
redirectIfNotLoggedIn();
require_once __DIR__ . '/dbconnect.php';

// Récupération de l'événement à modifier
$query = $bdd->prepare("SELECT * FROM events WHERE event_id = ?");
$query->execute([$_GET['event_id']]);
$event = $query->fetch();

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $event_date = $_POST['event_date'];
    $lieu = $_POST['lieu'];
    $prix = $_POST['prix'];
    $id = $_POST['event_id'];

    $sql = "UPDATE events 
            SET title = :title, 
                description = :description, 
                event_date = :event_date, 
                lieu = :lieu, 
                prix = :prix 
            WHERE event_id = :id";

    $query = $bdd->prepare($sql);
    $query->bindValue(':title', $title);
    $query->bindValue(':description', $description);
    $query->bindValue(':event_date', $event_date);
    $query->bindValue(':lieu', $lieu);
    $query->bindValue(':prix', $prix);
    $query->bindValue(':id', $id);

    $query->execute();

    header("Location: event_details.php?event_id=" . $id);
    exit();
}

$template = "update_event";
require_once __DIR__ . '/../phtml/layout.phtml';
?>