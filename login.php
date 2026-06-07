<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $database = new Database();
        $db = $database->getConnection();

        // Vytiahneme používateľa podľa mena
        $query = "SELECT * FROM users WHERE username = :username LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Ak používateľ existuje, overíme jeho heslo
        if ($user && password_verify($password, $user['password'])) {
            // Heslo je správne, uložíme údaje do session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Presmerujeme ho podľa toho, kto to je
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
        $error = 'Prosím, vyplňte všetky polia.';
    }
}

include_once 'parts/header.php';
?>

<h2>Prihlásenie</h2>

<?php if (!empty($error)): ?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php endif; ?>

<form action="login.php" method="POST">
    <label for="username">Prihlasovacie meno:</label><br>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Heslo:</label><br>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Prihlásiť sa</button>
</form>

<?php
include_once 'parts/footer.php';
?>