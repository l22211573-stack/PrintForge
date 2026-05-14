-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         12.2.2-MariaDB - MariaDB Server
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.14.0.7165
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Volcando estructura para tabla marketplace.detalle_pedido
DROP TABLE IF EXISTS `detalle_pedido`;
CREATE TABLE IF NOT EXISTS `detalle_pedido` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pedido_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pedido_id` (`pedido_id`),
  KEY `producto_id` (`producto_id`),
  CONSTRAINT `1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla marketplace.detalle_pedido: ~16 rows (aproximadamente)
INSERT INTO `detalle_pedido` (`id`, `pedido_id`, `producto_id`, `cantidad`, `precio_unitario`) VALUES
	(1, 1, 1, 1, 4599.00),
	(2, 1, 1, 1, 4599.00),
	(3, 1, 1, 1, 4599.00),
	(4, 1, 1, 1, 4599.00),
	(5, 1, 1, 1, 4599.00),
	(6, 1, 1, 1, 4599.00),
	(7, 1, 1, 1, 4599.00),
	(8, 1, 1, 1, 4599.00),
	(9, 1, 1, 1, 4599.00),
	(10, 1, 1, 1, 4599.00),
	(11, 1, 1, 1, 4599.00),
	(12, 1, 1, 1, 4599.00),
	(13, 1, 1, 1, 4599.00),
	(14, 1, 1, 1, 4599.00),
	(15, 15, 3, 5, 480.00),
	(16, 16, 3, 5, 480.00);

-- Volcando estructura para tabla marketplace.pedidos
DROP TABLE IF EXISTS `pedidos`;
CREATE TABLE IF NOT EXISTS `pedidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `metodo_envio` enum('estandar','recoleccion_local','descarga_digital') NOT NULL,
  `estado` enum('pendiente','pagado','enviado','entregado') DEFAULT 'pendiente',
  `fecha_pedido` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla marketplace.pedidos: ~16 rows (aproximadamente)
INSERT INTO `pedidos` (`id`, `usuario_id`, `total`, `metodo_envio`, `estado`, `fecha_pedido`) VALUES
	(1, 1, 4599.00, 'estandar', 'enviado', '2026-05-13 23:20:43'),
	(2, 1, 4599.00, 'estandar', 'pendiente', '2026-05-13 23:21:01'),
	(3, 1, 4599.00, 'estandar', 'pendiente', '2026-05-13 23:21:02'),
	(4, 1, 4599.00, 'estandar', 'pendiente', '2026-05-13 23:21:03'),
	(5, 1, 4599.00, 'estandar', 'pendiente', '2026-05-13 23:21:04'),
	(6, 1, 4599.00, 'estandar', 'pendiente', '2026-05-13 23:21:04'),
	(7, 1, 4599.00, 'estandar', 'pendiente', '2026-05-13 23:21:05'),
	(8, 1, 4599.00, 'estandar', 'pendiente', '2026-05-13 23:21:05'),
	(9, 1, 4599.00, 'estandar', 'pendiente', '2026-05-13 23:21:05'),
	(10, 1, 4599.00, 'estandar', 'pendiente', '2026-05-13 23:21:06'),
	(11, 1, 4599.00, 'estandar', 'pendiente', '2026-05-13 23:22:56'),
	(12, 1, 4599.00, 'estandar', 'pendiente', '2026-05-13 23:23:33'),
	(13, 1, 4599.00, 'estandar', 'pendiente', '2026-05-13 23:24:07'),
	(14, 1, 4599.00, 'estandar', 'pendiente', '2026-05-14 03:27:47'),
	(15, 1, 2400.00, 'estandar', 'pagado', '2026-05-14 04:00:16'),
	(16, 1, 2400.00, 'estandar', 'pagado', '2026-05-14 05:01:15');

-- Volcando estructura para tabla marketplace.productos
DROP TABLE IF EXISTS `productos`;
CREATE TABLE IF NOT EXISTS `productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `categoria` varchar(100) NOT NULL,
  `imagen` varchar(255) NOT NULL,
  `tipo` enum('fisico','digital') DEFAULT 'fisico',
  `formato_digital` varchar(50) DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `activo` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla marketplace.productos: ~7 rows (aproximadamente)
INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `stock`, `categoria`, `imagen`, `tipo`, `formato_digital`, `fecha_creacion`, `activo`, `imagen_url`) VALUES
	(1, 'Creality Ender 3 V3 SE', 'La puerta de entrada perfecta al mundo 3D. Nivelación automática.', 4599.00, 13, 'Impresora FDM', 'https://via.placeholder.com/400x400.png?text=Ender+3', 'fisico', NULL, '2026-05-13 22:33:09', 1, 'img/ender_3_v3_se.jpeg'),
	(2, 'Anycubic Photon Mono M5s', 'Resolución 12K para detalles que desafían la vista.', 12850.00, 8, 'Impresora Resina', 'https://via.placeholder.com/400x400.png?text=Photon+Mono', 'fisico', NULL, '2026-05-13 22:33:09', 0, 'img/photon_mono_m5s.jpeg'),
	(3, 'Filamento PLA Pro - Negro Carbón', 'Acabado mate premium y cero warping.', 480.00, 110, 'Filamento', 'https://via.placeholder.com/400x400.png?text=PLA+Negro', 'fisico', NULL, '2026-05-13 22:33:09', 1, 'img/pla_negro_carbon.jpeg'),
	(4, 'Boquilla de Acero Endurecido 0.4mm', 'Para filamentos abrasivos como fibra de carbono y madera.', 320.00, 200, 'Refacciones', 'https://via.placeholder.com/400x400.png?text=Boquilla+Acero', 'fisico', NULL, '2026-05-13 22:33:09', 1, 'img/boquilla_acero.jpeg'),
	(5, 'Plataforma PEI Magnética Texturizada', 'Tus piezas se adhieren perfectamente al calentar.', 650.00, 60, 'Refacciones', 'https://via.placeholder.com/400x400.png?text=Cama+PEI', 'fisico', NULL, '2026-05-13 22:33:09', 1, 'img/cama_pei.jpeg'),
	(101, 'Modelo STL: Blink Warrior Hero', 'Archivo digital premium. Incluye soportes pre-configurados.', 250.00, 9999, 'Blink Galaxy Forge', 'https://via.placeholder.com/400x400.png?text=Blink+Warrior+STL', 'digital', '.STL', '2026-05-13 22:33:09', 1, 'img/sin_foto.jpg'),
	(102, 'Modelo STL: Nave Star-Leap Explorer', 'Réplica digital a escala. Diseño modular sin soportes internos.', 420.00, 9999, 'Blink Galaxy Forge', 'https://via.placeholder.com/400x400.png?text=Star-Leap+Ship', 'digital', '.STL', '2026-05-13 22:33:09', 1, 'img/sin_foto.jpg');

-- Volcando estructura para tabla marketplace.usuarios
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','cliente') DEFAULT 'cliente',
  `fecha_registro` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla marketplace.usuarios: ~1 rows (aproximadamente)
INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `rol`, `fecha_registro`) VALUES
	(1, 'Admin Base', 'admin@test.com', '$2y$10$axhvV6eULz7brT7qfE/Bi.M4DMlPVKlKx8SrfsMD2VorbVq7Yi8lC', 'admin', '2026-05-13 22:33:09');

-- Volcando estructura para vista marketplace.vista_historial_pedidos
DROP VIEW IF EXISTS `vista_historial_pedidos`;
-- Creando tabla temporal para superar errores de dependencia de VIEW
CREATE TABLE `vista_historial_pedidos` (
	`folio` INT(11) NOT NULL,
	`cliente` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`producto` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`cantidad` INT(11) NOT NULL,
	`estado` ENUM('pendiente','pagado','enviado','entregado') NULL COLLATE 'utf8mb4_unicode_ci',
	`fecha_pedido` TIMESTAMP NULL
);

-- Volcando estructura para disparador marketplace.restar_stock_automatico
DROP TRIGGER IF EXISTS `restar_stock_automatico`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER restar_stock_automatico
AFTER INSERT ON detalle_pedido
FOR EACH ROW
BEGIN
    UPDATE productos 
    SET stock = stock - NEW.cantidad 
    WHERE id = NEW.producto_id; 
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Eliminando tabla temporal y crear estructura final de VIEW
DROP TABLE IF EXISTS `vista_historial_pedidos`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vista_historial_pedidos` AS SELECT 
    p.id AS folio,
    u.nombre AS cliente,
    pr.nombre AS producto,
    dp.cantidad,
    p.estado,
    p.fecha_pedido
FROM pedidos p
INNER JOIN usuarios u ON p.usuario_id = u.id
INNER JOIN detalle_pedido dp ON p.id = dp.pedido_id
INNER JOIN productos pr ON dp.producto_id = pr.id 
;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
