<?php
require '../config/conexion.php';

header("Content-Type: application/json");

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $stmt = $pdo->prepare("SELECT * FROM Opcion WHERE OpcionID = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $opcion = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($opcion);
        } else {
            $stmt = $pdo->query("SELECT * FROM Opcion");
            $opciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($opciones);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['descripcion'], $data['inciso'], $data['preguntaId'])) {
            $stmt = $pdo->prepare("INSERT INTO Opcion (Descripcion, Inciso, PreguntaID) VALUES (:descripcion, :inciso, :preguntaId)");
            $stmt->bindParam(':descripcion', $data['descripcion']);
            $stmt->bindParam(':inciso', $data['inciso']);
            $stmt->bindParam(':preguntaId', $data['preguntaId']);
            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Opción creada']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error al crear la opción']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['id'], $data['descripcion'], $data['inciso'], $data['preguntaId'])) {
            $stmt = $pdo->prepare("UPDATE Opcion SET Descripcion = :descripcion, Inciso = :inciso, PreguntaID = :preguntaId WHERE OpcionID = :id");
            $stmt->bindParam(':descripcion', $data['descripcion']);
            $stmt->bindParam(':inciso', $data['inciso']);
            $stmt->bindParam(':preguntaId', $data['preguntaId']);
            $stmt->bindParam(':id', $data['id']);
            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Opción actualizada']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error al actualizar la opción']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'), true);
        $opcionId = $data['id'];
        
        $stmt = $pdo->prepare("DELETE FROM Respuesta WHERE OpcionID = :id");
        $stmt->bindParam(':id', $opcionId);
        $stmt->execute();
        
        $stmt = $pdo->prepare("DELETE FROM Opcion WHERE OpcionID = :id");
        $stmt->bindParam(':id', $opcionId);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Opción eliminada']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al eliminar']);
        }
        break;
    
    default:
        echo json_encode(['status' => 'error', 'message' => 'Método no soportado']);
}
?>