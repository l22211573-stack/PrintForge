<?php
// procesar_carrito_js.php
session_start();

// 1. Recibimos el JSON que JavaScript nos manda "por debajo del agua"
$json = file_get_contents('php://input');
$datos = json_decode($json, true);

// 2. Si recibimos datos válidos, armamos el carrito de PHP
if (is_array($datos)) {
    $_SESSION['cart'] = []; // Limpiamos cualquier carrito viejo
    
    foreach ($datos as $item) {
        // Tu checkout.php espera que el ID sea la llave y la cantidad el valor
        $_SESSION['cart'][$item['id']] = $item['cantidad'];
    }
    
    // Le avisamos a JavaScript que todo salió perfecto
    echo json_encode(["status" => "success", "mensaje" => "Carrito sincronizado con el servidor"]);
} else {
    // Si algo falla, mandamos error
    echo json_encode(["status" => "error", "mensaje" => "No se recibieron datos"]);
}
?>