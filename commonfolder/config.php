<?php
class Config
{
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "Employee_management";
    public $conn;
    public function __construct()
    {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->database);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
    public function query($sql)
    {
        return $this->conn->query($sql);
    }
    public function fetch_assoc($result)
    {
        return $result->fetch_assoc();
    }
    public function getInsertId() {
        return $this->conn->insert_id;
    }
}
?>