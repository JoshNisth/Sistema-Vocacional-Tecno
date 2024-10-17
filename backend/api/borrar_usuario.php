<?php
include_once '../config/conexion.php';

header('Content-Type: application/json');

if (isset($_GET['usuarioid']) && isset($_GET['nombres'])) {
    $usuarioid = $_GET['usuarioid'];
    $nombres = $_GET['nombres'];

    try {
        $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $queryResultado = "DELETE FROM resultado WHERE usuarioid = :usuarioid";
        $stmtResultado = $pdo->prepare($queryResultado);
        $stmtResultado->bindParam(':usuarioid', $usuarioid);
        $stmtResultado->execute();

        $queryUsuario = "DELETE FROM usuario WHERE usuarioid = :usuarioid AND nombres = :nombres";
        $stmtUsuario = $pdo->prepare($queryUsuario);
        $stmtUsuario->bindParam(':usuarioid', $usuarioid);
        $stmtUsuario->bindParam(':nombres', $nombres);

        if ($stmtUsuario->execute()) {
            if ($stmtUsuario->rowCount() > 0) {
                echo json_encode([
                    "status" => "success",
                    "message" => "Usuario y sus resultados eliminados con éxito."
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "No se encontró un usuario con ese ID y nombre."
                ]);
            }
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Error al ejecutar la consulta de eliminación del usuario."
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            "status" => "error",
            "message" => "Error de conexión: " . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Faltan parámetros requeridos."
    ]);
}
?>
