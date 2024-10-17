<?php
include_once '../config/conexion.php';

header('Content-Type: application/json');

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if (isset($_GET['usuarioid'])) {
        $usuarioid = $_GET['usuarioid'];

        $query = "SELECT * FROM resultado WHERE usuarioid = :usuarioid";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':usuarioid', $usuarioid);
        
        if ($stmt->execute()) {
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($resultados) {
                echo json_encode([
                    "status" => "success",
                    "data" => $resultados
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "No se encontraron resultados para este usuario."
                ]);
            }
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Error al ejecutar la consulta."
            ]);
        }
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Falta el parámetro usuarioid."
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Error de conexión: " . $e->getMessage()
    ]);
}
?>
