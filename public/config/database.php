<?php
class Database {
    private $host = "mysql.railway.internal";
    private $port = "3306";
    private $db_name = "railway";
    private $username = "root";
    private $password = "piAZrDzSpmxGyGvJNVzlRJPGVdJmLrOy";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>