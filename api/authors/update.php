<?php

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Author.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate author object
$author_obj = new Author($db);

// Get raw author data
// true argument returns an associative array
$data = json_decode(file_get_contents("php://input"), true);

// This if-statement checks that all of the required keys and values for a updating a author are given.
// If the id or author do not exists, the author->update() method will catch the error.
if(!array_key_exists('id', $data) ||
    !array_key_exists('author', $data) ||
    !$data['id'] ||
    !$data['author']) 
{
    echo json_encode(
        array('message' => 'Missing Required Parameters')
    );
}
else {
    // Sets the author objets attributes
    $author_obj->id = $data['id'];
    $author_obj->author = $data['author'];
    
    // Update author
    if ($author_obj->update()) {
        echo json_encode(
            array('message' => 'Author Updated')
        );
    } 
}





