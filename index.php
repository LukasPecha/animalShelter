<?php

require_once 'config.php';
require_once 'classes/Animal.php';
include_once 'parts/header.php';

// Inicializácia objektu databázy a pripojenie k nej
$database = new Database();
$db = $database->getConnection();

if ($db) {
    // Inicializácia zvieratka a výber všetkých zvieratiek z databázy
    $animalModel = new Animal($db);
    $animals = $animalModel->getAll();
    ?>

    <section class="hero-section">
        <h2>Spoznajte našich chlpáčov</h2>
        <p>Hľadajú milujúci domov a vernú rodinu. Každé zvieratko u nás čaká na svoju novú šancu.</p>
    </section>

    <!-- Výpis zvieratiek z db -->
    
    <div class="animal-grid">
        <?php if (!empty($animals)): ?>
            <?php foreach ($animals as $animal): ?>
                <div class="animal-card">

                    <div class="card-image-wrapper">
                        <img src="assets/img/<?php echo htmlspecialchars($animal['image'] ?? 'default.jpg'); ?>" 
                             alt="<?php echo htmlspecialchars($animal['name']); ?>"
                             class="animal-card-img">
                    </div>

                    <div class="card-header">
                        <h3><?php echo htmlspecialchars($animal['name']); ?></h3>
                        <p class="species"><?php echo htmlspecialchars($animal['species']); ?></p>
                    </div>
                    
                    <div class="card-body">
                        <p class="breed"><strong>Plemeno:</strong> <?php echo htmlspecialchars($animal['breed']); ?></p>
                        <p class="description"><?php echo htmlspecialchars($animal['description']); ?></p>
                    </div>
                    
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-data">
                <p>Momentálne sú všetky zvieratká šťastne adoptované. 🐾</p>
            </div>
        <?php endif; ?>
    </div>

<?php
} else {
    echo "<h1>Chyba: Nepodarilo sa spojiť s databázou.</h1>";
}

include_once 'parts/footer.php';
?>