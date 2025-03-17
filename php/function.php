<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once "database.php";
require_once "constants.php";

function getRequestData()
{
    $data = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'PUT') {
        $data = $_POST;
        if (empty($data)) {
            $data = json_decode(file_get_contents("php://input"), true);
        }
    }
    return $data;
}

function getPosts()
{
    $sql = "SELECT Titre,commentaire, multimedia.path_ficher, posts.iduser, posts.idPosts
            FROM posts, multimedia, user
            WHERE posts.idPosts = multimedia.idPosts 
            AND user.iduser = posts.iduser
            ORDER BY idPosts DESC";

    $statement = dbrun($sql);
    $datas = $statement->fetchAll();
    return json_encode($datas);
}

function getPostById()
{
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    if (!$id) {
        echo json_encode(["error" => "Post ID required"]);
        return;
    }

    $sql = "SELECT Titre, commentaire, iduser FROM posts WHERE idPosts = :id";
    $param = [':id' => $id];
    $statement = dbRun($sql, $param);
    $post = $statement->fetch();

    if (!$post) {
        echo json_encode(["error" => "Post not found"]);
        return;
    }

    $sql = "SELECT path_ficher, 
                   CASE 
                       WHEN path_ficher LIKE '%.jpg' OR path_ficher LIKE '%.png' THEN 'image'
                       WHEN path_ficher LIKE '%.mp4' THEN 'video'
                       WHEN path_ficher LIKE '%.mp3' OR path_ficher LIKE '%.wav' THEN 'audio'
                       ELSE 'unknown'
                   END AS type
            FROM multimedia WHERE idPosts = :id";
    $statement = dbRun($sql, $param);
    $multimedia = $statement->fetchAll();

    $post['multimedia'] = $multimedia;
    echo json_encode($post);
}


function createPost()
{
    $data = getRequestData();
    beginTransaction();
    try {
        $sql = "INSERT INTO posts (Titre, commentaire, iduser) VALUES (:Titre, :commentaire, :iduser)";
        $param = [
            ':Titre' => $data['Titre'],
            ':commentaire' => $data['commentaire'],
            ':iduser' => $data['iduser']
        ];
        dbRun($sql, $param);
        $postId = lastInsertId();

        if (isset($_FILES['fichier'])) {
            foreach ($_FILES['fichier']['tmp_name'] as $key => $tmp_name) {
                $fileType = mime_content_type($tmp_name);

                if (strpos($fileType, 'image') !== false) {
                    $filePath = "./multimedia/image/" . basename($_FILES['fichier']['name'][$key]);
                } elseif (strpos($fileType, 'video') !== false) {
                    $filePath = "./multimedia/video/" . basename($_FILES['fichier']['name'][$key]);
                } elseif (strpos($fileType, 'sound') !== false) {
                    $filePath = "./multimedia/sound/" . basename($_FILES['fichier']['name'][$key]);
                } else {
                    throw new Exception("Unsupported file type");
                }
                move_uploaded_file($tmp_name, $filePath);

                $sql = "INSERT INTO multimedia (path_ficher, nom, idPosts) VALUES (:path, :nom, :idPosts)";
                $param = [
                    ':path' => $filePath,
                    ':nom' => $_FILES['fichier']['name'][$key],
                    ':idPosts' => $postId
                ];
                dbRun($sql, $param);
            }
        }
        commit();
    } catch (Exception $e) {
        rollBack();
        echo json_encode(["error" => $e->getMessage()]);
    }
}



function updatePost()
{
    $data = getRequestData();
    $id = $data['idPosts'];
    if (!$id) {
        echo json_encode(["error" => "Post ID required"]);
        return;
    }
    $sql = "UPDATE posts SET Titre = :Titre, commentaire = :commentaire WHERE idPosts = :id";
    $param = [
        ':Titre' => $data['Titre'],
        ':commentaire' => $data['commentaire'],
        ':id' => $id
    ];
    dbrun($sql, $param);
}

function deletePost()
{
    $data = getRequestData();
    $id = $data['idPosts'];
    if (!$id) {
        echo json_encode(["error" => "Post ID required"]);
        return;
    }
    beginTransaction();
    try {
        $sql = "DELETE FROM multimedia WHERE idPosts = :id";
        $param = [':id' => $id];
        dbrun($sql, $param);

        $sql = "DELETE FROM posts WHERE idPosts = :id";
        $param = [':id' => $id];
        dbrun($sql, $param);

        commit();
    } catch (Exception $e) {
        rollBack();
        echo json_encode(["error" => $e->getMessage()]);
    }
}

function getMultimedia()
{
    $data = getRequestData();
    $idPost = $data['idPosts'];
    if (!$idPost) {
        echo json_encode(["error" => "Post ID required"]);
        return;
    }
    $sql = "SELECT * FROM multimedia WHERE idPosts = :id";
    $param = [':id' => $idPost];

    $statement = dbRun($sql, $param);
    return $statement->fetchAll();
}

function createMultimedia()
{
    $data = getRequestData();
    $sql = "INSERT INTO multimedia (path_ficher, nom, idPosts) VALUES (:path, :nom, :idPosts)";
    $param = [
        ':path' => $data['path_ficher'],
        ':nom' => $data['nom'],
        ':idPosts' => $data['idPosts']
    ];

    dbrun($sql, $param);
}

function deleteMultimedia()
{
    $data = getRequestData();
    $id = $data['idmultimedia'];
    if (!$id) {
        echo json_encode(["error" => "Multimedia ID required"]);
        return;
    }
    $sql = "DELETE FROM multimedia WHERE idmultimedia = :id";

    $param = [':id' => $id];

    dbrun($sql, $param);
}


function getUserByName()
{
    $data = getRequestData();
    $name = $data['name'];
    $sql = "SELECT * FROM users WHERE name = :name";
    $param = [':name' => $name];

    $statement = dbRun($sql, $param);
    return $statement->fetch();
}








