<?php
session_start();
require 'config.php';

try {
    $stmt = $pdo->query("SELECT id, nombre, categoria, imagen, precio, stock, descripcion, tipo, formato_digital FROM productos");
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al conectar con la base de datos: " . $e->getMessage());
}

$catsHardwareRefacciones = ['Impresora FDM', 'Impresora Resina', 'Refacciones'];
$catFilamento = 'Filamento';
$catBlink = 'Blink Galaxy Forge';

function pf_fmt_mxn(float $n): string
{
    return '$' . number_format($n, 2, '.', ',') . ' MXN';
}

function pf_escape(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function pf_filter_rows(array $rows, callable $fn): array
{
    return array_values(array_filter($rows, $fn, ARRAY_FILTER_USE_BOTH));
}

$hardwareRefacciones = pf_filter_rows($productos, static function ($p) use ($catsHardwareRefacciones) {
    return in_array($p['categoria'], $catsHardwareRefacciones, true);
});

$filamentos = pf_filter_rows($productos, static function ($p) use ($catFilamento) {
    return $p['categoria'] === $catFilamento;
});

$catOuterRing = 'Outer Ring';

$stlGenericos = pf_filter_rows($productos, static function ($p) use ($catBlink, $catOuterRing) {
    return $p['tipo'] === 'digital' && $p['categoria'] !== $catBlink && $p['categoria'] !== $catOuterRing;
});

$blinkGalaxy = pf_filter_rows($productos, static function ($p) use ($catBlink) {
    return $p['categoria'] === $catBlink;
});

$outerRing = pf_filter_rows($productos, static function ($p) use ($catOuterRing) {
    return $p['categoria'] === $catOuterRing;
});

function pf_stars(float $r): string {
    $full  = floor($r);
    $half  = ($r - $full) >= 0.5 ? 1 : 0;
    $empty = 5 - $full - $half;
    return str_repeat('<span class="star full">★</span>', (int)$full)
         . ($half ? '<span class="star half">★</span>' : '')
         . str_repeat('<span class="star empty">★</span>', (int)$empty);
}

function pf_render_product_card(array $p): void
{
    $isBlink  = $p['categoria'] === 'Blink Galaxy Forge';
    $isOuter  = $p['categoria'] === 'Outer Ring';
    $cardClass = 'product-card';
    $btnClass  = 'add-to-cart-btn';
    if ($isBlink) { $cardClass .= ' blink-card'; $btnClass .= ' blink-btn'; }
    if ($isOuter) { $cardClass .= ' outer-card'; $btnClass .= ' outer-btn'; }

    $tipo     = $p['tipo'];
    $stock    = (int) $p['stock'];
    $delivery = $tipo === 'fisico'
        ? '📦 Envío estándar o recolección en Tijuana.'
        : '⚡ Descarga inmediata sin envío.';
    $formato  = !empty($p['formato_digital'])
        ? '<p class="product-formato">📄 ' . pf_escape((string)$p['formato_digital']) . '</p>'
        : '';

    // Stock urgency
    $stockHtml = '';
    if ($stock <= 0) {
        $stockHtml = '<p class="product-stock stock-out">❌ Sin stock</p>';
    } elseif ($stock <= 5) {
        $stockHtml = '<p class="product-stock stock-low">🔥 ¡Solo quedan ' . $stock . '!</p>';
    } else {
        $stockHtml = '<p class="product-stock stock-ok">✔ En stock</p>';
    }

    // Ratings
    $rating  = isset($p['rating'])  ? (float) $p['rating']  : 4.5;
    $reviews = isset($p['reviews']) ? (int)   $p['reviews'] : 0;
    $starsHtml = '<div class="product-rating">'
               . pf_stars($rating)
               . '<span class="rating-value">' . number_format($rating,1) . '</span>'
               . '<span class="rating-count">(' . $reviews . ')</span>'
               . '</div>';

    // 3D Specs
    $specsHtml = '';
    if (!empty($p['specs']) && is_array($p['specs'])) {
        $s = $p['specs'];
        $specsHtml = '<div class="print-specs">'
                   . '<span class="spec-item">⏱ ' . pf_escape($s['tiempo'] ?? '') . '</span>'
                   . '<span class="spec-item">🧵 ' . pf_escape($s['material'] ?? '') . '</span>'
                   . '<span class="spec-item">🔩 Soportes: ' . pf_escape($s['soportes'] ?? '') . '</span>'
                   . '<span class="spec-item">📐 ' . pf_escape($s['escala'] ?? '') . '</span>'
                   . '</div>';
    }

    // Quick-view data attrs (JSON-encode for JS modal)
    $qvData = htmlspecialchars(json_encode([
        'id'       => $p['id'],
        'nombre'   => $p['nombre'],
        'imagen'   => $p['imagen'],
        'precio'   => pf_fmt_mxn((float)$p['precio']),
        'desc'     => $p['descripcion'],
        'rating'   => $rating,
        'reviews'  => $reviews,
        'delivery' => $delivery,
        'specs'    => $p['specs'] ?? null,
        'tipo'     => $tipo,
        'formato'  => $p['formato_digital'] ?? '',
    ], JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8');

    echo '<article class="' . pf_escape($cardClass) . '" data-precio="' . $p['precio'] . '" data-categoria="' . pf_escape((string)$p['categoria']) . '" data-stock="' . $stock . '" data-tipo="' . pf_escape($tipo) . '">';
    echo '<span class="chip chip-categoria">' . pf_escape((string)$p['categoria']) . '</span>';
    echo '<div class="product-image">';
    echo '<img src="' . pf_escape((string)$p['imagen']) . '" alt="' . pf_escape((string)$p['nombre']) . '" loading="lazy" width="800" height="600">';
    echo '<button type="button" class="quick-view-btn" data-product=\'' . $qvData . '\'>👁 Vista rápida</button>';
    echo '</div>';
    echo '<div class="product-info">';
    echo '<h3 class="product-title">' . pf_escape((string)$p['nombre']) . '</h3>';
    echo $starsHtml;
    echo '<p class="product-price">' . pf_fmt_mxn((float)$p['precio']) . '</p>';
    echo $stockHtml;
    echo '<p class="product-desc">' . pf_escape((string)$p['descripcion']) . '</p>';
    echo $specsHtml;
    echo $formato;
    echo '<p class="product-delivery">' . pf_escape($delivery) . '</p>';
    echo '<div class="card-actions">';
    echo '<button type="button" class="' . pf_escape($btnClass) . '" data-id="' . pf_escape((string)$p['id']) . '" data-tipo="' . pf_escape($tipo) . '" data-nombre="' . pf_escape((string)$p['nombre']) . '" data-precio="' . pf_fmt_mxn((float)$p['precio']) . '" data-img="' . pf_escape((string)$p['imagen']) . '"><span>Añadir al carrito</span></button>';
    echo '</div>';
    echo '</div></article>';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="PrintForge — El marketplace maker para impresión 3D. Impresoras, filamentos, refacciones y archivos STL digitales.">
    <title>PrintForge — El Marketplace Maker</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <!-- ===== NAVBAR ===== -->
    <header class="navbar">
        <div class="nav-logo">
            <h2>🔥 PrintForge</h2>
        </div>
        <div class="nav-search">
            <input type="text" id="search-input" placeholder="Buscar filamentos, refacciones, modelos STL...">
            <button type="submit">🔍</button>
        </div>
        <div class="nav-actions">
            <?php if(isset($_SESSION['usuario_id'])): ?>
                <span style="color: var(--accent); font-weight: 600; margin-right: 15px;">👋 Hola, <?= htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Maker') ?></span>
                
                <button class="nav-auth-btn btn-biblioteca" id="btn-biblioteca" type="button" style="display:inline-block;">📦 Mi Biblioteca</button>
                
                <a href="logout.php" class="nav-auth-btn btn-login" style="text-decoration:none;">Cerrar sesión</a>
            
            <?php else: ?>
                <button class="nav-auth-btn btn-login" id="btn-open-login" type="button">👤 Iniciar sesión</button>
                <button class="nav-auth-btn btn-signup" id="btn-open-signup" type="button">🚀 Registrarse</button>
                
                <button class="nav-auth-btn btn-biblioteca" id="btn-biblioteca" type="button" style="display:none;">📦 Mi Biblioteca</button>
            <?php endif; ?>

            <div class="nav-cart" id="nav-cart-wrapper">
                <span class="cart-icon">🛒</span>
                <span id="cart-count" class="cart-count">0</span>
                <div class="mini-cart" id="mini-cart">
                    <div class="mini-cart-header">
                        <span>🛒 Carrito</span>
                        <button type="button" class="mini-cart-clear" id="mini-cart-clear">Vaciar</button>
                    </div>
                    <div class="mini-cart-items" id="mini-cart-items">
                        <p class="mini-cart-empty">Tu carrito está vacío</p>
                    </div>
                    <div class="mini-cart-footer">
                        <div class="mini-cart-total">
                            <span>Subtotal</span>
                            <span id="mini-cart-subtotal">$0.00 MXN</span>
                        </div>
                        <a href="cart.php" class="mini-cart-checkout">Ver carrito completo →</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- ===== SUB NAV ===== -->
    <nav class="sub-nav" aria-label="Secciones del marketplace">
        <ul>
            <li><a href="#hardware-refacciones">Hardware y Refacciones</a></li>
            <li><a href="#filamentos">Insumos Filamentos</a></li>
            <li><a href="#archivos-stl">Archivos STL</a></li>
            <li><a href="#blink-galaxy" class="blink-link">✨ Blink Galaxy Forge</a></li>
        </ul>
    </nav>

    <!-- ===== NEWS SLIDER ===== -->
    <section class="news-slider-section" id="news-slider-section" aria-label="Noticias y novedades">

        <div class="news-slider" id="news-slider">

            <!-- SLIDE 1 -->
            <div class="news-slide active" style="--slide-bg: url('img/news_racerloop.png')">
                <div class="news-slide-image" style="background-image: url('img/news_racerloop.png')"></div>
                <div class="news-slide-overlay"></div>
                <div class="news-slide-content">
                    <span class="news-tag tag-racerloop">🏎️ RacerLoop</span>
                    <h2 class="news-slide-title">Temporada Nebula Cup — <span>Ya disponible</span></h2>
                    <p class="news-slide-desc">Las naves ganadoras del campeonato galáctico ahora en STL. Imprime la Viper X y la Phantom Mk-II en tu propia impresora.</p>
                    <a href="#blink-galaxy" class="news-slide-cta">Ver modelos →</a>
                </div>
            </div>

            <!-- SLIDE 2 -->
            <div class="news-slide" style="--slide-bg: url('img/news_outerring.png')">
                <div class="news-slide-image" style="background-image: url('img/news_outerring.png')"></div>
                <div class="news-slide-overlay" style="background: linear-gradient(135deg, rgba(0,255,136,0.08), rgba(0,0,0,0.75) 60%)"></div>
                <div class="news-slide-content">
                    <span class="news-tag tag-outerring">⚔️ Outer Ring</span>
                    <h2 class="news-slide-title">Actualización 2.4 — <span>Siege of Silver City</span></h2>
                    <p class="news-slide-desc">3 nuevos guerreros Earthling llegan al catálogo. Alta fidelidad al juego, soportes optimizados y listo para resina o PLA.</p>
                    <a href="#blink-galaxy" class="news-slide-cta">Ver guerreros →</a>
                </div>
            </div>

            <!-- SLIDE 3 -->
            <div class="news-slide" style="--slide-bg: url('img/news_event.png')">
                <div class="news-slide-image" style="background-image: url('img/news_event.png')"></div>
                <div class="news-slide-overlay" style="background: linear-gradient(135deg, rgba(255,107,0,0.12), rgba(0,0,0,0.75) 60%)"></div>
                <div class="news-slide-content">
                    <span class="news-tag tag-event">📍 Evento Tijuana</span>
                    <h2 class="news-slide-title">Blink Galaxy Fest — <span>Este fin de semana</span></h2>
                    <p class="news-slide-desc">Trae tu figura impresa, participa en el concurso de pintura y gana premios exclusivos. Recolección de pedidos en el evento.</p>
                    <a href="#hardware-refacciones" class="news-slide-cta">Explorar catálogo →</a>
                </div>
            </div>

            <!-- SLIDE 4 -->
            <div class="news-slide" style="--slide-bg: url('img/news_offer.png')">
                <div class="news-slide-image" style="background-image: url('img/news_offer.png')"></div>
                <div class="news-slide-overlay" style="background: linear-gradient(135deg, rgba(255,180,0,0.08), rgba(0,0,0,0.75) 60%)"></div>
                <div class="news-slide-content">
                    <span class="news-tag tag-offer">🔥 Oferta Flash</span>
                    <h2 class="news-slide-title">Casco Piloto Pro — <span>$129 MXN hoy</span></h2>
                    <p class="news-slide-desc">Edición limitada del campeonato Galáctico. Solo quedan 7 unidades a este precio. Incluye STL + licencia de impresión.</p>
                    <a href="#archivos-stl" class="news-slide-cta">Comprar ahora →</a>
                </div>
            </div>

        </div><!-- /.news-slider -->

        <!-- Controls -->
        <button class="ns-arrow ns-prev" id="ns-prev" aria-label="Anterior">&#8592;</button>
        <button class="ns-arrow ns-next" id="ns-next" aria-label="Siguiente">&#8594;</button>

        <!-- Dots -->
        <div class="ns-dots" id="ns-dots">
            <span class="ns-dot active" data-ns="0"></span>
            <span class="ns-dot" data-ns="1"></span>
            <span class="ns-dot" data-ns="2"></span>
            <span class="ns-dot" data-ns="3"></span>
        </div>

    </section><!-- /.news-slider-section -->

    <!-- ===== HERO ===== -->
    <section class="hero">
        <span class="hero-badge">🎯 Nuevo: Blink Galaxy Forge ya disponible</span>
        <h1>Tu universo <span class="gradient-text">maker</span><br>empieza aquí</h1>
        <p>Impresoras 3D, filamentos premium, refacciones y archivos STL — todo en un solo marketplace para creadores.</p>
        <a href="#hardware-refacciones" class="hero-cta">Explorar catálogo →</a>
    </section>

    <!-- ===== MAIN CONTENT ===== -->
    <main class="container">

        <!-- Mobile filter toggle (outside layout) -->
        <button type="button" class="filter-toggle" id="filter-toggle">🔍 Filtrar</button>

        <!-- ===== CATALOG LAYOUT: sidebar + content side by side ===== -->
        <div class="catalog-layout">

            <!-- FILTER SIDEBAR -->
            <aside class="filter-sidebar" id="filter-sidebar">
                <div class="filter-header">
                    <h3>🔍 Filtros</h3>
                    <button type="button" class="filter-close" id="filter-close">✕</button>
                </div>

                <div class="filter-group">
                    <h4>Categoría</h4>
                    <label class="filter-check active" data-cat="all">
                        <input type="radio" name="cat-filter" value="all" checked> Todas
                    </label>
                    <label class="filter-check" data-cat="Impresora FDM">
                        <input type="radio" name="cat-filter" value="Impresora FDM"> Impresoras FDM
                    </label>
                    <label class="filter-check" data-cat="Impresora Resina">
                        <input type="radio" name="cat-filter" value="Impresora Resina"> Impresoras Resina
                    </label>
                    <label class="filter-check" data-cat="Filamento">
                        <input type="radio" name="cat-filter" value="Filamento"> Filamentos
                    </label>
                    <label class="filter-check" data-cat="Refacciones">
                        <input type="radio" name="cat-filter" value="Refacciones"> Refacciones
                    </label>
                    <label class="filter-check" data-cat="Blink Galaxy Forge">
                        <input type="radio" name="cat-filter" value="Blink Galaxy Forge"> ✨ Blink Galaxy
                    </label>
                    <label class="filter-check" data-cat="Outer Ring">
                        <input type="radio" name="cat-filter" value="Outer Ring"> ⚔️ Outer Ring
                    </label>
                </div>

                <div class="filter-group">
                    <h4>Rango de Precio</h4>
                    <div class="price-range">
                        <div class="price-inputs">
                            <input type="number" id="price-min" placeholder="Min" min="0" step="50">
                            <span>—</span>
                            <input type="number" id="price-max" placeholder="Max" min="0" step="50">
                        </div>
                        <button type="button" class="price-apply" id="price-apply">Aplicar</button>
                    </div>
                </div>

                <div class="filter-group">
                    <h4>Disponibilidad</h4>
                    <label class="filter-check">
                        <input type="checkbox" id="filter-stock"> Solo con stock (evento Tijuana)
                    </label>
                    <label class="filter-check">
                        <input type="checkbox" id="filter-digital"> Solo digitales (descarga inmediata)
                    </label>
                </div>

                <button type="button" class="filter-reset" id="filter-reset">↺ Limpiar filtros</button>
            </aside>

            <!-- MAIN CATALOG -->
            <div class="catalog-main" id="catalog-main">


        <section id="hardware-refacciones" class="category-section">
            <h2>Hardware y Refacciones <span>(Envío estándar o recolección en Tijuana)</span></h2>
            <hr class="section-divider">
            <div class="product-grid">
                <?php foreach ($hardwareRefacciones as $p) {
                    pf_render_product_card($p);
                } ?>
            </div>
        </section>

        <section id="filamentos" class="category-section">
            <h2>Insumos Filamentos <span>(Envío estándar o recolección en Tijuana)</span></h2>
            <hr class="section-divider">
            <div class="product-grid">
                <?php foreach ($filamentos as $p) {
                    pf_render_product_card($p);
                } ?>
            </div>
        </section>

        <section id="archivos-stl" class="category-section">
            <h2>Archivos Digitales STL <span>(Descarga inmediata sin envío)</span></h2>
            <hr class="section-divider">
        <?php if (count($stlGenericos) === 0): ?>
                <p class="empty-note">Por ahora no hay archivos STL genéricos en el catálogo. Los modelos exclusivos Blink Galaxy aparecen en su sección.</p>
            <?php else: ?>
                <div class="product-grid">
                    <?php foreach ($stlGenericos as $p) {
                        pf_render_product_card($p);
                    } ?>
                </div>
            <?php endif; ?>
        </section>

        <section id="blink-galaxy" class="category-section blink-section">
            <!-- ===== GAME SWITCHER SLIDER ===== -->
            <div class="game-switcher">
                <div class="game-switcher-header">
                    <span class="blink-badge" style="margin-bottom:0;">🎮 POWERED BY BLINK GALAXY</span>
                    <h2 class="blink-title" style="margin-bottom:0; font-size: clamp(1.4rem,3vw,2rem);">Forge <span>Collection</span></h2>
                </div>
                <div class="game-slider-track" id="game-slider">
                    <button class="game-slide active" data-game="racerloop" type="button">
                        <img src="img/blink-banner.png" alt="RacerLoop" class="game-slide-bg" loading="lazy">
                        <div class="game-slide-overlay"></div>
                        <div class="game-slide-info">
                            <span class="game-slide-tag">🏎️ ARCADE RACING</span>
                            <h3>RacerLoop</h3>
                            <p>Carreras cósmicas de gravedad cero</p>
                        </div>
                        <div class="game-slide-indicator"></div>
                    </button>
                    <button class="game-slide" data-game="outerring" type="button">
                        <img src="img/outer-banner.png" alt="Outer Ring" class="game-slide-bg" loading="lazy">
                        <div class="game-slide-overlay"></div>
                        <div class="game-slide-info">
                            <span class="game-slide-tag">⚔️ MMORPG</span>
                            <h3>Outer Ring</h3>
                            <p>MMORPG de acción blockchain</p>
                        </div>
                        <div class="game-slide-indicator"></div>
                    </button>
                </div>
            </div>

            <!-- ===== RACERLOOP PANEL ===== -->
            <div class="game-panel active" id="panel-racerloop">
                <div class="game-panel-header">
                    <div>
                        <h3 class="game-panel-title">RacerLoop <span>Forge Collection</span></h3>
                        <p class="game-panel-desc">Naves, pilotos, trofeos y circuitos del juego de carreras cósmicas más intenso. Modelos 3D oficiales listos para imprimir.</p>
                    </div>
                    <div class="blink-stats" style="animation:none;">
                        <div class="blink-stat"><strong>5</strong><span>Modelos</span></div>
                        <div class="blink-stat"><strong>.STL</strong><span>Formato</span></div>
                        <div class="blink-stat"><strong>⚡</strong><span>Descarga</span></div>
                    </div>
                </div>
                <div class="blink-features">
                    <div class="blink-feature-card">
                        <span class="blink-feature-icon">🚀</span>
                        <h4>Naves oficiales</h4>
                        <p>Réplicas fieles de las naves de RacerLoop con detalles de producción del juego.</p>
                    </div>
                    <div class="blink-feature-card">
                        <span class="blink-feature-icon">🎯</span>
                        <h4>Pre-soportado</h4>
                        <p>Todos los modelos incluyen soportes optimizados para FDM y resina.</p>
                    </div>
                    <div class="blink-feature-card">
                        <span class="blink-feature-icon">🔗</span>
                        <h4>Coleccionables Web3</h4>
                        <p>Cada compra incluye un NFT de autenticidad en la blockchain de Blink Galaxy.</p>
                    </div>
                </div>
                <h3 class="blink-catalog-title">Catálogo RacerLoop</h3>
                <hr class="section-divider" style="background: linear-gradient(90deg, #b300ff, #00e5ff, transparent); margin-left:24px; margin-right:24px;">
                <div class="product-grid" style="padding:0 24px;">
                    <?php foreach ($blinkGalaxy as $p) {
                        pf_render_product_card($p);
                    } ?>
                </div>
            </div>

            <!-- ===== OUTER RING PANEL ===== -->
            <div class="game-panel" id="panel-outerring">
                <div class="game-panel-header outer-theme">
                    <div>
                        <h3 class="game-panel-title outer-text">Outer Ring <span>Forge Collection</span></h3>
                        <p class="game-panel-desc">Guerreros, armas legendarias, criaturas y escenarios del MMORPG blockchain más épico. Imprime tu arsenal del universo Outer Ring.</p>
                    </div>
                    <div class="blink-stats outer-stats" style="animation:none;">
                        <div class="blink-stat outer-stat"><strong>5</strong><span>Modelos</span></div>
                        <div class="blink-stat outer-stat"><strong>.STL</strong><span>Formato</span></div>
                        <div class="blink-stat outer-stat"><strong>⚔️</strong><span>PvP Ready</span></div>
                    </div>
                </div>
                <div class="blink-features">
                    <div class="blink-feature-card outer-feature">
                        <span class="blink-feature-icon">⚔️</span>
                        <h4>Armas legendarias</h4>
                        <p>Réplicas de las armas NFT más codiciadas del marketplace de Outer Ring.</p>
                    </div>
                    <div class="blink-feature-card outer-feature">
                        <span class="blink-feature-icon">🐉</span>
                        <h4>Criaturas & Bosses</h4>
                        <p>Enemigos de mazmorra para tu colección o mesa de rol. Alta fidelidad al juego.</p>
                    </div>
                    <div class="blink-feature-card outer-feature">
                        <span class="blink-feature-icon">🏰</span>
                        <h4>Dioramas del mundo</h4>
                        <p>Escenarios icónicos como Silver City y planetas alienígenas en miniatura.</p>
                    </div>
                </div>
                <h3 class="blink-catalog-title outer-text">Catálogo Outer Ring</h3>
                <hr class="section-divider" style="background: linear-gradient(90deg, #00ff88, #00c9a7, transparent); margin-left:24px; margin-right:24px;">
                <div class="product-grid" style="padding:0 24px;">
                    <?php foreach ($outerRing as $p) {
                        pf_render_product_card($p);
                    } ?>
                </div>
            </div>
        </section>

        </div><!-- /.catalog-main -->
        </div><!-- /.catalog-layout -->

    </main>


    <!-- ===== AUTH MODAL ===== -->
    <div class="modal-overlay" id="auth-modal">
        <div class="auth-modal">
            <button class="modal-close" id="modal-close" type="button">&times;</button>

            <div class="auth-header">
                <h2>Bienvenido a <span style="background: linear-gradient(135deg, #FF6B00, #ff9a44); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">PrintForge</span></h2>
                <p>Accede a tu cuenta o crea una nueva</p>
            </div>

            <div class="auth-tabs">
                <button class="auth-tab active" data-tab="login" type="button">Iniciar sesión</button>
                <button class="auth-tab" data-tab="signup" type="button">Crear cuenta</button>
            </div>

            <!-- LOGIN FORM -->
            <form class="auth-form active" id="form-login" action="login.php" method="POST">
                <div class="form-group">
                    <label for="login-email">Correo electrónico</label>
                    <input type="email" id="login-email" name="email" placeholder="tu@correo.com" required>
                </div>
                <div class="form-group">
                    <label for="login-password">Contraseña</label>
                    <input type="password" id="login-password" name="password" placeholder="••••••••" required>
                </div>
                <button type="submit" class="auth-submit">Iniciar sesión</button>
                <div class="auth-divider">o continúa con</div>
                <div class="social-login">
                    <button type="button" class="social-btn">🔵 Google</button>
                    <button type="button" class="social-btn">⚫ GitHub</button>
                </div>
            </form>

            <!-- SIGNUP FORM -->
            <form class="auth-form" id="form-signup" action="register.php" method="POST">
                <div class="form-group">
                    <label for="signup-name">Nombre completo</label>
                    <input type="text" id="signup-name" name="name" placeholder="Tu nombre" required>
                </div>
                <div class="form-group">
                    <label for="signup-email">Correo electrónico</label>
                    <input type="email" id="signup-email" name="email" placeholder="tu@correo.com" required>
                </div>
                <div class="form-group">
                    <label for="signup-password">Contraseña</label>
                    <input type="password" id="signup-password" name="password" placeholder="Mínimo 8 caracteres" required minlength="8">
                </div>
                <div class="form-group">
                    <label for="signup-confirm">Confirmar contraseña</label>
                    <input type="password" id="signup-confirm" name="confirm" placeholder="Repite tu contraseña" required minlength="8">
                </div>
                <button type="submit" class="auth-submit">Crear cuenta</button>
                <div class="auth-divider">o continúa con</div>
                <div class="social-login">
                    <button type="button" class="social-btn">🔵 Google</button>
                    <button type="button" class="social-btn">⚫ GitHub</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ===== QUICK VIEW MODAL ===== -->
    <div class="modal-overlay" id="quick-view-modal">
        <div class="quick-view-dialog" role="dialog" aria-modal="true" aria-labelledby="qv-title">
            <button class="modal-close" id="qv-close" type="button">&times;</button>
            <div class="qv-body">
                <div class="qv-image">
                    <img id="qv-img" src="" alt="" loading="lazy">
                </div>
                <div class="qv-info">
                    <span id="qv-categoria" class="chip chip-categoria"></span>
                    <h2 id="qv-title"></h2>
                    <div id="qv-stars" class="product-rating"></div>
                    <p class="product-price" id="qv-precio"></p>
                    <p id="qv-desc" class="product-desc" style="margin-bottom:12px;"></p>
                    <div id="qv-specs" class="print-specs" style="margin-bottom:12px;"></div>
                    <p id="qv-delivery" class="product-delivery"></p>
                    <button type="button" class="add-to-cart-btn" id="qv-add-cart" data-id="" data-tipo=""><span>Añadir al carrito</span></button>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== MI BIBLIOTECA MODAL ===== -->
    <div class="modal-overlay" id="biblioteca-modal">
        <div class="auth-modal" style="max-width:600px;">
            <button class="modal-close" id="biblioteca-close" type="button">&times;</button>
            <div class="auth-header">
                <h2>📦 Mi <span style="background:linear-gradient(135deg,#FF6B00,#ff9a44);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">Biblioteca</span></h2>
                <p>Tus modelos digitales comprados — descárgalos cuando quieras</p>
            </div>
            <div id="biblioteca-list" style="margin-top:20px;">
                <div class="biblioteca-item">
                    <img src="img/blink-racer-ship.png" alt="Nave RacerLoop Viper" style="width:64px;height:64px;object-fit:cover;border-radius:8px;">
                    <div>
                        <strong style="color:#fff;">Nave RacerLoop Viper</strong>
                        <p style="font-size:.8rem;color:#888;">Comprado hace 2 días</p>
                    </div>
                    <button type="button" class="auth-submit" style="padding:8px 18px;font-size:.85rem;">⬇ Descargar</button>
                </div>
            </div>
            <p style="font-size:.78rem;color:#555;margin-top:20px;text-align:center;">Inicia sesión para ver tu biblioteca completa.</p>
        </div>
    </div>


    <!-- ===== FOOTER ===== -->
    <footer class="site-footer">
        <div class="footer-grid">
            <div class="footer-col">
                <h4>🔥 PrintForge</h4>
                <p>El marketplace maker para creadores, diseñadores e ingenieros. Todo para impresión 3D en un solo lugar.</p>
            </div>
            <div class="footer-col">
                <h4>Catálogo</h4>
                <a href="#hardware-refacciones">Hardware</a>
                <a href="#filamentos">Filamentos</a>
                <a href="#archivos-stl">Archivos STL</a>
                <a href="#blink-galaxy">Blink Galaxy</a>
            </div>
            <div class="footer-col">
                <h4>Soporte</h4>
                <a href="#">Centro de ayuda</a>
                <a href="#">Envíos y devoluciones</a>
                <a href="#">Contacto</a>
            </div>
            <div class="footer-col">
                <h4>Legal</h4>
                <a href="#">Términos de servicio</a>
                <a href="#">Privacidad</a>
                <a href="#">Cookies</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> PrintForge. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="js/app.js"></script>
</body>
</html>
