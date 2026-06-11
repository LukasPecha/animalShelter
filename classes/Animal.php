<?php

require_once __DIR__ . '/Model.php';

class Animal extends Model {
    private $table_name = "animals";

    // Výber všetkých zvieratiek
    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $statement = $this->db->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    // Výber konkrétneho zvieratka podľa ID
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $statement = $this->db->prepare($query);
        $statement->execute(['id' => $id]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    // Pridanie nového zvieratka (rozšírené o obrázok)
    public function create($name, $species, $breed, $description, $image) {
        $query = "INSERT INTO " . $this->table_name . " (name, species, breed, description, image) 
                  VALUES (:name, :species, :breed, :description, :image)";

        $statement = $this->db->prepare($query);
        return $statement->execute([
            'name' => $name,
            'species' => $species,
            'breed' => $breed,
            'description' => $description,
            'image' => $image
        ]);
    }

    // Úprava existujúceho zvieratka (rozšírené o obrázok)
    public function update($id, $name, $species, $breed, $description, $image) {
        $query = "UPDATE " . $this->table_name . " 
                  SET name = :name, species = :species, breed = :breed, description = :description, image = :image 
                  WHERE id = :id";
        
        $statement = $this->db->prepare($query);
        return $statement->execute([
            'name' => $name,
            'species' => $species,
            'breed' => $breed,
            'description' => $description,
            'image' => $image,
            'id' => $id
        ]);
    }

    // Vymazanie zvieratka
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $statement = $this->db->prepare($query);
        return $statement->execute(['id' => $id]);
    }
}