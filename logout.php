<?php

require_once 'config.php';

// Vyčistíme premenné zo session
$_SESSION = array();

// Zničíme celú session
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

// Presmerujeme používateľa na domovskú stránku
header("Location: index.php");
exit();