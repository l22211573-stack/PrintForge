-- 1. Crear la base de datos y seleccionarla
CREATE DATABASE IF NOT EXISTS marketplace CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE marketplace;

-- 2. Crear tabla de Usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'cliente') DEFAULT 'cliente',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. Crear tabla de Productos (Impresoras, Filamentos y Modelos STL)
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    stock INT DEFAULT 0,
    categoria VARCHAR(100) NOT NULL,
    imagen VARCHAR(255) NOT NULL,
    tipo ENUM('fisico', 'digital') DEFAULT 'fisico',
    formato_digital VARCHAR(50) DEFAULT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4. Crear tabla de Pedidos
CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    metodo_envio ENUM('estandar', 'recoleccion_local', 'descarga_digital') NOT NULL,
    estado ENUM('pendiente', 'pagado', 'enviado', 'entregado') DEFAULT 'pendiente',
    fecha_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- 5. Crear tabla de Detalle de Pedido (Relación N:M entre Pedidos y Productos)
CREATE TABLE detalle_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);

-- 6. Insertar un usuario Administrador de prueba
INSERT INTO usuarios (nombre, email, password, rol) 
VALUES ('Admin Base', 'admin@test.com', '$2y$10$axhvV6eULz7brT7qfE/Bi.M4DMlPVKlKx8SrfsMD2VorbVq7Yi8lC', 'admin');

-- 7. Insertar el catálogo inicial de productos
INSERT INTO productos (id, nombre, descripcion, precio, stock, categoria, imagen, tipo, formato_digital) VALUES
(1, 'Creality Ender 3 V3 SE', 'La puerta de entrada perfecta al mundo 3D. Nivelación automática.', 4599.00, 15, 'Impresora FDM', 'https://via.placeholder.com/400x400.png?text=Ender+3', 'fisico', NULL),
(2, 'Anycubic Photon Mono M5s', 'Resolución 12K para detalles que desafían la vista.', 12850.00, 8, 'Impresora Resina', 'https://via.placeholder.com/400x400.png?text=Photon+Mono', 'fisico', NULL),
(3, 'Filamento PLA Pro - Negro Carbón', 'Acabado mate premium y cero warping.', 480.00, 120, 'Filamento', 'https://via.placeholder.com/400x400.png?text=PLA+Negro', 'fisico', NULL),
(4, 'Boquilla de Acero Endurecido 0.4mm', 'Para filamentos abrasivos como fibra de carbono y madera.', 320.00, 200, 'Refacciones', 'https://via.placeholder.com/400x400.png?text=Boquilla+Acero', 'fisico', NULL),
(5, 'Plataforma PEI Magnética Texturizada', 'Tus piezas se adhieren perfectamente al calentar.', 650.00, 60, 'Refacciones', 'https://via.placeholder.com/400x400.png?text=Cama+PEI', 'fisico', NULL),
(101, 'Modelo STL: Blink Warrior Hero', 'Archivo digital premium. Incluye soportes pre-configurados.', 250.00, 9999, 'Blink Galaxy Forge', 'https://via.placeholder.com/400x400.png?text=Blink+Warrior+STL', 'digital', '.STL'),
(102, 'Modelo STL: Nave Star-Leap Explorer', 'Réplica digital a escala. Diseño modular sin soportes internos.', 420.00, 9999, 'Blink Galaxy Forge', 'https://via.placeholder.com/400x400.png?text=Star-Leap+Ship', 'digital', '.STL');