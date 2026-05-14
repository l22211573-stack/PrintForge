<div align="center">
  
# 🔥 PrintForge
### *Where Physical Maker Meets the Digital Frontier*
 
**Marketplace Híbrido 3D — Hackathon TecNM × SOREDI**
 
---
 
![PHP](https://img.shields.io/badge/PHP-8.0-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MariaDB](https://img.shields.io/badge/MariaDB-003545?style=for-the-badge&logo=mariadb&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![CSS3](https://img.shields.io/badge/CSS3-Mobile_First-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![Apache](https://img.shields.io/badge/Apache-2.4-D22128?style=for-the-badge&logo=apache&logoColor=white)
![Raspberry Pi](https://img.shields.io/badge/Raspberry_Pi_4-Server-A22846?style=for-the-badge&logo=raspberry-pi&logoColor=white)
![IoT](https://img.shields.io/badge/Pico_W-IoT_Client-00B4FF?style=for-the-badge&logo=raspberry-pi&logoColor=white)
![Web3](https://img.shields.io/badge/Web3-SOREDI_Ready-F16822?style=for-the-badge&logo=ethereum&logoColor=white)
 
[![Estado](https://img.shields.io/badge/Estado-Hackathon_Live-brightgreen?style=flat-square)](http://192.168.10.3/printforge)
[![Licencia](https://img.shields.io/badge/Licencia-MIT-blue?style=flat-square)](LICENSE)
[![Equipo](https://img.shields.io/badge/Equipo-GAFCG-ff6b35?style=flat-square)](#-equipo-gafcg)
[![Repo](https://img.shields.io/badge/GitHub-PrintForge-181717?style=flat-square&logo=github)](https://github.com/l22211573-stack/PrintForge)
 
</div>
---
 
## 📌 Tabla de Contenidos
 
- [¿Qué es PrintForge?](#-qué-es-printforge)
- [Arquitectura del Sistema](#-arquitectura-del-sistema)
- [Stack Tecnológico](#-stack-tecnológico)
- [Features Principales](#-features-principales)
- [Estructura del Proyecto](#-estructura-del-proyecto)
- [Instrucciones de Despliegue](#-instrucciones-de-despliegue)
- [Integración IoT](#-integración-iot--raspberry-pi-pico-w)
- [Integración SOREDI / Web3](#-integración-soredi--web3-blink-galaxy-forge)
- [Equipo GAFCG](#-equipo-gafcg)
---
 
## 🚀 ¿Qué es PrintForge?
 
**PrintForge** no es un e-commerce convencional. Es un **marketplace híbrido de doble canal** diseñado para la comunidad maker moderna, desplegado íntegramente sobre hardware embebido real:
 
```
┌─────────────────────────────────────────────────────────┐
│                      PRINTFORGE                         │
│                                                         │
│   🏭 CANAL FÍSICO          🌐 CANAL DIGITAL             │
│   ─────────────────        ────────────────────         │
│   • Impresoras 3D          • Modelos STL (.stl)         │
│   • Filamentos PLA/ABS     • Assets Web3 / NFT-Ready    │
│   • Hardware Maker         • Descarga Inmediata         │
│   • Envío Local            • Blink Galaxy Forge         │
└─────────────────────────────────────────────────────────┘
```
 
> **Misión:** Conectar el hardware maker físico con el ecosistema de activos digitales del metaverso Web3, todo orquestado desde una Raspberry Pi 4.
 
---
 
## 🏗 Arquitectura del Sistema
 
```
                         ┌───────────────────┐
  Usuario / Navegador ──▶│  Apache + PHP 8   │◀── Sin Frameworks
                         │  Raspberry Pi 4   │    Máxima ligereza
                         └────────┬──────────┘
                                  │
                    ┌─────────────▼──────────────┐
                    │         MariaDB             │
                    │  Esquema 3FN + Triggers     │
                    │  Gestión automática stock   │
                    └─────────────────────────────┘
                                  │
              ┌───────────────────┴───────────────────┐
              │                                       │
    ┌─────────▼─────────┐                 ┌──────────▼────────┐
    │  JS Nativo (SPA)  │                 │  Raspberry Pi     │
    │  Fetch API        │                 │  Pico W (IoT)     │
    │  CSS Grid/Flex    │                 │  Alerta Física    │
    └───────────────────┘                 │  HTTP Client      │
                                          └───────────────────┘
```
 
---
 
## 🛠 Stack Tecnológico
 
| Capa | Tecnología | Decisión de Diseño |
|------|-----------|-------------------|
| **Backend** | PHP 8 puro | Sin frameworks — máxima ligereza en ARM |
| **Base de Datos** | MariaDB | Esquema 3FN + triggers automáticos de stock |
| **Frontend** | JS Nativo + CSS3 | Fetch API, Grid/Flexbox, Mobile First |
| **Servidor Web** | Apache 2.4 | Corriendo nativo en Raspberry Pi 4 |
| **Hardware IoT** | Raspberry Pi Pico W | Cliente HTTP para notificaciones físicas |
| **Seguridad** | BCRYPT + Sessions PHP | `password_hash()`, validación server-side |
| **Integración** | SOREDI / Blink Galaxy | Activos STL Web3-ready |
 
---
 
## ✨ Features Principales
 
### 🛒 Carrito Híbrido Inteligente
El corazón diferenciador de PrintForge. La lógica de negocio distingue en tiempo real entre los dos tipos de producto y adapta el flujo de compra automáticamente:
 
```
Producto físico  ──▶  Validar stock  ──▶  Calcular envío  ──▶  Confirmar pedido
Producto digital ──▶  Validar licencia ──▶  Sin envío (₀)  ──▶  URL de descarga
```
 
- Cálculo dinámico de costos de envío únicamente para ítems físicos.
- Generación instantánea de enlace de descarga para archivos STL.
- Sin recarga de página: toda la lógica del carrito vía Fetch API.
---
 
### 🔔 Alertas IoT — Raspberry Pi Pico W
 
Cuando un pedido nuevo se registra en el backend PHP, la Raspberry Pi Pico W reacciona **físicamente**:
 
```
Backend PHP ──▶ Nuevo pedido en BD ──▶ HTTP POST al Pico W ──▶ Indicador físico activo
```
 
Esto simula una cadena de notificación real de producción, llevando el proyecto más allá de lo puramente web.
 
---
 
### 🌌 Integración SOREDI / Web3 — Blink Galaxy Forge
 
Sección oficial dentro del marketplace con modelos STL del universo **Blink Galaxy**:
 
- Naves estelares y guerreros del juego, listos para impresión o uso en metaverso.
- Arquitectura preparada para integración NFT/Web3 futura.
- Primer puente real entre hardware maker físico y activos del ecosistema SOREDI.
---
 
### 🔐 Seguridad Backend
 
- Autenticación con `password_hash()` usando algoritmo **BCRYPT**.
- Gestión de sesiones seguras con PHP Sessions.
- Validaciones server-side en todos los endpoints de la API.
- Triggers de MariaDB para mantener integridad de stock ante concurrencia.
---
 
## 📁 Estructura del Proyecto
 
```
PrintForge/
│
├── 📄 index.php               # Página principal / catálogo
├── 📄 login.php               # Autenticación de usuarios
├── 📄 register.php            # Registro con BCRYPT
├── 📄 cart.php                # Vista del carrito híbrido
├── 📄 checkout.php            # Proceso de compra
├── 📄 logout.php              # Cierre de sesión seguro
│
├── 🔌 api_productos.php       # API REST para catálogo (JSON)
├── 🔌 procesar_carrito_js.php # Endpoint procesamiento de carrito
├── 🔌 admin_pedidos.php       # Panel admin de pedidos
│
├── ⚙️  config.php              # Configuración de BD
├── 🗄️  database.sql            # Esquema completo + datos seed
├── 📊 consultas.sql           # Consultas de análisis
├── 📦 productos.json          # Catálogo en JSON estático
│
├── 🎨 css/                    # Estilos Mobile First
├── ⚡ js/                     # Lógica Fetch API
├── 🖼️  img/                    # Assets visuales
│
└── 📚 README.md               # Este archivo
```
 
---
 
## 🚀 Instrucciones de Despliegue
 
### Prerrequisitos
 
- Raspberry Pi 4 (o cualquier servidor Linux)
- Apache 2.4 instalado y activo
- PHP 8.0+
- MariaDB / MySQL
### Paso 1 — Clonar el repositorio
 
```bash
git clone https://github.com/l22211573-stack/PrintForge.git
cd PrintForge
```
 
### Paso 2 — Importar la base de datos
 
```bash
sudo mysql -u root -p < database.sql
```
 
### Paso 3 — Desplegar en el servidor Apache
 
```bash
sudo cp -r * /var/www/html/printforge/
sudo chown -R www-data:www-data /var/www/html/printforge/
```
 
### Paso 4 — Configurar la conexión
 
Edita `config.php` con tus credenciales locales:
 
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'printforge');
define('DB_USER', 'tu_usuario');
define('DB_PASS', 'tu_password');
```
 
### Paso 5 — Acceder a la aplicación
 
```
🌐 http://192.168.10.3/printforge
```
 
> Disponible para cualquier dispositivo en la misma red local que la Raspberry Pi.
 
---
 
## 📡 Integración IoT — Raspberry Pi Pico W
 
La Raspberry Pi Pico W está programada en MicroPython como **cliente HTTP activo**:
 
```python
# Flujo simplificado del cliente IoT
while True:
    response = urequests.get("http://192.168.10.3/api_pedidos_nuevo")
    if response.json()["nuevo_pedido"]:
        activar_indicador_fisico()   # LED, buzzer, etc.
    time.sleep(POLLING_INTERVAL)
```
 
Este componente eleva el proyecto de una aplicación web a un **sistema físico-digital integrado**, demostrando capacidades de IoT industrial en un entorno de hackathon.
 
---
 
## 🌐 Integración SOREDI / Web3 — Blink Galaxy Forge
 
```
┌──────────────────────────────────────────────┐
│           🌌 BLINK GALAXY FORGE              │
│                                              │
│  Tienda Oficial dentro de PrintForge         │
│                                              │
│  • Nave Estelar Mk-I        [STL] [WEB3]    │
│  • Guerrero Blink Alpha     [STL] [WEB3]    │
│  • Crucero de Combate       [STL] [WEB3]    │
│                                              │
│  Imprime el físico. Usa el digital.          │
│  Hardware + Metaverso en un solo checkout.  │
└──────────────────────────────────────────────┘
```
 
---
 
## 👥 Equipo GAFCG
 
> Un equipo multidisciplinario donde cada integrante dominó una capa crítica del sistema.
 
| # | Integrante | Rol | Responsabilidades Clave |
|---|-----------|-----|------------------------|
| 🔧 | **Axel Vallejo** | Arquitecto Backend & Seguridad | Lógica híbrida PHP, validaciones, fallbacks, BCRYPT, sesiones seguras |
| 🗄️ | **Francisco Maldonado** | DBA | Diseño en 3ra Forma Normal, triggers de stock, integridad referencial |
| 🎨 | **Ernesto Rivera** | Lead Frontend & UI/UX | Fetch API, diseño responsivo Mobile First, CSS Grid/Flexbox |
| 📡 | **Jesús Lizárraga** | Ingeniero IoT & Redes | Setup Raspberry Pi 4 como servidor, programación Pico W cliente HTTP |
| ✅ | **Angeles Gonzalez** | QA & Project Manager | Testing E2E, documentación técnica, gestión de entregables |
 
---
 
## 🏆 ¿Por qué PrintForge destaca en el Hackathon?
 
```
✅  Arquitectura real desplegada en hardware físico (no cloud)
✅  Sistema de doble canal (físico + digital) en un solo carrito
✅  Integración IoT funcional con Raspberry Pi Pico W
✅  Bridge entre maker hardware y ecosistema Web3 / SOREDI
✅  PHP 8 puro: cero dependencias externas, cero overhead
✅  BD normalizada en 3FN con triggers automáticos de stock
✅  Seguridad implementada: BCRYPT + sessions + validaciones
✅  Frontend 100% responsivo: Mobile First, sin librerías CSS
```
 
---
 
<div align="center">
**Construido con 🔥 durante el Hackathon TecNM × SOREDI**
 
*Equipo GAFCG — PrintForge © 2026*
 
[![GitHub](https://img.shields.io/badge/Ver_Repositorio-181717?style=for-the-badge&logo=github&logoColor=white)](https://github.com/l22211573-stack/PrintForge)
 
</div>
