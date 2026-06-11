<?php

class Database {
    // Do premenných dávame hodnoty z config.php
    private $host = DB_HOST;
    private $db_name = DB_NAME;
    private $username = DB_USER;
    private $password = DB_PASS;
    public $conn;

    // Funkcia pre pripojenie k databáze
    public function getConnection() {
        $this->conn = null;

        try {
            // Vytvorenie PDO pripojenia a nastavenie znakovej sady
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password
            );

            // Nastavenie vyhadzovania výnimiek pri chybách
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Výpis chyby pri pripojení k DB
        } catch(PDOException $exception) {
            echo "Chyba pripojenia k databáze: " . $exception->getMessage();
        }
        
        return $this->conn;
    }
}
?>