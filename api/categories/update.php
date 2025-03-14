<?php

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
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

// This if-statement checks that all of the required keys and values for a updating a category are given.
// If the id or category do not exists, the category->update() method will catch the error.
if(!array_key_exists('id', $data) ||
    !array_key_exists('category', $data) ||
    !$data['id'] ||
    !$data['category']) 
{
    echo json_encode(
        array('message' => 'Missing Required Parameters')
    );
}
else {
    // Sets the category objets attributes
    $category_obj->id = $data['id'];
    $category_obj->category = $data['category'];
    
    // Update category
    if ($category_obj->update()) {
        echo json_encode(
            array(
                'id' => $category_obj->id,
                'category' => $category_obj->category
            )
        );
    } 
}





