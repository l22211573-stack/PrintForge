<?php
// api_productos.php
require 'config.php';
header('Content-Type: application/json'); // Indica que enviamos datos, no una página

try {
    // Traemos los productos con la info que Frank definió en la BD
    $stmt = $pdo->query("SELECT id, nombre, descripcion, precio, stock, imagen FROM productos");
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Lo convertimos al formato que JavaScript ama
    echo json_encode($productos);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error de conexión: ' . $e->getMessage()]);
}