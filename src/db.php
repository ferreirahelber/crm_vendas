<?php

class Database
{
    // Configuração para XAMPP Local
    private $host = 'localhost';
    private $db_name = 'ia_finance_crm'; // O nome exato que criaste no Passo 3
    private $username = 'root';          // Usuário padrão do XAMPP
    private $password = '';              // Senha padrão do XAMPP é vazia
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