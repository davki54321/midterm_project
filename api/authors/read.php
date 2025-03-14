<?php

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Author.php';

// Instantiate DB & connect
// Database object is imported from Database.php
$database = new Database();
$db = $database->connect();

// Instantiate author object
$author_obj = new Author($db);

// author query
$result = $author_obj->read();

// Get row count
$num = $result->rowCount();

// Check if any authors
if ($num > 0) {

    // authors array
    $author_arr = array();       // initializes blank array

    // If the author id is given in the URL, it is stored in the author object.
    if (isset($_GET['id'])) {
        $author_obj->id = $_GET['id'];
    }

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);        // 'extract' extracts the data and stores them in the variables from author

        // If the author has a matching author_id and category_id, it is added to the array.
        if ((int)$author_obj->id === $id) {
            add_author_to_array($id, $author, $author, $category, $author_arr);
        }
        else if (!$author_obj->id) {
            add_author_to_array($id, $author, $author_arr);
        }
    }

    if (!$author_arr) {
        $author_arr = array(
            'message' => 'author_id Not Found'
        );
    }

    // Turn to JSON & output
    // is currently PHP array -- json_encode  formats the data in JSON
    echo json_encode($author_arr);
} 
else {
    // No authors
    echo json_encode(
        array('message' => 'author_id Not Found')
    );
}

function add_author_to_array(&$id, &$author, &$author_arr) {
    $author_item = array(
        'id' => $id,
        'author' => html_entity_decode($author),
    );

    // this function will loop through author_item and push each element on to the array
    array_push($author_arr, $author_item);
}