<?php

require_once "database.php";

// Récupérer le token depuis l'en-tête Authorization
$headers = getallheaders();
if (!isset($headers['Authorization'])) {
    echo json_encode(["error" => "Token manquant."]);
    exit();
}

$token = str_replace('Bearer ', '', $headers['Authorization']);

// Vérifier le token dans la base de données
$sql = "SELECT * FROM user WHERE token = :token";
$param = [':token' => $token];
$stmt = dbRun($sql, $param);
$user = $stmt->fetch();

if ($user) {
    echo json_encode(["success" => true, "message" => "Accès autorisé", "user" => $user['name']]);
} else {
    echo json_encode(["error" => "Token invalide."]);
}
?>