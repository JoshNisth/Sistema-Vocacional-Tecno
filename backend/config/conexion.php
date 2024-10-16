<?php
$host = '127.0.0.1';
$port = '5432';
$dbname = 'Voca360DB';
$user = 'postgres';
$password = 'admin';

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
