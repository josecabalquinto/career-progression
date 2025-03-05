<?php

class DatabaseConnection
{
    private $host = 'localhost';
    private $dbName = 'careerprogression';
    private $username = 'root';
    private $password = '';
    private $connection;

    public function connect()
    {
        if ($this->connection == null) {
            try {
                $this->connection = new PDO(
                    "mysql:host={$this->host};dbname={$this->dbName}",
                    $this->username,
                    $this->password
                );
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return $this->connection;
    }
}
