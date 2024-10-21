<?php
require '../config/conexion.php';

header("Access-Control-Allow-Origin: *");  // Esto permite solicitudes desde cualquier origen
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Métodos permitidos
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Encabezados permitidos
header("Content-Type: application/json");

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $stmt = $pdo->prepare("SELECT * FROM TipoPregunta WHERE TipoPreguntaID = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $tipo = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($tipo);
        } else {
            $stmt = $pdo->query("SELECT * FROM TipoPregunta");
            $tipos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($tipos);
        }
        break;
        
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['tipo'])) {
            $stmt = $pdo->prepare("INSERT INTO TipoPregunta (Tipo) VALUES (:tipo)");
            $stmt->bindParam(':tipo', $data['tipo']);
            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Tipo de pregunta creado']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error al crear el tipo de pregunta']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['id']) && isset($data['tipo'])) {
            $stmt = $pdo->prepare("UPDATE TipoPregunta SET Tipo = :tipo WHERE TipoPreguntaID = :id");
            $stmt->bindParam(':tipo', $data['tipo']);
            $stmt->bindParam(':id', $data['id']);
            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Tipo de pregunta actualizado']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el tipo de pregunta']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
        }
        break;

        case 'DELETE':
            $data = json_decode(file_get_contents('php://input'), true);
            if (isset($data['id'])) {
                $stmtPreguntas = $pdo->prepare("DELETE FROM Pregunta WHERE TipoPreguntaID = :tipoPreguntaId");
                $stmtPreguntas->bindParam(':tipoPreguntaId', $data['id']);
                $stmtPreguntas->execute();
        
                $stmt = $pdo->prepare("DELETE FROM TipoPregunta WHERE TipoPreguntaID = :id");
                $stmt->bindParam(':id', $data['id']);
                if ($stmt->execute()) {
                    echo json_encode(['status' => 'success', 'message' => 'Tipo de pregunta eliminado']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error al eliminar el tipo de pregunta']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
            }
            break;
        

    default:
        echo json_encode(['status' => 'error', 'message' => 'Método no soportado']);
}
?>
