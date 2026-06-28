<?php
// config/database.php
// En haut du fichier, après require_once
require_once __DIR__ . '/../config/database.php';
define('DB_HOST', 'localhost');
define('DB_NAME', 'hospital_jmk');
define('DB_USER', 'root');
define('DB_PASS', ''); // Sur XAMPP/WAMP, le mot de passe est vide

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("❌ Erreur de connexion à la base de données : " . $e->getMessage());
}
