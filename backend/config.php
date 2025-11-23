<?php
// backend/config.php
const DB_HOST = 'sql312.infinityfree.com';     
const DB_NAME = 'if0_40296760_cop4813';  
const DB_USER = 'if0_40296760';  
const DB_PASS = 'BMCqb8HtfwaZA'; 

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    // Simple but user-safe error message
    die("Database connection failed. Please try again later.");
}

session_start();
