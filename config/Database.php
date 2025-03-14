<?php

// https://youtu.be/OEWXbpUMODk?t=427
class Database
{
    // DB Params
    private $conn;
    private $host;
    private $port;
    private $dbname;
    private $username;
    private $password;

    public function __construct() {
        $this->username = getenv('USERNAME');
        $this->password = getenv('PASSWORD');
        $this->dbname = getenv('DBNAME');
        $this->host = getenv('HOST');
        // $this->port = getenv('PORT');    // This line is not required because the port number is hard-coded further down
    }

    // DB Connect
    public function connect()
    {
        if ($this->conn) {
            // connection already exists, return it
            return $this->conn;
        } 
        else {
            $port = 5432;   // Tje port number is hard-coded. Render web service does not work properly with the port as an environemnt variable.
            $dsn = "pgsql:host={$this->host};port={$port};dbname={$this->dbname};";
    
            try {
                $this->conn = new PDO($dsn, $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $this->conn;
            } catch (PDOException $e) {
                // echo for tutorial, but log the error for production
                echo 'Connection Error: ' . $e->getMessage();
            }
        }
    }
}
