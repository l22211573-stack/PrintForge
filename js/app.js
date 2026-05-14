(function () {
    'use strict';

    /* ===== CART LOGIC ===== */
    var STORAGE_KEY = 'printforge_cart';

    function loadCart() {
        try {
            var raw = localStorage.getItem(STORAGE_KEY);
            if (!raw) return [];
            var parsed = JSON.parse(raw);
            return Array.isArray(parsed) ? parsed : [];
        } catch (e) {
            return [];
        }
    }

    function saveCart(items) {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(items));
        } catch (e) {
            /* ignore quota / private mode */
        }
    }

    function cartTotalQty(items) {
        return items.reduce(function (sum, row) {
            var q = parseInt(row.cantidad, 10);
            return sum + (isNaN(q) || q < 1 ? 0 : q);
        }, 0);
    }

    function upsertItem(items, id, cantidadAdd) {
        var idStr = String(id);
        var found = false;
        var next = items.map(function (row) {
            if (String(row.id) === idStr) {
                found = true;
                var prev = parseInt(row.cantidad, 10);
                if (isNaN(prev) || prev < 1) prev = 0;
                return { id: row.id, cantidad: prev + cantidadAdd };
            }
            return { id: row.id, cantidad: row.cantidad };
        });
        if (!found) next.push({ id: id, cantidad: cantidadAdd });
        return next;
    }

    /* ===== AUTH MODAL ===== */
    function initAuthModal() {
        var overlay = document.getElementById('auth-modal');
        var closeBtn = document.getElementById('modal-close');
        var btnLogin = document.getElementById('btn-open-login');
        var btnSignup = document.getElementById('btn-open-signup');
        var tabs = document.querySelectorAll('.auth-tab');
        var forms = document.querySelectorAll('.auth-form');

        if (!overlay) return;

        function openModal(tab) {
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
            switchTab(tab || 'login');
        }

        function closeModal() {
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        function switchTab(tabName) {
            tabs.forEach(function (t) {
                t.classList.toggle('active', t.getAttribute('data-tab') === tabName);
            });
            forms.forEach(function (f) {
                f.classList.toggle('active', f.id === 'form-' + tabName);
            });
        }

        if (btnLogin) btnLogin.addEventListener('click', function () { openModal('login'); });
        if (btnSignup) btnSignup.addEventListener('click', function () { openModal('signup'); });
        if (closeBtn) closeBtn.addEventListener('click', closeModal);

        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) closeModal();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && overlay.classList.contains('active')) closeModal();
        });

        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                switchTab(this.getAttribute('data-tab'));
            });
        });

        /* Handle form submissions (demo) */
        var formLogin = document.getElementById('form-login');
        var formSignup = document.getElementById('form-signup');

        /*if (formLogin) {
            formLogin.addEventListener('submit', function (e) {
                e.preventDefault();
                var btn = this.querySelector('.auth-submit');
                btn.textContent = '✓ ¡Bienvenido!';
                btn.style.background = 'linear-gradient(135deg, #22c55e, #16a34a)';
                setTimeout(function () {
                    closeModal();
                    btn.textContent = 'Iniciar sesión';
                    btn.style.background = '';
                    formLogin.reset();
                }, 1500);
            });
        }

        if (formSignup) {
            formSignup.addEventListener('submit', function (e) {
                e.preventDefault();
                var pass = document.getElementById('signup-password').value;
                var confirm = document.getElementById('signup-confirm').value;
                var btn = this.querySelector('.auth-submit');

                if (pass !== confirm) {
                    btn.textContent = '✕ Las contraseñas no coinciden';
                    btn.style.background = 'linear-gradient(135deg, #ef4444, #dc2626)';
                    setTimeout(function () {
                        btn.textContent = 'Crear cuenta';
                        btn.style.background = '';
                    }, 2000);
                    return;
                }

                btn.textContent = '✓ ¡Cuenta creada!';
                btn.style.background = 'linear-gradient(135deg, #22c55e, #16a34a)';
                setTimeout(function () {
                    closeModal();
                    btn.textContent = 'Crear cuenta';
                    btn.style.background = '';
                    formSignup.reset();
                }, 1500);
            });
        }*/
    }

    /* ===== SMOOTH SCROLL ===== */
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(function (a) {
            a.addEventListener('click', function (e) {
                var target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    }

    /* ===== SCROLL ANIMATIONS ===== */
    function initScrollAnimations() {
        var sections = document.querySelectorAll('.category-section');
        if (!('IntersectionObserver' in window)) return;

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        sections.forEach(function (s) {
            s.style.opacity = '0';
            s.style.transform = 'translateY(30px)';
            s.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(s);
        });
    }

    /* ===== GAME SWITCHER ===== */
    function initGameSwitcher() {
        var slides = document.querySelectorAll('.game-slide');
        var panels = document.querySelectorAll('.game-panel');

        if (!slides.length) return;

        slides.forEach(function (slide) {
            slide.addEventListener('click', function () {
                var game = this.getAttribute('data-game');

                /* Update slide active state */
                slides.forEach(function (s) { s.classList.remove('active'); });
                this.classList.add('active');

                /* Switch panels with animation */
                panels.forEach(function (p) {
                    if (p.id === 'panel-' + game) {
                        p.classList.add('active');
                        /* Re-trigger animation */
                        p.style.animation = 'none';
                        p.offsetHeight; /* force reflow */
                        p.style.animation = '';
                    } else {
                        p.classList.remove('active');
                    }
                });
            });
        });
    }

    /* ===== NEWS IMAGE SLIDER ===== */
    function initNewsTicker() { /* kept as alias */ initNewsSlider(); }
    function initNewsSlider() {
        var slides  = document.querySelectorAll('.news-slide');
        var dots    = document.querySelectorAll('.ns-dot');
        var prevBtn = document.getElementById('ns-prev');
        var nextBtn = document.getElementById('ns-next');
        var section = document.getElementById('news-slider-section');
        if (!slides.length) return;

        var current = 0;
        var total   = slides.length;
        var timer   = null;

        function goTo(index) {
            slides[current].classList.remove('active');
            dots[current].classList.remove('active');
            current = ((index % total) + total) % total;
            slides[current].classList.add('active');
            dots[current].classList.add('active');

            /* Restart progress bar animation */
            if (section) {
                section.style.animation = 'none';
                section.offsetHeight; /* reflow */
                section.style.animation = '';
            }
        }

        function next() { goTo(current + 1); }
        function prev() { goTo(current - 1); }

        function start() { timer = setInterval(next, 5000); }
        function reset() { clearInterval(timer); start(); }

        if (prevBtn) prevBtn.addEventListener('click', function () { prev(); reset(); });
        if (nextBtn) nextBtn.addEventListener('click', function () { next(); reset(); });

        dots.forEach(function (dot) {
            dot.addEventListener('click', function () {
                goTo(parseInt(this.getAttribute('data-ns'), 10));
                reset();
            });
        });

        /* Pause on hover */
        if (section) {
            section.addEventListener('mouseenter', function () { clearInterval(timer); });
            section.addEventListener('mouseleave', start);
        }

        start();
    }

    /* ===== MINI CART RENDERER ===== */
    var productMeta = {}; /* populated by add-to-cart clicks */

    function renderMiniCart(cartItems) {
        var itemsEl   = document.getElementById('mini-cart-items');
        var totalEl   = document.getElementById('mini-cart-subtotal');
        if (!itemsEl) return;

        if (cartItems.length === 0) {
            itemsEl.innerHTML = '<p class="mini-cart-empty">Tu carrito está vacío</p>';
            if (totalEl) totalEl.textContent = '$0.00 MXN';
            return;
        }

        var html = '';
        var total = 0;
        cartItems.forEach(function (row) {
            var meta = productMeta[String(row.id)] || {};
            var name  = meta.nombre || 'Producto #' + row.id;
            var price = meta.precio || '$0.00 MXN';
            var img   = meta.img   || '';
            var qty   = parseInt(row.cantidad, 10) || 1;
            /* parse price number */
            var num = parseFloat(String(price).replace(/[^0-9.]/g, '')) || 0;
            total += num * qty;

            html += '<div class="mini-cart-row">';
            if (img) html += '<img src="' + img + '" alt="' + name + '">';
            html += '<div class="mini-cart-row-info">';
            html += '<div class="mini-cart-row-name">' + name + '</div>';
            html += '<div class="mini-cart-row-price">' + price + '</div>';
            html += '<div class="mini-cart-row-qty">Cantidad: ' + qty + '</div>';
            html += '</div>';
            html += '<button type="button" class="mini-cart-row-remove" data-remove="' + row.id + '" title="Eliminar">✕</button>';
            html += '</div>';
        });

        itemsEl.innerHTML = html;
        if (totalEl) totalEl.textContent = '$' + total.toFixed(2) + ' MXN';
    }

    /* ===== QUICK VIEW ===== */
    function initQuickView() {
        var overlay = document.getElementById('quick-view-modal');
        var closeBtn = document.getElementById('qv-close');
        if (!overlay) return;

        function openQV(data) {
            document.getElementById('qv-img').src        = data.imagen || '';
            document.getElementById('qv-img').alt        = data.nombre || '';
            document.getElementById('qv-title').textContent = data.nombre || '';
            document.getElementById('qv-precio').textContent = data.precio || '';
            document.getElementById('qv-desc').textContent   = data.desc  || '';
            document.getElementById('qv-delivery').textContent = data.delivery || '';

            /* categoria chip */
            var catEl = document.getElementById('qv-categoria');
            if (catEl) catEl.textContent = data.categoria || '';

            /* stars */
            var starsEl = document.getElementById('qv-stars');
            if (starsEl) {
                var r = data.rating || 4.5;
                var full = Math.floor(r);
                var half = (r - full) >= 0.5 ? 1 : 0;
                var empty = 5 - full - half;
                var stHtml = '';
                for (var i=0;i<full;i++) stHtml += '<span class="star full">★</span>';
                if (half) stHtml += '<span class="star half">★</span>';
                for (var j=0;j<empty;j++) stHtml += '<span class="star empty">★</span>';
                stHtml += '<span class="rating-value">' + r.toFixed(1) + '</span>';
                stHtml += '<span class="rating-count">(' + (data.reviews||0) + ' reseñas)</span>';
                starsEl.innerHTML = stHtml;
            }

            /* specs */
            var specsEl = document.getElementById('qv-specs');
            if (specsEl) {
                specsEl.innerHTML = '';
                if (data.specs) {
                    var s = data.specs;
                    specsEl.innerHTML = '<span class="spec-item">⏱ ' + (s.tiempo||'') + '</span>'
                        + '<span class="spec-item">🧵 ' + (s.material||'') + '</span>'
                        + '<span class="spec-item">🔩 Soportes: ' + (s.soportes||'') + '</span>'
                        + '<span class="spec-item">📐 ' + (s.escala||'') + '</span>';
                }
            }

            /* add-to-cart btn */
            var qvBtn = document.getElementById('qv-add-cart');
            if (qvBtn) {
                qvBtn.setAttribute('data-id', data.id || '');
                qvBtn.setAttribute('data-tipo', data.tipo || 'digital');
                qvBtn.setAttribute('data-nombre', data.nombre || '');
                qvBtn.setAttribute('data-precio', data.precio || '');
            }

            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeQV() {
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        if (closeBtn) closeBtn.addEventListener('click', closeQV);
        overlay.addEventListener('click', function (e) { if (e.target === overlay) closeQV(); });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeQV(); });

        /* delegate quick-view-btn clicks */
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('.quick-view-btn');
            if (!btn) return;
            try {
                var raw = btn.getAttribute('data-product');
                var data = JSON.parse(raw);
                openQV(data);
            } catch (err) { /* ignore */ }
        });
    }

    /* ===== FILTER SIDEBAR ===== */
    function initFilters() {
        var sidebar   = document.getElementById('filter-sidebar');
        var toggleBtn = document.getElementById('filter-toggle');
        var closeBtn  = document.getElementById('filter-close');
        var resetBtn  = document.getElementById('filter-reset');
        var priceApply = document.getElementById('price-apply');
        var priceMin  = document.getElementById('price-min');
        var priceMax  = document.getElementById('price-max');
        var filterStock   = document.getElementById('filter-stock');
        var filterDigital = document.getElementById('filter-digital');

        if (!sidebar) return;

        /* Mobile toggle */
        if (toggleBtn) toggleBtn.addEventListener('click', function () {
            sidebar.classList.toggle('open');
        });
        if (closeBtn) closeBtn.addEventListener('click', function () {
            sidebar.classList.remove('open');
        });

        var activeCategory = 'all';
        var activePriceMin = null;
        var activePriceMax = null;
        var onlyStock      = false;
        var onlyDigital    = false;

        function applyFilters() {
            var cards = document.querySelectorAll('.product-card');
            cards.forEach(function (card) {
                var cat    = card.getAttribute('data-categoria') || '';
                var price  = parseFloat(card.getAttribute('data-precio')) || 0;
                var stock  = parseInt(card.getAttribute('data-stock'), 10);
                var tipo   = card.getAttribute('data-tipo') || '';

                var show = true;
                if (activeCategory !== 'all' && cat !== activeCategory) show = false;
                if (activePriceMin !== null && price < activePriceMin) show = false;
                if (activePriceMax !== null && price > activePriceMax) show = false;
                if (onlyStock && stock <= 0) show = false;
                if (onlyDigital && tipo !== 'digital') show = false;

                card.classList.toggle('filtered-out', !show);
            });

            /* Update active state on labels */
            document.querySelectorAll('.filter-check[data-cat]').forEach(function (lbl) {
                lbl.classList.toggle('active', lbl.getAttribute('data-cat') === activeCategory);
            });
        }

        /* Category radio */
        document.querySelectorAll('input[name="cat-filter"]').forEach(function (radio) {
            radio.addEventListener('change', function () {
                activeCategory = this.value;
                applyFilters();
            });
        });

        /* Price apply */
        if (priceApply) {
            priceApply.addEventListener('click', function () {
                var min = parseFloat(priceMin.value);
                var max = parseFloat(priceMax.value);
                activePriceMin = isNaN(min) ? null : min;
                activePriceMax = isNaN(max) ? null : max;
                applyFilters();
            });
        }

        /* Availability checkboxes */
        if (filterStock)   filterStock.addEventListener('change', function () { onlyStock = this.checked; applyFilters(); });
        if (filterDigital) filterDigital.addEventListener('change', function () { onlyDigital = this.checked; applyFilters(); });

        /* Reset */
        if (resetBtn) resetBtn.addEventListener('click', function () {
            activeCategory = 'all';
            activePriceMin = null;
            activePriceMax = null;
            onlyStock = false;
            onlyDigital = false;
            if (priceMin)  priceMin.value = '';
            if (priceMax)  priceMax.value = '';
            if (filterStock) filterStock.checked = false;
            if (filterDigital) filterDigital.checked = false;
            document.querySelectorAll('input[name="cat-filter"]').forEach(function (r) {
                r.checked = r.value === 'all';
            });
            applyFilters();
        });
    }

    /* ===== MI BIBLIOTECA ===== */
    function initBiblioteca() {
        var btn = document.getElementById('btn-biblioteca');
        var overlay = document.getElementById('biblioteca-modal');
        var closeBtn = document.getElementById('biblioteca-close');
        if (!btn || !overlay) return;

        btn.addEventListener('click', function () { overlay.classList.add('active'); });
        if (closeBtn) closeBtn.addEventListener('click', function () { overlay.classList.remove('active'); });
        overlay.addEventListener('click', function (e) { if (e.target === overlay) overlay.classList.remove('active'); });
    }

    /* ===== INIT ===== */
    document.addEventListener('DOMContentLoaded', function () {
        var cartCountElement = document.getElementById('cart-count');
        var cartItems = loadCart();
        if (cartCountElement) {
            cartCountElement.textContent = String(cartTotalQty(cartItems));
        }
        renderMiniCart(cartItems);

        /* Mini-cart toggle on click (more reliable than CSS hover) */
        var cartWrapper = document.getElementById('nav-cart-wrapper');
        var miniCartEl  = document.getElementById('mini-cart');
        if (cartWrapper && miniCartEl) {
            cartWrapper.addEventListener('click', function (e) {
                if (e.target.closest('.mini-cart-row-remove') || e.target.closest('.mini-cart-clear') || e.target.closest('.mini-cart-checkout')) return;
                if (!e.target.closest('#mini-cart')) {
                    miniCartEl.style.display = miniCartEl.style.display === 'block' ? 'none' : 'block';
                }
            });
            document.addEventListener('click', function (e) {
                if (!cartWrapper.contains(e.target)) {
                    miniCartEl.style.display = 'none';
                }
            });
        }

        /* Mini-cart remove via delegation */
        document.addEventListener('click', function (e) {
            var removeBtn = e.target.closest('.mini-cart-row-remove');
            if (removeBtn) {
                var id = removeBtn.getAttribute('data-remove');
                cartItems = cartItems.filter(function (r) { return String(r.id) !== String(id); });
                saveCart(cartItems);
                if (cartCountElement) cartCountElement.textContent = String(cartTotalQty(cartItems));
                renderMiniCart(cartItems);
                return;
            }
        });

        /* Mini-cart clear */
        var clearBtn = document.getElementById('mini-cart-clear');
        if (clearBtn) clearBtn.addEventListener('click', function () {
            cartItems = [];
            saveCart(cartItems);
            if (cartCountElement) cartCountElement.textContent = '0';
            renderMiniCart(cartItems);
        });

        /* Use event delegation for cart buttons (works with game panel switching) */
        document.addEventListener('click', function (e) {
            var button = e.target.closest('.add-to-cart-btn');
            if (!button) return;

            var productId    = button.getAttribute('data-id');
            var productTipo  = button.getAttribute('data-tipo');
            var productNombre = button.getAttribute('data-nombre') || '';
            var productPrecio = button.getAttribute('data-precio') || '';
            var productImg   = button.getAttribute('data-img')   || '';
            var spanEl = button.querySelector('span');

            /* Store metadata for mini-cart AND for cart.php persistence */
            if (productId) {
                productMeta[String(productId)] = {
                    nombre: productNombre,
                    precio: productPrecio,
                    img:    productImg,
                    tipo:   productTipo,
                };
                /* Persist to localStorage so cart.php can read it */
                try {
                    var allMeta = JSON.parse(localStorage.getItem('printforge_cart_meta') || '{}');
                    allMeta[String(productId)] = productMeta[String(productId)];
                    localStorage.setItem('printforge_cart_meta', JSON.stringify(allMeta));
                } catch (ex) { /* ignore */ }
            }

            cartItems = upsertItem(cartItems, productId, 1);
            saveCart(cartItems);
            if (cartCountElement) {
                cartCountElement.textContent = String(cartTotalQty(cartItems));
                cartCountElement.style.transform = 'scale(1.4)';
                setTimeout(function () { cartCountElement.style.transition = 'transform 0.3s'; cartCountElement.style.transform = ''; }, 200);
            }
            renderMiniCart(cartItems);

            var originalText = spanEl ? spanEl.textContent : button.textContent;
            if (productTipo === 'fisico') {
                if (spanEl) spanEl.textContent = '✓ Añadido — envío o recolección';
                button.style.borderColor = '#22c55e';
                button.style.background  = 'rgba(34,197,94,0.15)';
                button.style.color       = '#22c55e';
            } else if (productTipo === 'digital') {
                if (spanEl) spanEl.textContent = '✓ Añadido — descarga inmediata';
                button.style.borderColor = '#3b82f6';
                button.style.background  = 'rgba(59,130,246,0.15)';
                button.style.color       = '#3b82f6';
            }
            setTimeout(function () {
                if (spanEl) spanEl.textContent = originalText;
                button.style.borderColor = '';
                button.style.background  = '';
                button.style.color       = '';
            }, 1600);
        });

        initAuthModal();
        initSmoothScroll();
        initScrollAnimations();
        initGameSwitcher();
        initQuickView();
        initFilters();
        initBiblioteca();
        initNewsTicker();
    });
})();
