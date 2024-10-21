<?php
//EL CODIGO FUE CORREGIDO, TENIA ERRORES
include_once '../config/conexion.php';

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");  // Incluye DELETE
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (isset($_GET['usuarioid']) && isset($_GET['nombres'])) {
        $usuarioid = $_GET['usuarioid'];
        $nombres = $_GET['nombres'];

        try {
            $queryUsuario = "DELETE FROM usuario WHERE usuarioid = :usuarioid AND nombres = :nombres";
            $stmtUsuario = $pdo->prepare($queryUsuario);
            $stmtUsuario->bindParam(':usuarioid', $usuarioid);
            $stmtUsuario->bindParam(':nombres', $nombres);

            if ($stmtUsuario->execute()) {
                if ($stmtUsuario->rowCount() > 0) {
                    echo json_encode(["status" => "success", "message" => "Usuario eliminado con éxito."]);
                } else {
                    echo json_encode(["status" => "error", "message" => "No se encontró un usuario con ese ID y nombre."]);
                }
            } else {
                echo json_encode(["status" => "error", "message" => "Error al eliminar el usuario."]);
            }
        } catch (PDOException $e) {
            echo json_encode(["status" => "error", "message" => "Error de conexión: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Faltan parámetros requeridos."]);
    }
}
?>
