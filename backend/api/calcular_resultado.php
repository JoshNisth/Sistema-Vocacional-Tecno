<?php
require '../config/conexion.php';

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['UsuarioID'])) {
        $usuarioID = $data['UsuarioID'];

        // Consultar las respuestas del usuario
        $stmt = $pdo->prepare("SELECT OpcionID FROM Respuesta WHERE UsuarioID = ?");
        $stmt->execute([$usuarioID]);
        $respuestas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($respuestas) > 0) {
            // Inicializar variables de puntajes
            $puntosVerbal = 0;
            $puntosNumerico = 0;
            $puntosEspacial = 0;
            $puntosClerical = 0;

            // Asignar puntajes según las respuestas
            foreach ($respuestas as $respuesta) {
                switch ($respuesta['OpcionID']) {
                    case 1: // Opción que suma a razonamiento verbal
                        $puntosVerbal += 10;
                        break;
                    case 2: // Opción que suma a razonamiento numérico
                        $puntosNumerico += 10;
                        break;
                    case 3: // Opción que suma a habilidades espaciales
                        $puntosEspacial += 10;
                        break;
                    case 4: // Opción que suma a habilidades clericales
                        $puntosClerical += 10;
                        break;
                    // Añadir más casos según las opciones y áreas
                }
            }

            // Determinar el nivel de cada área
            $razonamientoVerbal = $puntosVerbal >= 30 ? 'Alto' : ($puntosVerbal >= 20 ? 'Medio' : 'Bajo');
            $razonamientoNumerico = $puntosNumerico >= 30 ? 'Alto' : ($puntosNumerico >= 20 ? 'Medio' : 'Bajo');
            $espacial = $puntosEspacial >= 30 ? 'Alto' : ($puntosEspacial >= 20 ? 'Medio' : 'Bajo');
            $clerical = $puntosClerical >= 30 ? 'Alto' : ($puntosClerical >= 20 ? 'Medio' : 'Bajo');

            // Definir la carrera sugerida en base a los puntajes
            if ($razonamientoNumerico === 'Alto' && $espacial === 'Alto' && ($clerical === 'Medio' || $clerical === 'Bajo')) {
                $sugerenciaVocacional = 'Ingeniería';
            } elseif ($razonamientoNumerico === 'Alto' && $clerical === 'Alto' && $espacial === 'Bajo') {
                $sugerenciaVocacional = 'Financieras';
            } elseif ($espacial === 'Alto' && ($razonamientoNumerico === 'Bajo' || $razonamientoNumerico === 'Medio') && $clerical === 'Bajo') {
                $sugerenciaVocacional = 'Diseño';
            } else {
                $sugerenciaVocacional = 'Otra carrera';  // Si no encaja en ninguna categoría
            }

            // Almacenar el resultado en la tabla Resultado
            $stmtInsert = $pdo->prepare("INSERT INTO Resultado 
                (ResumenIntereses, RazonamientoVerbal, RazonamientoNumerico, Espacial, Clerical, SugerenciaVocacional, UsuarioID) 
                VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmtInsert->execute([
                'Intereses calculados', $razonamientoVerbal, $razonamientoNumerico, 
                $espacial, $clerical, $sugerenciaVocacional, $usuarioID
            ]);

            echo json_encode(['message' => 'Resultado calculado y almacenado correctamente']);
        } else {
            echo json_encode(['error' => 'No se encontraron respuestas para este usuario']);
        }
    } else {
        echo json_encode(['error' => 'Datos incompletos']);
    }
}
?>