<?php

//pripojenie databázy
define('DB_HOST', 'localhost');
define('DB_NAME', 'animal_shelter');
define('DB_USER', 'root');
define('DB_PASS', ''); 

//načítanie tried
spl_autoload_register(function ($class_name) {
    $path = __DIR__ . '/classes/' . $class_name . '.php';
    if (file_exists($path)) {
        require_once $path;
    }
});

// spustenie session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}