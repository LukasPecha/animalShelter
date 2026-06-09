<?php

require_once __DIR__ . '/Model.php';

class User extends Model {
    private $table_name = "users";  
    // Prihlasovacia metóda ktorá overí užívateľa podľa jeho mena a hesla
    public function login($username, $password) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username = :username LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }
}