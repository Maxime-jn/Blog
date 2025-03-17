<?php

require_once "database.php"; 

function loginUser($name, $password)
{
    $sql = "SELECT * FROM user WHERE name = :name";
    $params = [':name' => $name];
    $stmt = dbRun($sql, $params);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Génération du token
        $token = bin2hex(random_bytes(32));
        $tokenExpiration = time() + 3600; // Expiration dans 1 heure

        // Mise à jour du token
        $updateSql = "UPDATE user SET token = :token, token_expiration = :expiration WHERE iduser = :iduser";
        $updateParam = [
            ':token' => $token,
            ':expiration' => $tokenExpiration,
            ':iduser' => $user['iduser']
        ];
        dbRun($updateSql, $updateParam);

        return ["success" => true, "token" => $token, "message" => "Connexion réussie"];
    }

    return ["success" => false, "message" => "Nom ou mot de passe incorrect"];
}

function checkAuth($token)
{
    $sql = "SELECT * FROM user WHERE token = :token";
    $params = [':token' => $token];
    $stmt = dbRun($sql, $params);
    $user = $stmt->fetch();

    if ($user && $user['token_expiration'] > time()) {
        return $user;
    }

    return false;
}
?>
