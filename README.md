# 🛠️ PrintForge - Marketplace Híbrido 3D

PrintForge es un marketplace de impresión 3D montado en una Raspberry Pi 4. Diseñado para la comunidad maker, maneja una arquitectura híbrida: venta de productos físicos (impresoras, filamentos) con envío local, y venta de activos digitales (modelos STL) con descarga inmediata

##  Características
- **Carrito Híbrido:** Lógica de envíos inteligente (físico vs digital).
- **Backend Ligero:** PHP 8 puro y MariaDB (sin frameworks pesados).
- **Integración Web3 (Bonus):** Sección "Blink Galaxy Forge" con modelos STL oficiales del juego.

## ⚙️ Instrucciones de Instalación (Entorno Local en Raspberry Pi)
1. **Clonar el repositorio:** `git clone https://github.com/tu-usuario/printforge.git`
2. **Base de Datos:** Importar el archivo `database.sql` en MariaDB (`sudo mysql -u root -p`).
3. **Servidor Web:** Mover los archivos a `/var/www/html/printforge`.
4. **Acceso:** Entrar desde cualquier dispositivo en la misma red a la IP de la Raspberry Pi.

---
## 👥 Trabajo en equipo: Roles y Responsabilidades Técnicas

### 1. Axel Vallejo – Arquitecto Backend y Seguridad
* **Lógica de Negocio:** Programó validadores en PHP para distinguir entre productos físicos y activos digitales (STL).
* **Seguridad:** Implementó `password_hash()` con BCRYPT y manejo de sesiones seguras.

### 2. Francisco Maldonado – DBA (Administrador de Base de Datos)
* **Estructura:** Diseñó el esquema en 3ra Forma Normal para evitar redundancia.
* **Integridad:** Implementó Llaves Foráneas y lógica de actualización automática de stock.

### 3. Ernesto Rivera – Lead Frontend y UX/UI
* **Interactividad:** Consumo de API mediante Fetch JS para una carga dinámica de productos.
* **Diseño:** Interfaz responsiva (Mobile First) con Grid y Flexbox.

### 4. Jesús Lizárraga  – Ingeniero de Hardware e IoT
* **Conectividad:** Configuración de la Raspberry Pi Pico W como cliente HTTP.
* **Hardware:** Programó la respuesta física de indicadores ante nuevos pedidos en el servidor.

### 5. Angeles Gonzalez – QA y Documentación
* **Pruebas:** Ejecución de testing End-to-End para validación de flujos de compra.
* **Technical Writing:** Redacción de manuales técnicos y guía de instalación del entorno.

---
## 🎨 Creatividad e Innovación

Para este proyecto, decidimos implementar soluciones que van más allá de un e-commerce tradicional:

1. **Modelo de Negocio Híbrido:** Implementación de un sistema capaz de gestionar simultáneamente productos físicos (hardware) y productos digitales (archivos STL) con flujos de entrega diferenciados.
2. **Branding de Ecosistema Real:** Integración con el universo "Blink Galaxy", utilizando modelos y conceptos de un entorno de gaming/Web3 real para dar coherencia visual y comercial.
3. **Notificaciones de Hardware (IoT):** Uso de la Raspberry Pi Pico W como dispositivo de alerta temprana, notificando físicamente al equipo de manufactura cuando se registra una transacción en el backend.
