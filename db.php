<?php
$host = 'localhost';
$dbname = 'db_blockchain_vote';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
