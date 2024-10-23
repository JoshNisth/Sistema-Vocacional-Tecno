<?php
require '../config/conexion.php';

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

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
            try {
                $stmt = $pdo->prepare("INSERT INTO Opcion (Descripcion, Inciso, PreguntaID) VALUES (:descripcion, :inciso, :preguntaId)");
                $stmt->bindParam(':descripcion', $data['descripcion']);
                $stmt->bindParam(':inciso', $data['inciso']);
                $stmt->bindParam(':preguntaId', $data['preguntaId']);
                
                if ($stmt->execute()) {
                    echo json_encode(['status' => 'success', 'message' => 'Opción creada']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error al crear la opción']);
                }
            } catch (PDOException $e) {
                echo json_encode(['status' => 'error', 'message' => 'Error en la consulta: ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
        }
        break;
    
    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'), true);
        $opcionId = $data['id'];
        
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
