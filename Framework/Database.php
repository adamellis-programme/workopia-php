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
        // SET THE OBJECT KEYS TO VALUES
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            // fetch ass array and do not want the indexes
            // PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
        ];

        try {
            // $this->conn: The PDO object is stored in the $conn property of the Database class, making it accessible for further interactions with the database (e.g., running queries).
            $this->conn = new PDO($dsn, $config['username'], $config['password'], $options);
            echo 'CONNECTED';
        } catch (PDOException $e) {
            // throw new Exception("Database connection failed! {$e->getMessage()}", 1);
            throw new Exception("Database connection failed...: " . $e->getMessage());
        }
    }

    /**
     * Query the database.
     *
     * @param string $query The SQL query to execute
     *
     * @return PDOStatement The PDO statement object.
     * @throws Exception If query execution fails.
     * 
     * returen the statement as we are using it on 
     * users, listing, etc
     * 
     */


    public function query($query, $params = [])
    {
        try {
            // sth = statment & conn is the PDO INSTANCE
            $sth = $this->conn->prepare($query);
            // bind named params added in 
            foreach ($params as $param => $value) {
                inspect($param, );
                // we bind :id to $id
                $sth->bindValue(':' . $param, $value);
            }
            $sth->execute();
            // return $sth->fetchAll();
            return $sth;
        } catch (PDOException $e) {
            throw new Exception("Query execution failed: " . $e->getMessage());
        }
    }
}

/**
 * Why use a loop?
 * 1 Multiple Parameters: If your SQL query has 
 *  more than one parameter (like :id and :status),
 *  you need to bind each one to its corresponding value.
 *  The loop allows you to bind all parameters dynamically, without hardcoding each one.
 *
 * 2 Consistency: Whether you have one parameter (id) or many, the loop will handle them consistently, making the query() method more flexible.
 * 
 * 
 * 
 * 
 */
