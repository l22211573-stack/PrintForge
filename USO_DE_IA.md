# 🤖 Reporte Técnico: Integración de Inteligencia Artificial en PrintForge
 
En el marco de las 24 horas del **Hackathon TecNM × SOREDI**, el equipo GAFCG adoptó **Gemini 3 Flash** no solo como una herramienta de consulta, sino como un elemento fundamental de nuestra metodología de desarrollo (Pair-Programming con IA). 
 
Su implementación estratégica nos permitió delegar tareas repetitivas, acelerar la toma de decisiones arquitectónicas y enfocar el esfuerzo humano en la configuración de infraestructura y la lógica de negocio. A continuación, detallamos la aplicación de la IA en las diferentes capas del proyecto:
 
### 1. Ingeniería de Prompts para Datos y Modelado SQL
En un marketplace, contar con datos de prueba realistas es vital para evaluar la interfaz y el rendimiento.
* **Generación del Catálogo Híbrido:** Mediante prompts estructurados con parámetros específicos, la IA generó un archivo `productos.json` completo con 23 artículos listos para producción. Este catálogo incluyó una mezcla coherente de hardware físico (impresoras, refacciones) y activos digitales (modelos STL de Blink Galaxy Forge), incluyendo precios escalados, niveles de stock y descripciones técnicas detalladas. Esto nos ahorró horas de captura manual.
* **Arquitectura de Base de Datos:** Gemini nos asistió en el diseño inicial del esquema relacional para MariaDB. Nos proporcionó recomendaciones sobre los tipos de datos más eficientes para el almacenamiento en la Raspberry Pi 4, así como la correcta estructuración de llaves foráneas (`JOINs`) para mantener la integridad referencial entre las tablas de `usuarios`, `productos`, `pedidos` y `detalle_pedido`.
### 2. Soporte Avanzado en Lógica de Negocio y Ciberseguridad
El backend de PrintForge debía ser ligero pero extremadamente seguro y funcional.
* **Algoritmos de Inventario:** Consultamos a la IA para refinar la lógica transaccional y los Triggers de actualización de inventario. Esto nos aseguró que el sistema descontara automáticamente los productos al procesar una venta, evitando problemas de concurrencia o ventas fantasma de artículos agotados.
* **Estándares de Seguridad Backend:** En lugar de implementar sistemas de encriptación obsoletos, la IA nos guio en la aplicación de las mejores prácticas de ciberseguridad modernas. Implementamos el uso nativo de `password_hash()` en PHP utilizando el algoritmo BCRYPT, garantizando que las credenciales de los usuarios estén protegidas contra ataques de fuerza bruta.
### 3. Decisiones Arquitectónicas Asistidas (Infraestructura y Hardware)
El mayor reto técnico del proyecto fue demostrar que un sistema de e-commerce de producción real puede operar completamente fuera de la nube.
* **Arquitectura de Servidor Autónomo sobre Raspberry Pi 4:** Para lograr nuestro Factor WOW, necesitábamos que una placa ARM de bajo costo sostuviera un stack empresarial completo bajo carga real. La IA analizó nuestro entorno y nos asistió en la decisión de eliminar toda dependencia de proveedores cloud, recomendando en su lugar una arquitectura donde la Raspberry Pi 4 opera como un **nodo híbrido descentralizado**: servidor Apache, motor de base de datos MariaDB y runtime PHP 8 corriendo de forma nativa en ARM, con acceso de red local de latencia mínima. Esta decisión demostró que la soberanía tecnológica es alcanzable con hardware accesible.
### 4. Technical Writing y Comunicación Estratégica
Un código excelente necesita una presentación excelente.
* **Documentación (README):** Utilizamos la IA para estructurar y dar formato profesional a nuestra documentación técnica. Nos ayudó a aplicar correctamente la sintaxis de Markdown, integrar insignias visuales (badges) y organizar las instrucciones de despliegue para que cualquier desarrollador pueda montar el proyecto en minutos.
* **Soporte de Pitch para el Jurado:** Gemini fue clave para estructurar la narrativa de nuestra demostración en vivo. Nos ayudó a redactar un guion que resalta la propuesta de valor híbrida, la integración del ecosistema Web3 de SOREDI y el despliegue de infraestructura soberana sobre Raspberry Pi 4, optimizando nuestro tiempo para los 3 minutos de presentación.
---
 
> ⚠️ **Nota de Integridad Técnica y Calidad:**
> En GAFCG entendemos a la Inteligencia Artificial como un copiloto de desarrollo, no como un sustituto del criterio de ingeniería. **Absolutamente todo el código, las consultas SQL, las configuraciones de red y las sugerencias arquitectónicas proporcionadas por Gemini fueron analizadas, auditadas y ajustadas manualmente por los integrantes del equipo.** Nos aseguramos rigurosamente de que cada línea de código fuera segura y se ejecutara con un rendimiento óptimo en el entorno físico y limitado de nuestra Raspberry Pi 4.
