<?php
require '../config/conexion.php';

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->query("SELECT * FROM InteresUsuario");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $pdo->prepare("INSERT INTO InteresUsuario (UsuarioID, InteresID, FechaRegistro) 
                           VALUES (?, ?, NOW())");
    $stmt->execute([$data['UsuarioID'], $data['InteresID']]);
    echo json_encode(['message' => 'Relación creada']);
}
?>
