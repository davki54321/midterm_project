<?php

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Category.php';

// Instantiate DB & connect
// Database object is imported from Database.php
$database = new Database();
$db = $database->connect();

// Instantiate category object
$category_obj = new Category($db);

// category query
$result = $category_obj->read();

// Get row count
$num = $result->rowCount();

// Check if any categorys
if ($num > 0) {

    // categorys array
    $category_arr = array();       // initializes blank array

    // If the category id is given in the URL, it is stored in the category object.
    if (isset($_GET['id'])) {
        $category_obj->id = $_GET['id'];
    }

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);        // 'extract' extracts the data and stores them in the variables from category

        // If the category has a matching category_id and category_id, it is added to the array.
        if ((int)$category_obj->id === $id) {
            add_category_to_array($id, $category, $category, $category, $category_arr);
        }
        else if (!$category_obj->id) {
            add_category_to_array($id, $category, $category_arr);
        }
    }

    if (!$category_arr) {
        $category_arr = array(
            'message' => 'category_id Not Found'
        );
    }

    // Turn to JSON & output
    // is currently PHP array -- json_encode  formats the data in JSON
    echo json_encode($category_arr);
} 
else {
    // No categorys
    echo json_encode(
        array('message' => 'category_id Not Found')
    );
}

function add_category_to_array(&$id, &$category, &$category_arr) {
    $category_item = array(
        'id' => $id,
        'category' => html_entity_decode($category),
    );

    // this function will loop through category_item and push each element on to the array
    array_push($category_arr, $category_item);
}