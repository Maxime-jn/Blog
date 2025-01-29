<?php
require_once "constants.php";
require_once "function.php";

$fullPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$scriptPath = $_SERVER['SCRIPT_NAME'];
$path = substr($fullPath, strlen($scriptPath) + 2);


echo $path;


$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if ($path == '/get/posts') {
            getPosts();
        } elseif ($path == '/get/multimedia') {
            getMultimedia();
        } else {
            http_response_code(HTTP_STATUS_NOT_FOUND);
            echo "Not Found";
        }
        break;

    case 'POST':
        if ($path == '/post/create') {
            createPost();
        } elseif ($path == '/post/multimedia') {
            createMultimedia();
        } else {
            http_response_code(HTTP_STATUS_NOT_FOUND);
            echo "Not Found";
        }
        break;

    case 'PUT':
        if ($path == '/posts') {
            updatePost();
        } else {
            http_response_code(HTTP_STATUS_NOT_FOUND);
            echo "Not Found";
        }
        break;

    case 'DELETE':
        if ($path == '/posts') {
            deletePost();
        } elseif ($path == '/multimedia') {
            deleteMultimedia();
        } else {
            http_response_code(HTTP_STATUS_NOT_FOUND);
            echo "Not Found";
        }
        break;

    default:
        http_response_code(HTTP_STATUS_IM_A_TEAPOT); // HTTP_STATUS_IM_A_TEAPOT
        break;
}