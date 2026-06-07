<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../config.php'; 

/*  
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
     header('Location: ../login.php');
     exit();
} 
*/

$success = '';
$error = '';

// Spracivanie formulára na pridávanie zvierat
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $species = trim($_POST['species']);
    $breed = trim($_POST['breed']);
    $description = trim($_POST['description']);

    // Meno, druh a popis sú povinné
    if (!empty($name) && !empty($species) && !empty($description)) {
        $database = new Database();
        $db = $database->getConnection();

        if ($db) {
            $query = "INSERT INTO animals (name, species, breed, description) 
                      VALUES (:name, :species, :breed, :description)";
            
            $stmt = $db->prepare($query);
            
            // Z premenných zapíšeme do SQL
            $result = $stmt->execute([
                'name' => $name,
                'species' => $species,
                'breed' => $breed,
                'description' => $description
            ]);

            if ($result) {
                $success = "Zvieratko <strong>" . htmlspecialchars($name) . "</strong> bolo úspešne pridané do útulku!";
            } else {
                $error = "Chyba: Nepodarilo sa uložiť dáta do databázy.";
            }
        } else {
            $error = "Chyba: Nepodarilo sa spojiť s databázou.";
        }
    } else {
        $error = "Prosím, vyplňte všetky povinné polia (Meno, Druh, Popis).";
    }
}
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrácia - Útulok</title>
</head>
<body>

    <header>
        <h1>Administrácia útulku</h1>
        <nav>
            <a href="../index.php">⬅ Späť na hlavnú stránku (Domov)</a> | 
            <a href="../logout.php">Odhlásiť sa</a>
        </nav>
    </header>
    <hr>

    <main>
        <h2>Pridanie nového zvieratka</h2>

        <!-- Výpis úspechu alebo chyby -->
        <?php if (!empty($success)): ?>
            <p style="color: green; background-color: #e6ffe6; padding: 10px; border: 1px solid green;"><?php echo $success; ?></p>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <p style="color: red; background-color: #ffe6e6; padding: 10px; border: 1px solid red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <!-- Formulár -->
        <form action="index.php" method="POST">
            <label for="name">Meno zvieratka *:</label><br>
            <input type="text" id="name" name="name" required><br><br>

            <label for="species">Druh zvieratka * (napr. Pes, Mačka):</label><br>
            <input type="text" id="species" name="species" required><br><br>

            <label for="breed">Rasa / Plemeno (nepovinné):</label><br>
            <input type="text" id="breed" name="breed"><br><br>

            <label for="description">Popis / Príbeh zvieratka *:</label><br>
            <textarea id="description" name="description" rows="5" cols="40" required></textarea><br><br>

            <button type="submit" style="padding: 5px 15px; cursor: pointer;">➕ Pridať do databázy</button>
        </form>
    </main>

    <hr>
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Útulok pre zvieratá - Admin panel</p>
    </footer>

</body>
</html>