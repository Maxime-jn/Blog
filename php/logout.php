<?php
session_start(); // Démarre la session

// Vérifier si un utilisateur est connecté
if (isset($_SESSION['token'])) {
    // Effacer le token de la base de données
    require_once "database.php";
    $sql = "UPDATE user SET token = NULL WHERE token = :token";
    $param = [':token' => $_SESSION['token']];
    dbRun($sql, $param);

    // Détruire la session
    session_unset();
    session_destroy();

    // Rediriger vers la page de connexion
    header("Location: connexion.html");
    exit();
} else {
    echo "Vous n'êtes pas connecté.";
}
?>
