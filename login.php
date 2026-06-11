<?php

require_once 'config.php';
require_once 'classes/User.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $database = new Database();
        $db = $database->getConnection();

        if ($db) {
            $userModel = new User($db);
            $user = $userModel->login($username, $password);

            if ($user) {
                // Uloženie údajov do session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                if ($user['role'] === 'admin') {
                    header('Location: admin/index.php');
                } else {
                    header('Location: index.php');
                }
                exit();
            } else {
                $error = 'Nesprávne prihlasovacie meno alebo heslo!';
            }
        } else {
            $error = 'Chyba pripojenia k databáze.';
        }
    } else {
        $error = 'Prosím, vyplňte všetky polia.';
    }
}

include_once 'parts/header.php';
?>

<div class="login-wrapper">
    <div class="login-container">
        
        <h2>🔒 Prihlásenie</h2>
        
        <?php if (!empty($error)): ?>
            <div class="alert-error">
                ⚠️ <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="username">Prihlasovacie meno:</label>
                <input type="text" id="username" name="username" required placeholder="Zadajte meno">
            </div>

            <div class="form-group">
                <label for="password">Heslo:</label>
                <input type="password" id="password" name="password" required placeholder="Zadajte heslo">
            </div>

            <button type="submit" class="btn-login">Prihlásiť sa</button>
        </form>

    </div>
</div>

<?php
include_once 'parts/footer.php';
?>