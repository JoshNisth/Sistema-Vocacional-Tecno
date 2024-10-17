<?php
require '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->query("SELECT * FROM Interes");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $pdo->prepare("INSERT INTO Interes (DescripcionInteres) VALUES (?)");
    $stmt->execute([$data['DescripcionInteres']]);
    echo json_encode(['message' => 'Interés creado']);
}
?>