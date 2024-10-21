<?php
require '../config/conexion.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

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
                // Primero, obtener todas las preguntas asociadas a la prueba
                $stmtPreguntas = $pdo->prepare("SELECT PreguntaID FROM Pregunta WHERE PruebaID = :pruebaId");
                $stmtPreguntas->bindParam(':pruebaId', $data['id']);
                $stmtPreguntas->execute();
                $preguntas = $stmtPreguntas->fetchAll(PDO::FETCH_COLUMN);
        
                // Luego, eliminar las respuestas asociadas a las opciones de las preguntas
                if ($preguntas) {
                    // Obtener todas las opciones asociadas a las preguntas
                    $placeholders = rtrim(str_repeat('?,', count($preguntas)), ',');
                    $stmtOpciones = $pdo->prepare("SELECT OpcionID FROM Opcion WHERE PreguntaID IN ($placeholders)");
                    $stmtOpciones->execute($preguntas);
                    $opciones = $stmtOpciones->fetchAll(PDO::FETCH_COLUMN);
        
                    // Eliminar las respuestas asociadas a las opciones
                    if ($opciones) {
                        $placeholdersRespuestas = rtrim(str_repeat('?,', count($opciones)), ',');
                        $stmtRespuestas = $pdo->prepare("DELETE FROM Respuesta WHERE OpcionID IN ($placeholdersRespuestas)");
                        $stmtRespuestas->execute($opciones);
                    }
        
                    // Luego, eliminar las opciones asociadas a las preguntas
                    $stmtOpcionesDelete = $pdo->prepare("DELETE FROM Opcion WHERE PreguntaID IN ($placeholders)");
                    $stmtOpcionesDelete->execute($preguntas);
                }
        
                // Ahora eliminar las preguntas asociadas a la prueba
                $stmtPreguntasDelete = $pdo->prepare("DELETE FROM Pregunta WHERE PruebaID = :pruebaId");
                $stmtPreguntasDelete->bindParam(':pruebaId', $data['id']);
                $stmtPreguntasDelete->execute();
        
                // Finalmente, eliminar la prueba
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
