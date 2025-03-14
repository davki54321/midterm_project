<?php

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
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

// This if-statement checks that all of the required keys and values for a updating a quote are given.
// If the id, author_id, or category_id do not exists, the quote->update() method will catch the error.
if(!array_key_exists('id', $data) ||
    !array_key_exists('quote', $data) ||
    !array_key_exists('author_id', $data) ||
    !array_key_exists('category_id', $data) ||
    !$data['id'] ||
    !$data['quote'] ||
    !$data['author_id'] ||
    !$data['category_id']) 
{
    echo json_encode(
        array('message' => 'Missing Required Parameters')
    );
}
else {
    // Sets the quote objets attributes
    $quote_obj->id = $data['id'];
    $quote_obj->quote = $data['quote'];
    $quote_obj->author_id = $data['author_id'];
    $quote_obj->category_id = $data['category_id'];
    
    // Update quote
    if ($quote_obj->update()) {
        echo json_encode(
            array(
                'id' => $quote_obj->id,
                'quote' => $quote_obj->quote,
                'author_id' => $quote_obj->author_id,
                'category_id' => $quote_obj->category_id
            )
        );
    } 
}





