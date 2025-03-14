<?php

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Category.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate blog post object
$category_obj = new Category($db);

// Get raw posted data
// true argument returns an associative array
$data = json_decode(file_get_contents("php://input"), true);

// This if-statement checks that the required key and value for a deleting a category are given.
// If the category id is not in the table the category->delete() method will catch the error.
if(!array_key_exists('id', $data) ||
    !$data['id']) 
{
    echo json_encode(
        array('message' => 'Missing Required Parameters')
    );
}
else {
    // Set ID to update
    $category_obj->id = $data['id'];
    
    // Delete post
    if ($category_obj->delete()) {
        echo json_encode(
            array('id' => $data['id'])
        );
    } 
}
