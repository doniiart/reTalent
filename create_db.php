<?php
try {
    $dbh = new PDO('mysql:host=127.0.0.1', 'root', '');
    $dbh->exec('CREATE DATABASE IF NOT EXISTS retalent');
    echo "Database created successfully\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
