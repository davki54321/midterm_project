<?php

class Author {
    // DB related
    private $conn;
    private $table = "authors";

    // Author properties
    public $id;
    public $author;

    // Constructor with DB
    public function __construct($db) {
        $this->conn = $db;
    }

    // Get authors
    public function read() {
        // Create query
        $query =
            'SELECT
                id, author
            FROM
                ' . $this->table . '
            ORDER BY
                author DESC';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();

        return $stmt;
    }

    // Get single author
    public function read_single() {
        // Create query
        $query =
            'SELECT
                id, author
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
            $this->author = $row['author'];
        }
    }

    // Create new author
    public function create() {

        // Create query
        $query = 'INSERT INTO ' . $this->table . ' 
                (author)
            VALUES
                (:author)';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->author = htmlspecialchars(strip_tags($this->author));

        // Bind data -- Binding named parameters
        $stmt->bindParam(':author', $this->author);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        // Print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);

        return false;
    }

    // Update author
    public function update() {

        // This checks if the id exists in the authors tables.
        // If one or more of the DIs do not exist, false is returned. If all IDs exist, true is returned.
        if (!$this->id_exists()) {
            return false;
        }

        // Create query
        $query = 
            'UPDATE ' . $this->table . '
            SET
                author = :author
            WHERE
                id = :id';
        
        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind data
        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':id', $this->id);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        // Print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);

        return false;

    }

    // Delete author
    public function delete() {

        if (!$this->id_exists()) {
            return false;
        }

        // Create query
        $query = 
            'DELETE FROM ' . $this->table . '
            WHERE id = :id';

        // Prepare Statement
        $stmt = $this->conn->prepare($query);

        // clean data
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind Data
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
                array('message' => 'author_id Not Found')
            );
            return false;
        }
        return true;
    }
}