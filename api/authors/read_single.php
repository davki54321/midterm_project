<?php

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Author.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate blog author object
$author_obj = new author($db);

// Get ID from URL
$author_obj->id = isset($_GET['id']) ? $_GET['id'] : die();

// Get author
$author_obj->read_single();

// This variable will store the PHP array to be JSON encoded
$array_to_output = '';

// If there is no author, the author attribute will be null.
if (!$author_obj->author) {
    // Creates array
    $array_to_output = array(
        'message' => 'author_id Not Found'
    );
}
else {
    // Creates array
    $array_to_output = array(
        'id' => $author_obj->id,
        'author' => $author_obj->author,
    );
}

// Make JSON
print_r(json_encode($array_to_output));
