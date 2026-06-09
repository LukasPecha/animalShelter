<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Útulok pre zvieratá</title>
</head>
<body>
    <header>
        <h1>🐾 Útulok pre zvieratá</h1>
        <nav>
            <a href="index.php">Domov</a>
            
            <?php if (isset($_SESSION['role'])): ?>
                <!-- Tento blok uvidia iba prihlásení -->
                | <span>Ahoj, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong> (<?php echo $_SESSION['role']; ?>)</span>
                
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <!-- Odkaz do admin zóny uvidí iba admin -->
                    | <a href="admin/index.php" style="color: red; font-weight: bold;">Administrácia</a>
                <?php endif; ?>
                
                | <a href="logout.php">Odhlásiť sa</a>
            <?php else: ?>
                <!-- Tento odkaz uvidí iba neprihlásený  -->
                | <a href="login.php">Prihlásiť sa</a>
            <?php endif; ?>
        </nav>
    </header>
    <hr>
    <main>