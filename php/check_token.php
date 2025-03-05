<?php
session_start(); // Démarre la session

require_once "database.php"; // Connexion à la base de données

// Vérification du token dans la session
if (!isset($_SESSION['token'])) {
    echo json_encode(["error" => "Token manquant. Veuillez vous connecter."]);
    exit();
}

$token = $_SESSION['token'];

// Vérifier si le token existe dans la base de données
$sql = "SELECT * FROM user WHERE token = :token";
$param = [':token' => $token];
$stmt = dbRun($sql, $param);
$user = $stmt->fetch();

if ($user) {
    // Token trouvé, vérifier si le token n'est pas expiré (si vous stockez la date d'expiration)
    $tokenExpiration = time() + 3600; // Expiration après 1 heure
    if ($user['token_expiration'] > time()) {
        // Le token est valide, afficher la page protégée
        echo "Accès autorisé. Bienvenue sur votre tableau de bord, " . $user['name'];
    } else {
        // Token expiré
        echo json_encode(["error" => "Token expiré. Veuillez vous reconnecter."]);
    }
} else {
    // Token invalide
    echo json_encode(["error" => "Token invalide. Veuillez vous reconnecter."]);
}
?>
