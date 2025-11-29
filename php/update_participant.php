<?php
require_once __DIR__ . '/auth.php';
redirectIfNotLoggedIn();
require_once __DIR__ . '/dbconnect.php';

// Récupération du participant à modifier
$query = $bdd->prepare("SELECT * FROM participants WHERE participant_id = ?");
$query->execute([$_GET['participant_id']]);
$participant = $query->fetch();

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $id = $_POST['participant_id'];

    try {
        $sql = "UPDATE participants 
                SET name = :name, 
                    email = :email, 
                    phone = :phone 
                WHERE participant_id = :id";

        $query = $bdd->prepare($sql);
        $query->bindValue(':name', $name);
        $query->bindValue(':email', $email);
        $query->bindValue(':phone', $phone);
        $query->bindValue(':id', $id);

        $query->execute();

        header("Location: participant_details.php?participant_id=" . $id);
        exit();
    } catch (PDOException $e) {
        $error = "Erreur : Cet email est déjà utilisé par un autre participant.";
    }
}

$template = "update_participant";
require_once __DIR__ . '/../phtml/layout.phtml';
?>