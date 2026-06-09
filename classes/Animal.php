<?php

require_once __DIR__ . '/Model.php';

class Animal extends Model {
    private $table_name = "animals";

    // Výber všetkých zvieratiek
    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Výber konkrétneho zvieratka podľa ID
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Pridanie nového zvieratka
    public function create($name, $species, $breed, $description) {
        $query = "INSERT INTO " . $this->table_name . " (name, species, breed, description) 
                  VALUES (:name, :species, :breed, :description)";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'name' => $name,
            'species' => $species,
            'breed' => $breed,
            'description' => $description
        ]);
    }

    // Úprava existujúceho zvieratka
    public function update($id, $name, $species, $breed, $description) {
        $query = "UPDATE " . $this->table_name . " 
                  SET name = :name, species = :species, breed = :breed, description = :description 
                  WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'name' => $name,
            'species' => $species,
            'breed' => $breed,
            'description' => $description,
            'id' => $id
        ]);
    }

    // Vymazanie zvieratka
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['id' => $id]);
    }
}