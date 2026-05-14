Reporte de Uso de Inteligencia Artificial - PrintForge

Este proyecto integró **Gemini 3 Flash** como asistente de desarrollo para optimizar los tiempos de entrega y asegurar la calidad del código y la documentación. A continuación, se detallan las áreas donde la IA fue fundamental:

1. Ingeniería de Prompts para Datos
Generación de Catálogo: Se utilizaron prompts estructurados para generar un archivo `productos.json` con 23 artículos coherentes, incluyendo categorías híbridas (físicas y digitales), precios y descripciones técnicas.
Estructura SQL: La IA asistió en la creación del esquema de base de datos inicial, sugiriendo relaciones entre tablas (`JOINs`) y tipos de datos óptimos para MariaDB.

2. Soporte en Lógica de Negocio
Algoritmo de Stock: Se consultó a la IA para definir la lógica de actualización de inventario, asegurando que el sistema descontara productos automáticamente al procesar una venta.
Seguridad: La IA proporcionó las mejores prácticas para el uso de `password_hash()` en PHP, garantizando que el sistema cumpliera con estándares modernos de ciberseguridad.

3. Documentación y Comunicación
README Profesional: El contenido técnico de la documentación fue estructurado con ayuda de IA para mejorar la claridad y el impacto visual mediante Markdown.
Soporte de Presentación: Generación de la estructura del pitch y guion técnico para la demostración en vivo.

4. Decisiones Asistidas
La IA sugirió la arquitectura para la integración de la Raspberry Pi Pico W, recomendando el uso de una arquitectura de "cliente-servidor" mediante peticiones HTTP para que el hardware reaccionara a los pedidos de la base de datos.

> Nota: Todo el código y las sugerencias proporcionadas por la IA fueron revisados, probados y ajustados manualmente por los integrantes del equipo para asegurar su correcto funcionamiento en el entorno de producción de la Raspberry Pi.