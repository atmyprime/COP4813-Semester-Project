<?php
// backend/config.php
$host = 'localhost';
$db   = 'mealplanner';       // change to your DB name
$user = 'db_user';           // change to your DB user
$pass = 'db_password';       // change to your DB password

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
