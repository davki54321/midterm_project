<?php

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate blog quote object
$quote_obj = new Quote($db);

// Get ID from URL
$quote_obj->id = isset($_GET['id']) ? $_GET['id'] : die();

// Get quote
$quote_obj->read_single();

// This variable will store the PHP array to be JSON encoded
$array_to_output = '';

// If there is no quote, the quote attribute will be null.
if (!$quote_obj->quote) {
    // Creates array
    $array_to_output = array(
        'message' => 'No Quotes Found'
    );
}
else {
    // Creates array
    $array_to_output = array(
        'id' => $quote_obj->id,
        'quote' => $quote_obj->quote,
        'author' => $quote_obj->author_id,
        'category' => $quote_obj->category_id,
    );
}

// Make JSON
print_r(json_encode($array_to_output));
