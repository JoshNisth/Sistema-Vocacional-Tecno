<?php
require '../config/conexion.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Listar usuarios
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->query("SELECT * FROM Usuario");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($usuarios);
}

// Crear usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $hashedPassword = password_hash($data['Contrasena'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO Usuario (Nombres, Apellidos, Email, Contrasena, RolID) 
                           VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $data['Nombres'], $data['Apellidos'], 
        $data['Email'], $hashedPassword, $data['RolID']
    ]);
    echo json_encode(['message' => 'Usuario creado']);
}

// Autenticación de usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['login'])) {
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $pdo->prepare("SELECT * FROM Usuario WHERE Email = ?");
    $stmt->execute([$data['Email']]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($data['Contrasena'], $usuario['Contrasena'])) {
        echo json_encode(['message' => 'Autenticación exitosa']);
    } else {
        http_response_code(401);
        echo json_encode(['message' => 'Credenciales incorrectas']);
    }
}
?>
