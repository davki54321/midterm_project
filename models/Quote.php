<?php

class Quote {

    // DB related
    private $conn;
    private $quotes_table = "quotes";
    private $categories_table = "categories";
    private $authors_table = "authors";

    // Quote properties
    public $id;
    public $quote;
    public $author_id;
    public $category_id;

    // Constructor
    public function __construct($db) {
        $this->conn = $db;
    }

    /////////////////////////////////
    // Get all  quotes
    public function read() {

        // Create query
        $query = 
            'SELECT 
                q.id, q.quote, q.author_id, q.category_id,
                a.author,
                c.category
            FROM
                ' . $this->quotes_table . ' AS q
            LEFT JOIN 
                ' . $this->authors_table . ' AS a ON q.author_id = a.id
            LEFT JOIN 
                ' . $this->categories_table . ' AS c ON q.category_id = c.id
            ORDER BY 
                id ASC';

        // Prepare statement
        // $stmt is a PDO (PHP Data Object)
        // $stmt has been prepared but not executed
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();

        return $stmt;
    }

    /////////////////////////////////
    // Get single Quote
    public function read_single() {

        // Create query
        $query = 
            'SELECT 
                q.id, q.quote, q.author_id, q.category_id,
                a.author,
                c.category
            FROM
                ' . $this->quotes_table . ' AS q
            LEFT JOIN 
                ' . $this->authors_table . ' AS a ON q.author_id = a.id
            LEFT JOIN 
                ' . $this->categories_table . ' AS c ON q.category_id = c.id
            WHERE 
                q.id = ?
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
            $this->quote = $row['quote'];
    
            // Using 'author' and 'category' instead of 'author_id' and 'category_id' store the names from the
            // respective tables instead of the ID numbers
            $this->author_id = $row['author'];
            $this->category_id = $row['category'];
        }
    }

    /////////////////////////////////
    // Create new quote
    public function create() {

        if (!$this->check_author_id_and_category_id()) {
            return false;
        }

        // // manually set next value for id
        // $set_serial_query = 'ALTER SEQUENCE quotes_id_seq RESTART WITH 20;';
        // $stmt = $this->conn->prepare($set_serial_query);
        // $stmt->execute();

        // Create query
        $query = 'INSERT INTO ' . $this->quotes_table . ' 
                (quote, author_id, category_id)
            VALUES
                (:quote, :author_id, :category_id)';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->quote = htmlspecialchars(strip_tags($this->quote));
        $this->author_id = htmlspecialchars(strip_tags($this->author_id));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));

        // Bind data -- Binding named parameters
        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        // Print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);

        return false;
    }

    /////////////////////////////////
    // Update existing quote
    public function update() {

        // This checks if the quote id, author_id, and category_id exist in their respective tables.
        // If one or more of the DIs do not exist, false is returned. If all IDs exist, true is returned.
        if (!$this->quote_id_exists() || 
            !$this->check_author_id_and_category_id()) {
            return false;
        }

        // Create query
        $query = 'UPDATE ' . $this->quotes_table . '
            SET 
                quote = :quote, 
                author_id = :author_id,  
                category_id = :category_id
            WHERE 
                id = :id';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->quote = htmlspecialchars(strip_tags($this->quote));
        $this->author_id = htmlspecialchars(strip_tags($this->author_id));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));

        // Bind data
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        // Print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);

        return false;
    }

    /////////////////////////////////
    // Delete quote
    public function delete() {

        if (!$this->quote_id_exists()) {
            return false;
        }
        
        // Create query
        $query = 'DELETE FROM ' . $this->quotes_table . ' WHERE id = :id';

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

    // This method checks to see if an category_id exists in the categories table.
    // If the category_id exists, this method returns true. Otherwise it returns false.
    function author_id_exists() {
        $query = 
            'SELECT * FROM ' . $this->authors_table . 
            ' WHERE id=' . $this->author_id . '
            LIMIT 1';
    
        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // $row will be empty of the author_id does not exist in the authors table
        if (!$row) {
            echo json_encode(
                array('message' => 'author_id Not Found')
            );
            return false;
        }
        return true;
    }

    // This method checks to see if an author_id exists in the authors table.
    // If the author_id exists, this method returns true. Otherwise it returns false.
    function category_id_exists() {
        $query = 
            'SELECT * FROM ' . $this->categories_table . 
            ' WHERE id=' . $this->category_id . '
            LIMIT 1';
    
        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // $row will be empty of the author_id does not exist in the authors table
        if (!$row) {
            echo json_encode(
                array('message' => 'category_id Not Found')
            );
            return false;
        }
        return true;
    }

    function check_author_id_and_category_id() {
        return ($this->author_id_exists() && $this->category_id_exists());
    }

    function quote_id_exists() {
        $query = 
            'SELECT * FROM ' . $this->quotes_table . 
            ' WHERE id=' . $this->id . 
            ' LIMIT 1';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // $row will be empty if the quote id does not exist in the table
        if (!$row) {
            echo json_encode(
                array('message' => 'No Quotes Found')
            );
            return false;
        }
        return true;
    }
}   // end of Quote class