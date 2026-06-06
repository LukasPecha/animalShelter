<?php

class Database {
    // udaje z configu ukladám do premenných
    private $host = DB_HOST;
    private $db_name = DB_NAME;
    private $username = DB_USER;
    private $password = DB_PASS;
    public $conn;

    // fukcia pre zavolanie databázy
    public function getConnection() {
        $this->conn = null;
        try {
            // PDO pre pripojenie k databáze
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            // error v databáze
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // nastavenie znakovej sady
            $this->conn->exec("set names utf8");
            
        } catch(PDOException $exception) {
            // výpis chyby pri pripojení k DB
            echo "Chyba pripojenia k databáze: " . $exception->getMessage();
        }
        
        return $this->conn;
    }
}