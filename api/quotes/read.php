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
$quote_obj = new Quote($db);

// Quote query
$result = $quote_obj->read();

// Get row count
$num = $result->rowCount();

// Check if any quotes
if ($num > 0) {

    // Quotes array
    $quotes_arr = array();       // initializes blank array

    // If the author_id is given in the URL, it is stored in the quote object.
    if (isset($_GET['author_id'])) {
        $quote_obj->author_id = $_GET['author_id'];
    }

    // If the category_id is given in the URL, it is stored in the quote object.
    if (isset($_GET['category_id'])) {
        $quote_obj->category_id = $_GET['category_id'];
    }

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);        // 'extract' extracts the data and stores them in the variables from Quote

        // If the quote has a matching author_id and category_id, it is added to the array.
        if ((int)$quote_obj->author_id === $author_id && 
            (int)$quote_obj->category_id === $category_id) 
        {
            add_quote_to_array($id, $quote, $author, $category, $quotes_arr);
        }

        // If the quote has a matching author_id, it is added to the array.
        else if ((int)$quote_obj->author_id === $author_id &&
            !$quote_obj->category_id) 
        {
            add_quote_to_array($id, $quote, $author, $category, $quotes_arr);
        }

        // If the quote has a matching category_id, it is added to the array.
        else if ((int)$quote_obj->category_id === $category_id &&
            !$quote_obj->author_id) 
        {
            add_quote_to_array($id, $quote, $author, $category, $quotes_arr);
        }

        // If there no author_id or category_id were given, the quote is added to the array.
        else if (!$quote_obj->author_id && 
            !$quote_obj->category_id) 
        {
            add_quote_to_array($id, $quote, $author, $category, $quotes_arr);
        }
    }

    if (!$quotes_arr) {
        $quotes_arr = array(
            'message' => 'No Quotes Found'
        );
    }

    // Turn to JSON & output
    // is currently PHP array -- json_encode  formats the data in JSON
    echo json_encode($quotes_arr);
} 
else {
    // No quotes
    echo json_encode(
        array('message' => 'No Quotes Found')
    );
}

function add_quote_to_array(&$id, &$quote, &$author, &$category, &$quotes_arr) {
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