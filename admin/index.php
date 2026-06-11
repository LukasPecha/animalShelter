<?php

require_once '../config.php'; 
require_once '../classes/Animal.php';

 
/* if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
     header('Location: ../login.php');
     exit();
}  */


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
        
        // Update zvieratka
        elseif (isset($_POST['update_id'])) {
            $update_id = intval($_POST['update_id']);
            $name = trim($_POST['name']);
            $species = trim($_POST['species']);
            $breed = trim($_POST['breed']);
            $description = trim($_POST['description']);
            
            if (!empty($name) && !empty($species) && !empty($description)) {
                
                // Načítame aktuálne zvieratko, a jeho obrázok, ak by sa nenahral nový obrázok zvieratka pri úprave
                $current_animal = $animalModel->getById($update_id);
                $image_name = $current_animal['image'] ?? 'default.jpg';

                // Spracovanie NOVÉHO obrázka pri úprave
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $fileTmpPath = $_FILES['image']['tmp_name'];
                    $fileName = $_FILES['image']['name'];
                    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

                    // Ak nahráme nový obrázok, použije sa, ak nie, zostáva pôvodný obrázok
                    if (in_array($fileExtension, $allowedExtensions)) {
                        $newFileName = uniqid() . '.' . $fileExtension;
                        $uploadFileDir = '../assets/img/';
                        $dest_path = $uploadFileDir . $newFileName;

                        if (move_uploaded_file($fileTmpPath, $dest_path)) {
                            $image_name = $newFileName;
                        }
                    } else {
                        $error = "Nepovolený formát obrázka (povolené sú jpg, jpeg, png, webp).";
                    }
                }

                if (empty($error)) {
                    if ($animalModel->update($update_id, $name, $species, $breed, $description, $image_name)) {
                        $success = "Údaje zvieratka <strong>" . htmlspecialchars($name) . "</strong> boli úspešne upravené!";
                    } else {
                        $error = "Chyba: Nepodarilo sa aktualizovať dáta.";
                    }
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
            $image_name = 'default.jpg'; // Predvolený obrázok, ak žiadny nenahráme

            // Spracovanie obrázka pri vytváraní
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['image']['tmp_name'];
                $fileName = $_FILES['image']['name'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

                if (in_array($fileExtension, $allowedExtensions)) {
                    $newFileName = uniqid() . '.' . $fileExtension;
                    $uploadFileDir = '../assets/img/';
                    $dest_path = $uploadFileDir . $newFileName;

                    if (move_uploaded_file($fileTmpPath, $dest_path)) {
                        $image_name = $newFileName;
                    }
                } else {
                    $error = "Nepovolený formát obrázka. Použil sa default obrázok.";
                }
            }

            if (!empty($name) && !empty($species) && !empty($description)) {
                if ($animalModel->create($name, $species, $breed, $description, $image_name)) {
                    $success = "Zvieratko <strong>" . htmlspecialchars($name) . "</strong> bolo úspešne pridané!";
                } else {
                    $error = "Chyba: Nepodarilo sa uložiť dáta.";
                }
            } else {
                $error = "Prosím, vyplňte všetky povinné polia.";
            }
        }
    }

    // Načítanie údajov pre úpravu zvieratka
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
    <link rel="stylesheet" href="assets/css/adminStyle.css">
</head>
<body>

    <div class="container">
        
        <header class="admin-header">
            <h1>⚙️ Administrácia útulku</h1>
            <nav class="admin-nav">
                <a href="../index.php" class="btn-back">⬅ Späť domov</a>
                <a href="../logout.php" class="logout-link">Odhlásiť sa</a>
            </nav>
        </header>

        <main>
            <!-- Výpis správ -->
            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <h2><?php echo $edit_animal ? "✏️ Upraviť zvieratko: " . htmlspecialchars($edit_animal['name']) : "➕ Pridanie nového zvieratka"; ?></h2>
            
            <!-- Formulár pre pridanie/úpravu zvieratka -->
            <form action="index.php" method="POST" enctype="multipart/form-data" class="admin-form">
                
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

                <!-- Výber obrázka zvieratka -->
                <div class="form-group">
                    <label for="image">Fotografia zvieratka:</label>
                    <input type="file" id="image" name="image" accept="image/*">
                    <?php if ($edit_animal && !empty($edit_animal['image'])): ?>
                        <small style="display:block; margin-top:5px; color:#718096;">Aktuálny obrázok: <?php echo htmlspecialchars($edit_animal['image']); ?></small>
                    <?php endif; ?>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-success">
                        <?php echo $edit_animal ? "💾 Uložiť zmeny" : "➕ Pridať do databázy"; ?>
                    </button>
                    
                    <?php if ($edit_animal): ?>
                        <a href="index.php" class="btn btn-cancel">Zrušiť úpravu</a>
                    <?php endif; ?>
                </div>
            </form>

            <h2>Zoznam zvierat v databáze</h2>
            
            <?php if (empty($animals)): ?>
                <p class="no-data">V databáze nie sú žiadne zvieratá.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Foto</th>
                                <th>Meno</th>
                                <th>Druh</th>
                                <th>Rasa</th>
                                <th>Popis</th>
                                <th class="actions-th">Akcie</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($animals as $animal): ?>
                                <tr>
                                    <td><?php echo $animal['id']; ?></td>
                                    <!-- Mini náhľad obrázka priamo v tabuľke správcu -->
                                    <td>
                                        <img src="../assets/img/<?php echo htmlspecialchars($animal['image'] ?? 'default.jpg'); ?>" alt="Foto" style="width: 45px; height: 45px; object-fit: cover; border-radius: 6px; border: 1px solid #e2e8f0;">
                                    </td>
                                    <td><strong><?php echo htmlspecialchars($animal['name']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($animal['species']); ?></td>
                                    <td><?php echo htmlspecialchars($animal['breed']); ?></td>
                                    <td class="td-desc"><?php echo htmlspecialchars($animal['description']); ?></td>
                                    <td class="td-actions">
                                        <a href="index.php?edit_id=<?php echo $animal['id']; ?>" class="btn btn-edit">Upraviť</a>
                                        
                                        <form action="index.php" method="POST" onsubmit="return confirm('Naozaj chcete zmazať zvieratko <?php echo htmlspecialchars($animal['name']); ?>?');" class="inline-form">
                                            <input type="hidden" name="delete_id" value="<?php echo $animal['id']; ?>">
                                            <button type="submit" class="btn btn-delete">Zmazať</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </main>

        <footer>
            <p>&copy; <?php echo date('Y'); ?> Útulok pre zvieratá - Admin panel</p>
        </footer>

    </div>

</body>
</html>