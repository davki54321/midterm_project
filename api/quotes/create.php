<?php

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate quote object
$quote_obj = new Quote($db);

// Get raw quote data
// true argument returns an associative array
$data = json_decode(file_get_contents("php://input"), true);

// This if-statement checks that all of the required keys and values for a new quote are given.
// If the author_id or category_id are not in their respective tables, the quote->create() 
// method will catch the error.
if(!array_key_exists('quote', $data) ||
    !array_key_exists('author_id', $data) ||
    !array_key_exists('category_id', $data) ||
    !$data['quote'] ||
    !$data['author_id'] ||
    !$data['category_id']) 
{
    echo json_encode(
        array('message' => 'Missing Required Parameters')
    );
}
else {
    // Prepares information to create a new quote
    $quote_obj->quote = $data['quote'];
    $quote_obj->author_id = $data['author_id'];
    $quote_obj->category_id = $data['category_id'];

    // Creates quote
    if ($quote_obj->create()) {
        echo json_encode(
            array('message' => 'Quote Created')
        );
    } 
}