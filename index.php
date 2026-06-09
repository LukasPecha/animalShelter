<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'config.php';
require_once 'classes/Animal.php';

include_once 'parts/header.php';

$database = new Database();
$db = $database->getConnection();

if ($db) {
    // Inicializácia zvieratka a výber všetkých zvieratiek z databázy
    $animalModel = new Animal($db);
    $animals = $animalModel->getAll();

    echo "<h2>Zvieratá v útulku:</h2>";

    // Výpis zvieratiek z db
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

include_once 'parts/footer.php';
?>