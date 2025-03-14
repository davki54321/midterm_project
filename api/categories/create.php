<?php

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Category.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate category object
$category_obj = new Category($db);

// Get raw category data
// true argument returns an associative array
$data = json_decode(file_get_contents("php://input"), true);

// This if-statement checks that all of the required keys and values for a new category are given.
// If the category_id or category_id are not in their respective tables, the category->create() 
// method will catch the error.
if(!array_key_exists('category', $data) || !$data['category']) {
    echo json_encode(
        array('message' => 'Missing Required Parameters')
    );
}
else {
    // Prepares information to create a new category
    $category_obj->category = $data['category'];

    // Creates category
    if ($category_obj->create()) {
        echo json_encode(
            array('message' => 'Category Created')
        );
    } 
}