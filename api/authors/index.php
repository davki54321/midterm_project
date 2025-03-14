<?php

    // These headers are required for testing
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'OPTIONS') {
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
        exit();
    }

    // If a GET request is sent, either read.php or read_single.php will be called
    if ($method === 'GET') {
        if (isset($_GET['id'])) 
        {
            include './read_single.php';
        }
        else {
            include './read.php';
        }
    } 
    else if ($method === 'POST') {
        include './create.php';
    } 
    else if ($method === 'PUT') {
        include './update.php';
    } 
    else if ($method === 'DELETE') {
        include './delete.php';
    }

?>
