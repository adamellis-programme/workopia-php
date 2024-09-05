<?php

class Database
{
    public $conn;



    /**
     * Constructor for Database class
     * constructor with config values passed in 
     *
     * @param array assos arr[key] $config The database configuration array
     */

    // RUN THROUGH CHAT AND SEE WHATS HAPPENING 
    public function __construct($config)
    {
        // Data Source Name
        // when isnstantiate a PDO we pass in the DSN which is a string
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}";

        // options for PDO
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];

        try {
            // $this->conn: The PDO object is stored in the $conn property of the Database class, making it accessible for further interactions with the database (e.g., running queries).
            $this->conn = new PDO($dsn, $config['username'], $config['password']);
            echo 'CONNECTED';
        } catch (PDOException $e) {
            // throw new Exception("Database connection failed! {$e->getMessage()}", 1);
            throw new Exception("Database connection failed...: " . $e->getMessage());
        }
    }
}
