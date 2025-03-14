<?php

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Author.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate author object
$author_obj = new Author($db);

// Get raw author data
// true argument returns an associative array
$data = json_decode(file_get_contents("php://input"), true);

// This if-statement checks that all of the required keys and values for a new author are given.
// If the author_id or category_id are not in their respective tables, the author->create() 
// method will catch the error.
if(!array_key_exists('author', $data) || !$data['author']) {
    echo json_encode(
        array('message' => 'Missing Required Parameters')
    );
}
else {
    // Prepares information to create a new author
    $author_obj->author = $data['author'];

    // Creates author
    if ($author_obj->create()) {

        // Gets the current ID number in quotes table
        $query = 'select max(id) from authors;';
        $stmt = $db->query($query);
        $new_id_arr = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach($new_id_arr as $new_id_single) {
            $new_id = $new_id_single['max'];
        }

        echo json_encode(
            array(
                'id' => $new_id,
                'author' => $author_obj->author
            )
        );
    } 
}