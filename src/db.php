<?php

class Database
{
    private $host = 'sql207.ezyro.com';
    private $db_name = 'ezyro_40614945_crmvendas';
    private $username = 'ezyro_40614945'; // Ajuste conforme necessário
    private $password = 'a4730c832ebb0';     // Ajuste conforme necessário
    public $conn;

    public function getConnection()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8mb4");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>