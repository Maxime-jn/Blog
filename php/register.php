<?php
require_once "database.php"; // Connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    // Vérifier que les champs ne sont pas vides
    if (empty($username) || empty($password)) {
        echo json_encode(["error" => "Tous les champs sont requis."]);
        exit();
    }

    // Vérifier si l'utilisateur existe déjà
    $sql = "SELECT * FROM user WHERE name = :username";
    $stmt = dbRun($sql, [":username" => $username]);
    $existingUser = $stmt->fetch();

    if ($existingUser) {
        echo json_encode(["error" => "Ce nom d'utilisateur est déjà pris."]);
        exit();
    }

    // Hacher le mot de passe
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insérer l'utilisateur dans la base de données
    $insertSql = "INSERT INTO user (name, password) VALUES (:username, :password)";
    $insertParams = [
        ":username" => $username,
        ":password" => $hashedPassword
    ];
    dbRun($insertSql, $insertParams);

    echo json_encode(["success" => true]);
}
?>
