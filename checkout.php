<?php
// checkout.php
session_start();
require 'config.php';

if (empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id']; 
$metodo_envio = 'estandar'; 

try {
    $total_pedido = 0;
    foreach ($_SESSION['cart'] as $id => $cantidad) {
        $stmt = $pdo->prepare("SELECT precio FROM productos WHERE id = ?");
        $stmt->execute([$id]);
        $producto = $stmt->fetch();
        if ($producto) {
            $total_pedido += $producto['precio'] * $cantidad;
        }
    }

    $pdo->beginTransaction();

    // 2. Insertamos el Pedido Principal
    $stmt_pedido = $pdo->prepare("INSERT INTO pedidos (usuario_id, total, metodo_envio) VALUES (?, ?, ?)");
    $stmt_pedido->execute([$usuario_id, $total_pedido, $metodo_envio]);
    $pedido_id = $pdo->lastInsertId();

    // 3. Insertamos detalles Y actualizamos stock (Lo que pidió Frank)
    $stmt_detalle = $pdo->prepare("INSERT INTO detalle_pedido (pedido_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
    $stmt_stock = $pdo->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?");
    
    foreach ($_SESSION['cart'] as $producto_id => $cantidad) {
        // Necesitamos el precio unitario para el detalle
        $stmt_precio = $pdo->prepare("SELECT precio FROM productos WHERE id = ?");
        $stmt_precio->execute([$producto_id]);
        $precio_unitario = $stmt_precio->fetchColumn();

        // Guardamos el detalle del producto en la venta
        $stmt_detalle->execute([$pedido_id, $producto_id, $cantidad, $precio_unitario]);

        // Restamos del inventario (Lógica de Frank)
        $stmt_stock->execute([$cantidad, $producto_id]);
    }

    $pdo->commit();
    unset($_SESSION['cart']);
    $mensaje = "¡Éxito! Tu pedido #" . $pedido_id . " se ha guardado correctamente.";

} catch (\PDOException $e) {
    $pdo->rollBack();
    die("Error al procesar el pedido: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago Exitoso - PrintForge</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .success-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 80vh;
            padding: 20px;
        }
        .success-card {
            background: var(--bg-panel);
            backdrop-filter: blur(12px);
            border: 1px solid var(--success);
            border-radius: var(--radius-xl);
            padding: 50px 40px;
            text-align: center;
            max-width: 500px;
            box-shadow: 0 0 40px rgba(34, 197, 94, 0.15);
            animation: fadeInUp 0.6s ease-out;
        }
        .success-icon {
            font-size: 5rem;
            margin-bottom: 20px;
        }
        .success-title {
            font-family: 'Outfit', sans-serif;
            font-size: 2rem;
            font-weight: 800;
            color: var(--success);
            margin-bottom: 15px;
        }
        .success-text {
            color: var(--text-muted);
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .success-btn {
            display: inline-block;
            padding: 14px 32px;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: #fff;
            font-weight: 700;
            border-radius: 999px;
            text-decoration: none;
            box-shadow: 0 4px 20px rgba(34, 197, 94, 0.3);
            transition: all var(--transition);
        }
        .success-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(34, 197, 94, 0.4);
        }
    </style>
</head>
<body>
    <header class="navbar">
        <div class="nav-logo">
            <h2>🔥 PrintForge</h2>
        </div>
    </header>

    <main class="success-container">
        <div class="success-card">
            <div class="success-icon">🎉</div>
            <h1 class="success-title">¡Compra Realizada!</h1>
            <p class="success-text">
                <?= htmlspecialchars($mensaje) ?><br><br>
                Frank ya ha actualizado el inventario en la base de datos y la orden ha sido registrada en el panel de administrador. ¡Gracias por confiar en PrintForge!
            </p>
            <a href="index.php" class="success-btn">← Volver al catálogo</a>
        </div>
    </main>

    <script>
        localStorage.removeItem('printforge_cart');
    </script>
</body>
</html>