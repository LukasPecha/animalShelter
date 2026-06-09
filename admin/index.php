<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../config.php'; 
require_once '../classes/Animal.php';

 
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
     header('Location: ../login.php');
     exit();
} 


$success = '';
$error = '';
$edit_animal = null;

$database = new Database();
$db = $database->getConnection();

if ($db) {
    // Inicializácia objektu Animal
    $animalModel = new Animal($db);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        // Delete zvieratka 
        if (isset($_POST['delete_id'])) {
            $delete_id = intval($_POST['delete_id']);
            
            if ($animalModel->delete($delete_id)) {
                $success = "Zvieratko bolo úspešne vymazané z databázy.";
            } else {
                $error = "Chyba: Nepodarilo sa vymazať zvieratko.";
            }
        }
        
        // Create zvieratka
        elseif (isset($_POST['update_id'])) {
            $update_id = intval($_POST['update_id']);
            $name = trim($_POST['name']);
            $species = trim($_POST['species']);
            $breed = trim($_POST['breed']);
            $description = trim($_POST['description']);

            if (!empty($name) && !empty($species) && !empty($description)) {
                if ($animalModel->update($update_id, $name, $species, $breed, $description)) {
                    $success = "Údaje zvieratka <strong>" . htmlspecialchars($name) . "</strong> boli úspešne upravené!";
                } else {
                    $error = "Chyba: Nepodarilo sa aktualizovať dáta.";
                }
            } else {
                $error = "Prosím, vyplňte všetky povinné polia.";
            }
        }
        
        // Create zvieratka
        elseif (isset($_POST['name'])) {
            $name = trim($_POST['name']);
            $species = trim($_POST['species']);
            $breed = trim($_POST['breed']);
            $description = trim($_POST['description']);

            if (!empty($name) && !empty($species) && !empty($description)) {
                if ($animalModel->create($name, $species, $breed, $description)) {
                    $success = "Zvieratko <strong>" . htmlspecialchars($name) . "</strong> bolo úspešne pridané!";
                } else {
                    $error = "Chyba: Nepodarilo sa uložiť dáta.";
                }
            } else {
                $error = "Prosím, vyplňte všetky povinné polia.";
            }
        }
    }

    // Načítanie údajou pre úpravu zvieratka
    if (isset($_GET['edit_id'])) {
        $edit_id = intval($_GET['edit_id']);
        $edit_animal = $animalModel->getById($edit_id);
    }

    // Vytiahnutie všetkých zvieratiek
    $animals = $animalModel->getAll();

} else {
    $error = "Chyba: Nepodarilo sa spojiť s databázou.";
    $animals = [];
}
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrácia - Útulok</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <div class="container">
        
        <header>
            <h1>⚙️ Administrácia útulku</h1>
            <nav>
                <a href="../index.php" style="text-decoration: none; margin-right: 15px; color: #555; font-weight: 600;">⬅ Späť domov</a>
                <a href="../logout.php" class="logout-link">Odhlásiť sa</a>
            </nav>
        </header>

        <main>
            <!-- Výpis správ s upraveným modernejším dizajnom -->
            <?php if (!empty($success)): ?>
                <p style="color: #27ae60; background-color: #e8f8f0; padding: 12px; border: 1px solid #27ae60; border-radius: 4px; margin-bottom: 20px;"><?php echo $success; ?></p>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <p style="color: #c0392b; background-color: #fceae9; padding: 12px; border: 1px solid #c0392b; border-radius: 4px; margin-bottom: 20px;"><?php echo $error; ?></p>
            <?php endif; ?>

            <h2><?php echo $edit_animal ? "✏️ Upraviť zvieratko: " . htmlspecialchars($edit_animal['name']) : "➕ Pridanie nového zvieratka"; ?></h2>
            
            <form action="index.php" method="POST" style="margin-bottom: 40px;">
                
                <?php if ($edit_animal): ?>
                    <input type="hidden" name="update_id" value="<?php echo $edit_animal['id']; ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label for="name">Meno zvieratka *:</label>
                    <input type="text" id="name" name="name" value="<?php echo $edit_animal ? htmlspecialchars($edit_animal['name']) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="species">Druh zvieratka *:</label>
                    <input type="text" id="species" name="species" value="<?php echo $edit_animal ? htmlspecialchars($edit_animal['species']) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="breed">Rasa / Plemeno:</label>
                    <input type="text" id="breed" name="breed" value="<?php echo $edit_animal ? htmlspecialchars($edit_animal['breed']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="description">Popis *:</label>
                    <textarea id="description" name="description" required><?php echo $edit_animal ? htmlspecialchars($edit_animal['description']) : ''; ?></textarea>
                </div>

                <button type="submit" class="btn btn-success">
                    <?php echo $edit_animal ? "💾 Uložiť zmeny" : "➕ Pridať do databázy"; ?>
                </button>
                
                <?php if ($edit_animal): ?>
                    <a href="index.php" class="btn btn-primary" style="margin-left: 10px; background-color: #7f8c8d;">Zrušiť úpravu</a>
                <?php endif; ?>
            </form>

            <h2>Zoznam zvierat v databáze (Správa)</h2>
            
            <?php if (empty($animals)): ?>
                <p>V databáze nie sú žiadne zvieratá.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Meno</th>
                            <th>Druh</th>
                            <th>Rasa</th>
                            <th>Popis</th>
                            <th style="min-width: 160px;">Akcie</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($animals as $animal): ?>
                            <tr>
                                <td><?php echo $animal['id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($animal['name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($animal['species']); ?></td>
                                <td><?php echo htmlspecialchars($animal['breed']); ?></td>
                                <td><?php echo htmlspecialchars($animal['description']); ?></td>
                                <td>
                                    <a href="index.php?edit_id=<?php echo $animal['id']; ?>" class="btn btn-success" style="padding: 5px 10px; font-size: 14px; margin-right: 5px;">Upraviť</a>
                                    
                                    <form action="index.php" method="POST" onsubmit="return confirm('Naozaj chcete zmazať zvieratko <?php echo htmlspecialchars($animal['name']); ?>?');" style="display:inline-block;">
                                        <input type="hidden" name="delete_id" value="<?php echo $animal['id']; ?>">
                                        <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 14px;">Zmazať</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </main>

        <footer>
            <p>&copy; <?php echo date('Y'); ?> Útulok pre zvieratá - Admin panel</p>
        </footer>

    </div>

</body>
</html>