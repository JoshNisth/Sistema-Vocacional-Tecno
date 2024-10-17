<?php
require '../config/conexion.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->query("SELECT * FROM Rol");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $pdo->prepare("INSERT INTO Rol (Nombre) VALUES (?)");
    $stmt->execute([$data['Nombre']]);
    echo json_encode(['message' => 'Rol creado']);
}
?>