<?php
// Database connection settings
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_NAME', 'gestion_evenements');
define('DB_PASS', '');

try {
    // Connect to MySQL database using PDO
    $bdd = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
    // Set default fetch mode to associative array
    $bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    // Set error mode to exception for better error handling
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    // Stop execution and display error if connection fails
    die('Erreur de connexion: ' . $e->getMessage());
}
?>