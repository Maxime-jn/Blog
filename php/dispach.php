<?php
require_once "constants.php";


$fullPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$scriptPath = $_SERVER['SCRIPT_NAME'];
$path = str_replace($scriptPath, '', $fullPath);

echo $fullPath;


$method = $_SERVER['REQUEST_METHOD'];


switch ($method) {
    case 'GET':
        HandleGET($path);
        break;

    case 'POST':
        HandlePOST($path);
        break;

    case 'PUT':
        HandlePUT($path);
        break;

    case 'DELETE':
        HandleDELETE($path);
        break;

    default:
        http_response_code(HTTP_STATUS_IM_A_TEAPOT);
        break;
}

function HandlePOST($path)
{
    switch ($path) {
        case '/media/create/':
            createMultimedia();
            break;
        case '/post/create/':
            createPost();
            break;
        default:
            echo json_encode(["error" => "Method not allowed"]);
            break;
    }
}
function HandleGET($path)
{
    switch ($path) {
        case '/posts/':
            getPosts();
            break;
        case '/media/':
            getMultimedia();
            break;
        default:
            http_response_code(HTTP_STATUS_NOT_FOUND);
            echo 'Not Found';
            break;
    }
}

function HandlePUT($path)
{
    switch ($path) {
        case '/post/update/':
            updatePost();
            break;
        default:
            http_response_code(HTTP_STATUS_NOT_FOUND);
            echo 'Not Found';
            break;
    }
}

function HandleDELETE($path)
{
    switch ($path) {
        case '/posts/delete/':
            deletePost();
            break;

        case "/media/delete/": 
            deleteMultimedia();
            break;
        default:
            http_response_code(HTTP_STATUS_NOT_FOUND);
            echo 'Not Found';
            break;
    }
}