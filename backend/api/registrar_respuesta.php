<?php
require '../config/conexion.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Registrar respuesta de un usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['OpcionID'], $data['UsuarioID'])) {
        $stmt = $pdo->prepare("INSERT INTO Respuesta (FechaRespuesta, OpcionID, UsuarioID) 
                               VALUES (NOW(), ?, ?)");
        $stmt->execute([$data['OpcionID'], $data['UsuarioID']]);
        echo json_encode(['message' => 'Respuesta registrada exitosamente']);
    } else {
        echo json_encode(['error' => 'Datos incompletos']);
    }
}
?>
