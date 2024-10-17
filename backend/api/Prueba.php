<?php
require '../config/conexion.php';

header("Content-Type: application/json");

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $stmt = $pdo->prepare("SELECT * FROM Prueba WHERE PruebaID = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $prueba = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($prueba);
        } else {
            $stmt = $pdo->query("SELECT * FROM Prueba");
            $pruebas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($pruebas);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['fechaReg'], $data['instrucciones'])) {
            $stmt = $pdo->prepare("INSERT INTO Prueba (FechaReg, Instrucciones) VALUES (:fechaReg, :instrucciones)");
            $stmt->bindParam(':fechaReg', $data['fechaReg']);
            $stmt->bindParam(':instrucciones', $data['instrucciones']);
            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Prueba creada']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error al crear la prueba']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['id'], $data['fechaReg'], $data['instrucciones'])) {
            $stmt = $pdo->prepare("UPDATE Prueba SET FechaReg = :fechaReg, Instrucciones = :instrucciones WHERE PruebaID = :id");
            $stmt->bindParam(':fechaReg', $data['fechaReg']);
            $stmt->bindParam(':instrucciones', $data['instrucciones']);
            $stmt->bindParam(':id', $data['id']);
            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Prueba actualizada']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error al actualizar la prueba']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['id'])) {
            $stmt = $pdo->prepare("DELETE FROM Prueba WHERE PruebaID = :id");
            $stmt->bindParam(':id', $data['id']);
            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Prueba eliminada']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error al eliminar la prueba']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Método no soportado']);
}
?>
