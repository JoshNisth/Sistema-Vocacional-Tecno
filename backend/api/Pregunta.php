<?php
require '../config/conexion.php';

header("Content-Type: application/json");

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $stmt = $pdo->prepare("SELECT * FROM Pregunta WHERE PreguntaID = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $pregunta = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($pregunta);
        } else {
            $stmt = $pdo->query("SELECT * FROM Pregunta");
            $preguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($preguntas);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['contenido'], $data['tipoPreguntaID'], $data['pruebaID'])) {
            $stmt = $pdo->prepare("INSERT INTO Pregunta (Contenido, TipoPreguntaID, PruebaID) VALUES (:contenido, :tipoPreguntaID, :pruebaID)");
            $stmt->bindParam(':contenido', $data['contenido']);
            $stmt->bindParam(':tipoPreguntaID', $data['tipoPreguntaID']);
            $stmt->bindParam(':pruebaID', $data['pruebaID']);
            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Pregunta creada']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error al crear la pregunta']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['id'], $data['contenido'], $data['tipoPreguntaID'], $data['pruebaID'])) {
            $stmt = $pdo->prepare("UPDATE Pregunta SET Contenido = :contenido, TipoPreguntaID = :tipoPreguntaID, PruebaID = :pruebaID WHERE PreguntaID = :id");
            $stmt->bindParam(':contenido', $data['contenido']);
            $stmt->bindParam(':tipoPreguntaID', $data['tipoPreguntaID']);
            $stmt->bindParam(':pruebaID', $data['pruebaID']);
            $stmt->bindParam(':id', $data['id']);
            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Pregunta actualizada']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error al actualizar la pregunta']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
        }
        break;

        case 'DELETE':
            $data = json_decode(file_get_contents('php://input'), true);
            $preguntaId = $data['id'];
            
            $stmtRespuestas = $pdo->prepare("DELETE FROM Respuesta WHERE OpcionID IN (SELECT OpcionID FROM Opcion WHERE PreguntaID = :preguntaId)");
            $stmtRespuestas->bindParam(':preguntaId', $preguntaId);
            $stmtRespuestas->execute();
        
            $stmtOpciones = $pdo->prepare("DELETE FROM Opcion WHERE PreguntaID = :preguntaId");
            $stmtOpciones->bindParam(':preguntaId', $preguntaId);
            $stmtOpciones->execute();
            
            $stmt = $pdo->prepare("DELETE FROM Pregunta WHERE PreguntaID = :id");
            $stmt->bindParam(':id', $preguntaId);
            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Pregunta eliminada']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error al eliminar']);
            }
            break;
        
    default:
        echo json_encode(['status' => 'error', 'message' => 'Método no soportado']);
}
?>
