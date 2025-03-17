<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once "database.php";
require_once "constants.php";

// function getRequestData()
// {
//     $data = [];
//     if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'PUT') {
//         $data = $_POST;
//         if (empty($data)) {
//             $data = json_decode(file_get_contents("php://input"), true);
//         }
//     }
//     return $data;
// }

function getRequestData()
{
    $data = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'PUT' || $_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $data = $_POST;
        if (empty($data)) {
            $data = json_decode(file_get_contents("php://input"), true);
        }
    }
    return $data;
}


// function getPosts()
// {
//     $sql = "SELECT posts.Titre, posts.commentaire, (     
//         SELECT multimedia.path_ficher     
//         FROM multimedia    
//          WHERE multimedia.idPosts = posts.idPosts     
//          ORDER BY multimedia.idmultimedia ASC     
//          LIMIT 1 ) 
//          AS path_ficher, posts.iduser, posts.idPosts 
//     FROM posts 
//     JOIN user ON user.iduser = posts.iduser 
//     ORDER BY posts.idPosts DESC;
// ";

//     $statement = dbrun($sql);
//     $datas = $statement->fetchAll();
//     return json_encode($datas);
// }

function getPosts()
{
    $sql = "SELECT posts.Titre, posts.commentaire, (     
        SELECT multimedia.path_ficher     
        FROM multimedia    
        WHERE multimedia.idPosts = posts.idPosts     
        ORDER BY multimedia.idmultimedia ASC     
        LIMIT 1 ) 
        AS path_ficher, posts.iduser, posts.idPosts 
    FROM posts 
    JOIN user ON user.iduser = posts.iduser 
    ORDER BY posts.idPosts DESC";

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
                if (empty($tmp_name)) {
                    throw new Exception("File upload error: No file uploaded");
                }

                $fileSize = $_FILES['fichier']['size'][$key];
                $maxFileSize = 5 * 1024 * 1024; // 5MB
                if ($fileSize > $maxFileSize) {
                    throw new Exception("File size exceeds the maximum limit of 5MB");
                }

                $mimeType = mime_content_type($tmp_name);

                // Vérification spécifique pour les images
                if (strpos($mimeType, 'image') !== false) {
                    $imageInfo = getimagesize($tmp_name);
                    if ($imageInfo === false) {
                        throw new Exception("Invalid image file");
                    }
                    $mimeType = $imageInfo['mime'];
                }

               
                // Déterminer le dossier de destination
                if (in_array($mimeType, ['image/jpeg', 'image/png', 'image/gif'])) {
                    $directory = "multimedia/image/";
                } elseif (in_array($mimeType, ['video/mp4', 'video/quicktime'])) {
                    $directory = "multimedia/video/";
                } elseif (in_array($mimeType, ['audio/mpeg', 'audio/wav', 'audio/ogg'])) {
                    $directory = "multimedia/sound/";
                } else {
                    throw new Exception("Unsupported file type: $mimeType");
                }

                // Créer le dossier si nécessaire
                if (!is_dir("../". $directory)) {
                    mkdir("../". $directory, 0777, true);
                }

                $filePathForPHP = "../" . $directory . basename($_FILES['fichier']['name'][$key]);
                if (!move_uploaded_file($tmp_name, $filePathForPHP)) {
                    throw new Exception("Failed to move uploaded file");
                }


                $filePathForHtml = "./" . $directory . basename($_FILES['fichier']['name'][$key]);

                $sql = "INSERT INTO multimedia (path_ficher, nom, idPosts) VALUES (:path, :nom, :idPosts)";
                $param = [
                    ':path' => $filePathForHtml,
                    ':nom' => $_FILES['fichier']['name'][$key],
                    ':idPosts' => $postId
                ];
                dbRun($sql, $param);
            }
        }
        commit();
        echo json_encode(["success" => true, "message" => "Post created successfully"]);
    } catch (Exception $e) {
        rollBack();
        echo json_encode(["error" => $e->getMessage()]);
    }
}


