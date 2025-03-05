<?php
session_start(); // Démarre la session

require_once "database.php"; // Connexion à la base de données

// Vérification si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Vérifier si les champs sont remplis
    if (empty($username) || empty($password)) {
        echo "Veuillez remplir tous les champs.";
        exit();
    }

    // Requête pour récupérer les données de l'utilisateur dans la base de données
    $sql = "SELECT * FROM user WHERE name = :username";
    $param = [':username' => $username];

    // Exécution de la requête
    $stmt = dbRun($sql, $param);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Si l'utilisateur existe et que le mot de passe est correct

        // Génération du token (par exemple, basé sur l'ID de l'utilisateur et un secret)
        $token = bin2hex(random_bytes(32)); // Générer un token aléatoire
        $tokenExpiration = time() + 3600; // Expiration du token dans 1 heure

        // Enregistrement du token dans la base de données
        $updateSql = "UPDATE user SET token = :token WHERE iduser = :iduser";
        $updateParam = [
            ':token' => $token,
            ':iduser' => $user['iduser']
        ];
        dbRun($updateSql, $updateParam);

        // Stocker le token dans la session ou le renvoyer à l'utilisateur (optionnel)
        $_SESSION['token'] = $token;

        // Réponse avec le token (si vous utilisez une API)
        echo json_encode(["success" => true, "token" => $token]);

        // Rediriger vers la page protégée (index.php ou autre)
        header("Location: index.html");
        exit();
    } else {
        // Si les informations d'identification sont incorrectes
        echo "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>
