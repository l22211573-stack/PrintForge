1. Consulta para el Historial de Pedidos:
SELECT 
    p.id AS 'Folio', u.nombre AS 'Cliente', pr.nombre AS 'Producto', 
    dp.cantidad AS 'Cantidad', p.estado AS 'Estatus', p.fecha_pedido AS 'Fecha'
FROM pedidos p
INNER JOIN usuarios u ON p.usuario_id = u.id
INNER JOIN detalle_pedido dp ON p.id = dp.pedido_id
INNER JOIN productos pr ON dp.producto_id = pr.id
ORDER BY p.fecha_pedido DESC;

2. Lógica para Actualizar Stock (al cerrar venta):
UPDATE productos SET stock = stock - [CANTIDAD_VENDIDA] WHERE id = [ID_PRODUCTO];


3. Cambio de estado del pedido:
UPDATE pedidos SET estado = '[NUEVO_ESTADO]' WHERE id = [ID_PEDIDO];