// function updatePost()
// {
//     $data = getRequestData();
//     $id = $data['idPosts'];
//     if (!$id) {
//         echo json_encode(["error" => "Post ID required"]);
//         return;
//     }
//     $sql = "UPDATE posts SET Titre = :Titre, commentaire = :commentaire WHERE idPosts = :id";
//     $param = [
//         ':Titre' => $data['Titre'],
//         ':commentaire' => $data['commentaire'],
//         ':id' => $id
//     ];
//     dbrun($sql, $param);
// }

// function deletePost()
// {
//     $data = getRequestData();
//     $id = $data['idPosts'];
//     if (!$id) {
//         echo json_encode(["error" => "Post ID required"]);
//         return;
//     }
//     beginTransaction();
//     try {
//         $sql = "DELETE FROM multimedia WHERE idPosts = :id";
//         $param = [':id' => $id];
//         dbrun($sql, $param);

//         $sql = "DELETE FROM posts WHERE idPosts = :id";
//         $param = [':id' => $id];
//         dbrun($sql, $param);

//         commit();
//     } catch (Exception $e) {
//         rollBack();
//         echo json_encode(["error" => $e->getMessage()]);
//     }
// }


function checkToken()
{
    $headers = getallheaders();
    if (!isset($headers['Authorization'])) {
        echo json_encode(["success" => false, "error" => "Token manquant"]);
        return;
    }

    $token = str_replace('Bearer ', '', $headers['Authorization']);

    $sql = "SELECT iduser FROM user WHERE token = :token";
    $param = [':token' => $token];
    $stmt = dbRun($sql, $param);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo json_encode(["success" => true, "userId" => $user['iduser']]);
    } else {
        echo json_encode(["success" => false, "error" => "Token invalide"]);
    }
}

function getUserByToken($token)
{
    $sql = "SELECT iduser FROM user WHERE token = :token";
    $param = [':token' => $token];
    $stmt = dbRun($sql, $param);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


// function deletePost()
// {
//     $data = getRequestData();
//     $id = $data['idPosts'];
//     if (!$id) {
//         echo json_encode(["error" => "Post ID required"]);
//         return;
//     }

//     // Vérifier que l'utilisateur connecté est bien celui qui a créé le post
//     $sql = "SELECT iduser FROM posts WHERE idPosts = :id";
//     $param = [':id' => $id];
//     $statement = dbRun($sql, $param);
//     $post = $statement->fetch();

//     if (!$post || $post['iduser'] != $_SESSION['userId']) {
//         echo json_encode(["error" => "You don't have permission to delete this post"]);
//         return;
//     }

//     beginTransaction();
//     try {
//         $sql = "DELETE FROM multimedia WHERE idPosts = :id";
//         $param = [':id' => $id];
//         dbrun($sql, $param);

//         $sql = "DELETE FROM posts WHERE idPosts = :id";
//         $param = [':id' => $id];
//         dbrun($sql, $param);

//         commit();
//         echo json_encode(["success" => true]);
//     } catch (Exception $e) {
//         rollBack();
//         echo json_encode(["error" => $e->getMessage()]);
//     }
// }



// function updatePost()
// {
//     $data = getRequestData();
//     $id = $data['idPosts'];
//     if (!$id) {
//         echo json_encode(["error" => "Post ID required"]);
//         return;
//     }

//     // Vérifier que l'utilisateur connecté est bien celui qui a créé le post
//     $sql = "SELECT iduser FROM posts WHERE idPosts = :id";
//     $param = [':id' => $id];
//     $statement = dbRun($sql, $param);
//     $post = $statement->fetch();

//     if (!$post || $post['iduser'] != $_SESSION['userId']) {
//         echo json_encode(["error" => "You don't have permission to update this post"]);
//         return;
//     }

//     $sql = "UPDATE posts SET Titre = :Titre, commentaire = :commentaire WHERE idPosts = :id";
//     $param = [
//         ':Titre' => $data['Titre'],
//         ':commentaire' => $data['commentaire'],
//         ':id' => $id
//     ];
//     dbrun($sql, $param);
// }

function updatePost()
{
    $data = getRequestData();
    error_log("updatePost() - Données reçues: " . print_r($data, true));  // Debug
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
    echo json_encode(["success" => "Post mis à jour"]);
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
        echo json_encode(["success" => true]);
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








