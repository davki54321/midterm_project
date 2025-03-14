<?php

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Instantiate DB & connect
// Database object is imported from Database.php
$database = new Database();
$db = $database->connect();

// Instantiate quote object
// Quote is imported from Quote.php
$current_quote = new Quote($db);

// Quote query
$result = $current_quote->read();
// Get row count
$num = $result->rowCount();

// Check if any quotes
if ($num > 0) {

    // Quotes array
    $quotes_arr = array();       // initializes blank array

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);        // 'extract' extracts the data and stores them in the variables from Quote

        $quote_item = array(
            'id' => $id,
            'quote' => html_entity_decode($quote),

            // These array elements will send "author" attribute from table "authors" and "category" attribute
            // the table "categories" instead of "author_id" and "category_id" from tables "quotes" 
            'author' => $author,
            'category' => $category,
        );

        // this function will loop through quote_item and push each element on to the array
        array_push($quotes_arr, $quote_item);

    }

    // Turn to JSON & output
    // is currently PHP array -- json_encode  formats the data in JSON
    echo json_encode($quotes_arr);
} else {
    // No quotes
    echo json_encode(
        array('message' => 'No Quotes Found')
    );
}
