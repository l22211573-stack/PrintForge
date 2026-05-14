<?php
// login.php
session_start();
require 'config.php';

// Si recibimos los datos del formulario de Gotzu...
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Buscamos al usuario por su correo
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();

    if ($usuario) {
        // Verificamos la contraseña (Segura o Rápida)
        $login_seguro = password_verify($password, $usuario['password']);
        $login_rapido = (md5($password) === $usuario['password']);

        if ($login_seguro || $login_rapido) {
            // ¡Éxito! Guardamos sus datos en la sesión
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_rol'] = $usuario['rol'];

            // Lo mandamos de regreso a la tienda principal, ya con su sesión iniciada
            header("Location: index.php");
            exit;
        } else {
            // Contraseña incorrecta, lo regresamos a la tienda
            header("Location: index.php?error=auth");
            exit;
        }
    } else {
        // El correo no existe, lo regresamos a la tienda
        header("Location: index.php?error=auth");
        exit;
    }
} else {
    // Si alguien intenta entrar a login.php directamente desde la URL, lo regresamos al inicio
    header("Location: index.php");
    exit;
}
?>