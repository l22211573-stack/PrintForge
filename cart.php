<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="PrintForge — Revisa y confirma tu carrito de compras antes de pagar.">
    <title>Mi Carrito — PrintForge</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/cart.css">
</head>
<body>

    <!-- ===== NAVBAR (same as index) ===== -->
    <header class="navbar">
        <div class="nav-logo">
            <a href="index.php" style="text-decoration:none;"><h2>🔥 PrintForge</h2></a>
        </div>
        <div class="nav-search">
            <input type="text" id="search-input" placeholder="Buscar filamentos, refacciones, modelos STL...">
            <button type="submit">🔍</button>
        </div>
        <div class="nav-actions">
            <button class="nav-auth-btn btn-login" id="btn-open-login" type="button">👤 Iniciar sesión</button>
            <button class="nav-auth-btn btn-signup" id="btn-open-signup" type="button">🚀 Registrarse</button>
            <div class="nav-cart" id="nav-cart-wrapper">
                <span class="cart-icon">🛒</span>
                <span id="cart-count" class="cart-count">0</span>
            </div>
        </div>
    </header>

    <!-- ===== CART PAGE ===== -->
    <main class="cart-page">

        <!-- Breadcrumb -->
        <div class="cart-breadcrumb">
            <a href="index.php">🏠 Inicio</a>
            <span>›</span>
            <span class="active">Mi Carrito</span>
        </div>

        <div class="cart-layout">

            <!-- ===== LEFT: Items ===== -->
            <section class="cart-items-section">
                <div class="cart-section-header">
                    <h1>🛒 Mi Carrito</h1>
                    <span class="cart-item-count" id="cart-item-count">0 artículos</span>
                </div>
                <hr class="section-divider">

                <!-- Empty state -->
                <div class="cart-empty" id="cart-empty" style="display:none;">
                    <div class="empty-icon">🛒</div>
                    <h3>Tu carrito está vacío</h3>
                    <p>Agrega productos desde el catálogo para comenzar tu compra.</p>
                    <a href="index.php" class="cart-back-btn">← Explorar catálogo</a>
                </div>

                <!-- Items list -->
                <div id="cart-items-list"></div>

                <!-- Actions bar -->
                <div class="cart-actions-bar" id="cart-actions-bar">
                    <button type="button" class="cart-clear-btn" id="cart-clear-btn">🗑 Vaciar carrito</button>
                    <a href="index.php" class="cart-continue-btn">← Seguir comprando</a>
                </div>
            </section>

            <!-- ===== RIGHT: Order Summary ===== -->
            <aside class="cart-summary">
                <h2>📋 Resumen del pedido</h2>
                <hr class="section-divider">

                <div class="summary-rows">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span id="summary-subtotal">$0.00 MXN</span>
                    </div>
                    <div class="summary-row">
                        <span>Envío</span>
                        <span id="summary-shipping" class="shipping-label">Calculando...</span>
                    </div>
                    <div class="summary-row discount-row" id="discount-row" style="display:none;">
                        <span>🎉 Descuento</span>
                        <span id="summary-discount" class="discount-value">-$0.00 MXN</span>
                    </div>
                </div>

                <div class="summary-coupon">
                    <input type="text" id="coupon-input" placeholder="Código de cupón">
                    <button type="button" id="coupon-apply">Aplicar</button>
                </div>
                <p class="coupon-msg" id="coupon-msg"></p>

                <hr class="section-divider" style="margin: 16px 0;">

                <div class="summary-total">
                    <span>Total</span>
                    <span id="summary-total">$0.00 MXN</span>
                </div>

                <button type="button" class="checkout-btn" id="checkout-btn">
                    🔒 Proceder al pago
                </button>

                <div class="summary-badges">
                    <span class="badge-item">🔒 Pago seguro</span>
                    <span class="badge-item">📦 Recolección Tijuana</span>
                    <span class="badge-item">💾 Descarga digital</span>
                </div>

                <div class="accepted-payments">
                    <p>Métodos de pago aceptados</p>
                    <div class="payment-icons">
                        <span class="pay-icon">💳 Tarjeta</span>
                        <span class="pay-icon">📱 OXXO</span>
                        <span class="pay-icon">🏦 Transferencia</span>
                    </div>
                </div>
            </aside>

        </div><!-- /.cart-layout -->

        <!-- ===== RECOMMENDED SECTION ===== -->
        <section class="cart-recommended">
            <h2>🔥 También te puede interesar</h2>
            <div class="recommended-grid" id="recommended-grid">
                <div class="rec-card">
                    <div class="rec-img" style="background:linear-gradient(135deg,#1a1a3e,#0d0d22);">🏎️</div>
                    <div class="rec-info">
                        <span class="rec-name">Nave Viper X — RacerLoop</span>
                        <span class="rec-price">$129.00 MXN</span>
                        <button type="button" class="rec-add add-to-cart-btn" data-id="101" data-tipo="digital" data-nombre="Nave Viper X" data-precio="$129.00 MXN" data-img=""><span>+ Agregar</span></button>
                    </div>
                </div>
                <div class="rec-card">
                    <div class="rec-img" style="background:linear-gradient(135deg,#0d2e1a,#051408);">⚔️</div>
                    <div class="rec-info">
                        <span class="rec-name">Guerrero Earthling — Outer Ring</span>
                        <span class="rec-price">$99.00 MXN</span>
                        <button type="button" class="rec-add add-to-cart-btn" data-id="102" data-tipo="digital" data-nombre="Guerrero Earthling" data-precio="$99.00 MXN" data-img=""><span>+ Agregar</span></button>
                    </div>
                </div>
                <div class="rec-card">
                    <div class="rec-img" style="background:linear-gradient(135deg,#2e1a00,#180d00);">🧵</div>
                    <div class="rec-info">
                        <span class="rec-name">PLA Negro 1kg — Premium</span>
                        <span class="rec-price">$389.00 MXN</span>
                        <button type="button" class="rec-add add-to-cart-btn" data-id="103" data-tipo="fisico" data-nombre="PLA Negro 1kg" data-precio="$389.00 MXN" data-img=""><span>+ Agregar</span></button>
                    </div>
                </div>
                <div class="rec-card">
                    <div class="rec-img" style="background:linear-gradient(135deg,#2e0d2e,#150015);">🖨️</div>
                    <div class="rec-info">
                        <span class="rec-name">Boquilla Acero MK8 0.4mm</span>
                        <span class="rec-price">$149.00 MXN</span>
                        <button type="button" class="rec-add add-to-cart-btn" data-id="104" data-tipo="fisico" data-nombre="Boquilla Acero MK8" data-precio="$149.00 MXN" data-img=""><span>+ Agregar</span></button>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- ===== CHECKOUT CONFIRM MODAL ===== -->
    <div class="modal-overlay" id="checkout-modal">
        <div class="auth-modal" style="max-width:480px; text-align:center;">
            <button class="modal-close" id="checkout-modal-close" type="button">&times;</button>
            <div style="font-size:3rem; margin-bottom:16px;">🎉</div>
            <h2 style="color:#fff; margin-bottom:8px;">¡Pedido confirmado!</h2>
            <p style="color:#aaa; margin-bottom:20px;">Tu pedido ha sido recibido. Te contactaremos por WhatsApp para coordinar la recolección en Tijuana o el envío digital.</p>
            <div class="order-summary-box" id="order-summary-box"></div>
            <button type="button" class="auth-submit" id="checkout-done" style="margin-top:20px;">Volver al catálogo</button>
        </div>
    </div>

    <!-- ===== FOOTER ===== -->
    <footer class="site-footer" style="margin-top:60px;">
        <div class="footer-grid">
            <div class="footer-col">
                <h4>🔥 PrintForge</h4>
                <p>El marketplace maker para creadores, diseñadores e ingenieros. Todo para impresión 3D en un solo lugar.</p>
            </div>
            <div class="footer-col">
                <h4>Catálogo</h4>
                <a href="index.php#hardware-refacciones">Hardware</a>
                <a href="index.php#filamentos">Filamentos</a>
                <a href="index.php#archivos-stl">Archivos STL</a>
                <a href="index.php#blink-galaxy">Blink Galaxy</a>
            </div>
            <div class="footer-col">
                <h4>Soporte</h4>
                <a href="#">Centro de ayuda</a>
                <a href="#">Envíos y devoluciones</a>
                <a href="#">Contacto</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> PrintForge. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="js/cart-page.js"></script>

    <script>
        // Secuestramos el botón de pago de Gotzu
        document.getElementById('checkout-btn').addEventListener('click', function(e) {
            // 1. Detenemos la simulación visual que hizo Gotzu
            e.preventDefault();
            e.stopPropagation();

            // 2. Leemos el carrito del navegador
            let carritoJS = localStorage.getItem('printforge_cart');

            // Validamos que haya algo que comprar
            if (!carritoJS || carritoJS === "[]" || JSON.parse(carritoJS).length === 0) {
                alert("Tu carrito está vacío. ¡Agrega productos primero!");
                return;
            }

            // 3. Cambiamos el texto para que el cliente sepa que estamos procesando
            this.innerHTML = "⚙️ Conectando con el servidor...";
            this.style.opacity = "0.7";
            this.disabled = true;

            // 4. Enviamos los datos a tu archivo traductor de PHP
            fetch('procesar_carrito_js.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: carritoJS
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === "success") {
                    // 5. ¡ÉXITO! Redirigimos al checkout real que tú programaste
                    window.location.href = 'checkout.php';
                } else {
                    alert("Error en el servidor: " + data.mensaje);
                    this.innerHTML = "🔒 Proceder al pago";
                    this.disabled = false;
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Hubo un problema de conexión con el servidor.");
                this.innerHTML = "🔒 Proceder al pago";
                this.disabled = false;
            });
        }, true); // El 'true' asegura que tu código se ejecute ANTES que el de Gotzu
    </script>
</body>
</html>
</html>
