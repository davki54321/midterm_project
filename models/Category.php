<?php

class Category {
    // DB related
    private $conn;
    private $table = "categories";

    // Category properties
    public $id;
    public $category;

    // Constructor
    public function __construct($db) {
        $this->conn = $db;
    }

    // Get categories
    public function read() {
        // Create Query
        $query = 
            'SELECT 
                id, category
            FROM
                ' . $this->table . '
            ORDER BY
                id DESC';

        // Prepare Statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();

        return $stmt;
    }

    // Get single category
    public function read_single() {

        // Create Query
        $query = 
            'SELECT
                id, category
            FROM
                ' . $this->table . '
            WHERE id = ?
            LIMIT 1';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind ID
        $stmt->bindParam(1, $this->id);

        // Execute query
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // This checks to see if the quote exists
        if ($row) {
            // Set properties
            $this->id = $row['id'];
            $this->category = $row['category'];
        }
    }

    // Create category
    public function create() {
        // Create category
        $query = 
            'INSERT INTO ' . $this->table . '
                (category)
            VALUES
                (:category)';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->category = htmlspecialchars(strip_tags($this->category));

        // Bind data
        $stmt->bindParam(':category', $this->category);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        // Print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);

        return false;        
    }

    // Update category
    public function update() {

        // This checks if the id exists in the categories tables.
        // If one or more of the DIs do not exist, false is returned. If all IDs exist, true is returned.
        if (!$this->id_exists()) {
            return false;
        }

        // Create query
        $query = 
            'UPDATE ' . $this->table . '
            SET
                category = :category
            WHERE
                id = :id';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind data
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':id', $this->id);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        // Print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);

        return false;        
    }

    // Delete category
    public function delete() {

        if (!$this->id_exists()) {
            return false;
        }

        // Create query
        $query = 
            'DELETE FROM ' . $this->table . '
            WHERE id = :id';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind data
        $stmt->bindParam(':id', $this->id);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        // Print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);

        return false;
    }

        // This method checks to see if an id exists in the authors table.
    // If the id exists, this method returns true. Otherwise it returns false.
    function id_exists() {
        $query = 
            'SELECT * FROM ' . $this->table . 
            ' WHERE id=' . $this->id . '
            LIMIT 1';
    
        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // $row will be empty of the id does not exist in the authors table
        if (!$row) {
            echo json_encode(
                array('message' => 'category_id Not Found')
            );
            return false;
        }
        return true;
    }
}