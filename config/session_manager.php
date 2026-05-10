<?php
// Fichier de gestion des sessions - à inclure dans chaque page
require_once 'database.php';

// Démarrer les sessions automatiquement
$database = new Database();
$db = $database->getConnection();

// Maintenant $_SESSION est disponible dans toutes les pages qui incluent ce fichier
?>
