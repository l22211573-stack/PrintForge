<?php
session_start();
require 'config.php';

// Seguridad: Solo entra si eres admin
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin') {
    die("Acceso denegado. Solo administradores.");
}

// --- LOGICA PARA EL HARDWARE DE CHUY ---
$raspberry_ip = "192.168.10.3"; // La IP que te pasó Chuy
// Intentamos conectar con un tiempo de espera corto (1 segundo) para no trabar la página
$socket = @fsockopen($raspberry_ip, 80, $errno, $errstr, 1);
if ($socket) {
    $estado_hardware = "<span style='color: #28a745;'>🟢 ONLINE (Conectado)</span>";
    fclose($socket);
} else {
    $estado_hardware = "<span style='color: #dc3545;'>🔴 OFFLINE (Desconectado)</span>";
}
// ---------------------------------------

// LA CONSULTA DE FRANK
$sql = "SELECT p.id AS Folio, u.nombre AS Cliente, pr.nombre AS Producto, 
               dp.cantidad AS Cantidad, p.estado AS Estatus, p.fecha_pedido AS Fecha
        FROM pedidos p
        INNER JOIN usuarios u ON p.usuario_id = u.id
        INNER JOIN detalle_pedido dp ON p.id = dp.pedido_id
        INNER JOIN productos pr ON dp.producto_id = pr.id
        ORDER BY p.fecha_pedido DESC";

$pedidos = $pdo->query($sql)->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Control - PrintForge</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background: #f0f2f5; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #343a40; color: white; }
        .status { font-weight: bold; text-transform: uppercase; font-size: 0.8em; }
        .btn-tienda { text-decoration: none; color: #007bff; font-weight: bold; }
    </style>
</head>
<body>
    <h1>📊 Panel de Administración - PrintForge</h1>
    <a href="index.php" class="btn-tienda">← Volver a la tienda</a>
    <br><br>

    <!-- MONITOR DE HARDWARE (CHUY) -->
    <div class="card">
        <h3>🔌 Estado del Hardware IoT</h3>
        <p>Dispositivo (Raspberry Pi Pico W): <strong><?= $estado_hardware ?></strong></p>
        <p><small>IP del nodo: <?= $raspberry_ip ?></small></p>
    </div>

    <!-- TABLA DE VENTAS (FRANK/AXEL) -->
    <div class="card">
        <h3>📋 Historial de Ventas</h3>
        <table>
            <thead>
                <tr>
                    <th>Folio</th>
                    <th>Cliente</th>
                    <th>Producto</th>
                    <th>Cant.</th>
                    <th>Estatus</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $p): ?>
                <tr>
                    <td>#<?= $p['Folio'] ?></td>
                    <td><?= htmlspecialchars($p['Cliente']) ?></td>
                    <td><?= htmlspecialchars($p['Producto']) ?></td>
                    <td><?= $p['Cantidad'] ?></td>
                    <td><span class="status"><?= $p['Estatus'] ?></span></td>
                    <td><?= $p['Fecha'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>