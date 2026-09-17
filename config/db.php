<?php
// config/db.php

$db_file = __DIR__ . '/../database.sqlite';

try {
    // Connect to SQLite database
    $pdo = new PDO("sqlite:" . $db_file);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}

/**
 * Helper to ensure database is initialized
 */
function check_and_init_db($pdo) {
    // Check if agenda table exists
    $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='agenda'");
    if (!$stmt->fetch()) {
        require_once __DIR__ . '/../init_db.php';
    }
}

check_and_init_db($pdo);
