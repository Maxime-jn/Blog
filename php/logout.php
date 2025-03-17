<?php

require_once "database.php"; 

// Récupérer le token depuis l'en-tête Authorization
$headers = getallheaders();
if (!isset($headers['Authorization'])) {
    echo json_encode(["error" => "Token manquant."]);
    exit();
}

$token = str_replace('Bearer ', '', $headers['Authorization']);

// Supprimer le token de la base de données
$sql = "UPDATE user SET token = NULL, token_expiration = NULL WHERE token = :token";
$param = [':token' => $token];
dbRun($sql, $param);

echo json_encode(["success" => true, "message" => "Déconnexion réussie."]);
?>
