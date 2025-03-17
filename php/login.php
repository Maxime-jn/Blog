<?php

require_once "database.php"; // Connexion à la base de données

// Vérification si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Vérifier si les champs sont remplis
    if (empty($username) || empty($password)) {
        echo json_encode(["error" => "Veuillez remplir tous les champs."]);
        exit();
    }

    // Requête pour récupérer l'utilisateur
    $sql = "SELECT * FROM user WHERE name = :username";
    $param = [':username' => $username];
    $stmt = dbRun($sql, $param);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Génération du token
        $token = bin2hex(random_bytes(32));
        $tokenExpiration = time() + 3600; // Expiration dans 1 heure

        // Mise à jour du token dans la base de données
        $updateSql = "UPDATE user SET token = :token WHERE iduser = :iduser";
        $updateParam = [
            ':token' => $token,
            ':iduser' => $user['iduser']
        ];
        dbRun($updateSql, $updateParam);

        // Réponse avec le token
        echo json_encode(["success" => true, "token" => $token]);
    } else {
        echo json_encode(["error" => "Nom d'utilisateur ou mot de passe incorrect."]);
    }
}
?>
