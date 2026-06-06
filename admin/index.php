<?php

// pripojenie congif.php
require_once 'config.php';

// vytváranie objektu DB, a pripojenie k DB
$database = new Database();
$db = $database->getConnection();

// ak sme sa uspesne pripojili, vytiahneme všetky zvieratá z DB
if ($db) {

    $query = "SELECT * FROM animals";
    $stmt = $db->prepare($query);
    $stmt->execute();

    // všetky zvieratá dávame do premennej animals
    $animals = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h2>Zvieratá v útulku:</h2>";

    // foreach cyklom prejdeme zvieratá, a vypíšeme ich
    foreach ($animals as $animal) {
        echo "<p>";
        echo "<strong>Meno:</strong> " . htmlspecialchars($animal['name']) . "<br>";
        echo "<strong>Druh:</strong> " . htmlspecialchars($animal['species']) . "<br>";
        echo "<strong>Rasa:</strong> " . htmlspecialchars($animal['breed']) . "<br>";
        echo "<strong>Popis:</strong> " . htmlspecialchars($animal['description']) . "<br>";
        echo "</p><hr>";
    }
} else {
    echo "<h1>Chyba: Nepodarilo sa spojiť s databázou.</h1>";
}