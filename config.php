<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
define('DB_HOST', 'localhost');
define('DB_NAME', 'animal_shelter');
define('DB_USER', 'root');
define('DB_PASS', ''); 

require_once __DIR__ . '/classes/Database.php';
?>