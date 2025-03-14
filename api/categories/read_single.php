<?php

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Category.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate blog category object
$category_obj = new Category($db);

// Get ID from URL
$category_obj->id = isset($_GET['id']) ? $_GET['id'] : die();

// Get category
$category_obj->read_single();

// This variable will store the PHP array to be JSON encoded
$array_to_output = '';

// If there is no category, the category attribute will be null.
if (!$category_obj->category) {
    // Creates array
    $array_to_output = array(
        'message' => 'category_id Not Found'
    );
}
else {
    // Creates array
    $array_to_output = array(
        'id' => $category_obj->id,
        'category' => $category_obj->category,
    );
}

// Make JSON
print_r(json_encode($array_to_output));
