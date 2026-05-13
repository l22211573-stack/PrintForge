# 🛠️ PrintForge - Marketplace Híbrido 3D

PrintForge es un marketplace de impresión 3D montado en una Raspberry Pi 4. Diseñado para la comunidad maker, maneja una arquitectura híbrida: venta de productos físicos (impresoras, filamentos) con envío local, y venta de activos digitales (modelos STL) con descarga inmediata.

## 🚀 Características
- **Carrito Híbrido:** Lógica de envíos inteligente (físico vs digital).
- **Backend Ligero:** PHP 8 puro y MariaDB (sin frameworks pesados).
- **Integración Web3 (Bonus):** Sección "Blink Galaxy Forge" con modelos STL oficiales del juego.

## ⚙️ Instrucciones de Instalación (Entorno Local en Raspberry Pi)
1. **Clonar el repositorio:** `git clone https://github.com/tu-usuario/printforge.git`
2. **Base de Datos:** Importar el archivo `database.sql` en MariaDB (`sudo mysql -u root -p`).
3. **Servidor Web:** Mover los archivos a `/var/www/html/printforge`.
4. **Acceso:** Entrar desde cualquier dispositivo en la misma red a la IP de la Raspberry Pi.
