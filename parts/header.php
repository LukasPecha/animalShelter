<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Útulok pre zvieratá</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="logo">
            <h1>🐾 Útulok pre zvieratá</h1>
        </div>
        <nav class="nav-links">
            <a href="index.php" class="nav-link">Domov</a>
            
            <?php if (isset($_SESSION['role'])): ?>
                <!-- Tento blok uvidia iba prihlásení -->
                <span class="user-info">Ahoj, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong> (<?php echo $_SESSION['role']; ?>)</span>
                
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <!-- Odkaz do admin zóny uvidí iba admin -->
                    <a href="admin/index.php" class="nav-link btn-admin">Administrácia</a>
                <?php endif; ?>
                
                <a href="logout.php" class="nav-link btn-logout">Odhlásiť sa</a>
            <?php else: ?>
                <!-- Tento odkaz uvidí iba neprihlásený  -->
                <a href="login.php" class="nav-link btn-login">Prihlásiť sa</a>
            <?php endif; ?>
        </nav>
    </header>
    <main class="main-container">