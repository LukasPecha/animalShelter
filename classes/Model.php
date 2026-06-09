<?php
abstract class Model {
    // Spoločný atribút pre prístup k databáze
    protected $db;

    // Konštruktor ktorý inicializuje databázové spojenie
    public function __construct($db) {
        $this->db = $db;
    }
}