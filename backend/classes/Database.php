<?php
class Database {
    private mysqli $connection;
        
    public function __construct() {
        $this->connection = mysqli_connect("localhost", "root", "", "przepisy");
    }

    public function __destruct() {
        $this->connection->close();
    }

    public function getConnection(): mysqli {
        return $this->connection;
    }
}
