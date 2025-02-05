<?php
session_start();
require_once "database.php";

function loginUser($name, $password)
{
    $sql = "SELECT * FROM user WHERE name = :name";
    $params = [':name' => $name];
    $stmt = dbRun($sql, $params);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['iduser'];
        $_SESSION['token'] = $user['token'];
        return ["success" => true, "token" => $user['token'], "message" => "Connexion réussie"];
    }
    return ["success" => false, "message" => "Nom ou mot de passe incorrect"];
}

function checkAuth()
{
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['token'])) {
        return false;
    }
    $sql = "SELECT * FROM user WHERE iduser = :id AND token = :token";
    $params = [
        ':id' => $_SESSION['user_id'],
        ':token' => $_SESSION['token']
    ];
    $stmt = dbRun($sql, $params);
    return $stmt->fetch() !== false;
}
?>
