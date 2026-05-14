<?php
// register.php
session_start();
require 'config.php'; // Conectamos con la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recibimos los datos (Gotzu le puso name="name" a su input)
    $nombre = $_POST['name'] ?? $_POST['nombre'] ?? 'Usuario Nuevo';
    $email = $_POST['email'] ?? '';
    $password_plana = $_POST['password'] ?? '';

    // Encriptamos la contraseña (estándar BCRYPT)
    $password_segura = password_hash($password_plana, PASSWORD_DEFAULT);

    try {
        // Preparamos la consulta para evitar Inyección SQL
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, 'cliente')");
        
        if ($stmt->execute([$nombre, $email, $password_segura])) {
            // TRUCO PRO: Iniciar sesión automáticamente tras un registro exitoso
            $_SESSION['usuario_id'] = $pdo->lastInsertId();
            $_SESSION['usuario_nombre'] = $nombre;
            $_SESSION['usuario_rol'] = 'cliente';

            // Redirigimos a la tienda bonita sin mostrar ninguna pantalla fea
            header("Location: index.php");
            exit;
        }
    } catch (PDOException $e) {
        // Si el correo ya existe o hay error, lo regresamos a la tienda
        header("Location: index.php?error=registro");
        exit;
    }
} else {
    // Si intentan entrar directo a register.php desde la barra de direcciones, los regresamos
    header("Location: index.php");
    exit;
}
?>