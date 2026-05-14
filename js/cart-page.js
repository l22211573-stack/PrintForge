(function () {
    'use strict';

    /* ===== STORAGE ===== */
    var STORAGE_KEY = 'printforge_cart';
    var META_KEY    = 'printforge_cart_meta';

    /* Pre-defined product data (fallback when meta not in localStorage) */
    var CATALOG = {
        '1':  { nombre: 'Ender 3',           precio: 5499, tipo: 'fisico',  img: 'img/ender3.jpg'    },
        '2':  { nombre: 'Photon Mono',        precio: 6299, tipo: 'fisico',  img: 'img/photon.jpg'    },
        '3':  { nombre: 'Boquilla Acero MK8', precio: 149,  tipo: 'fisico',  img: ''                  },
        '4':  { nombre: 'PEI Sheet',          precio: 459,  tipo: 'fisico',  img: 'img/pei.jpg'       },
        '5':  { nombre: 'PLA Negro 1kg',      precio: 389,  tipo: 'fisico',  img: 'img/pla.jpg'       },
        '101':{ nombre: 'Nave Viper X',       precio: 129,  tipo: 'digital', img: ''                  },
        '102':{ nombre: 'Guerrero Earthling', precio: 99,   tipo: 'digital', img: ''                  },
        '103':{ nombre: 'PLA Negro 1kg',      precio: 389,  tipo: 'fisico',  img: ''                  },
        '104':{ nombre: 'Boquilla Acero MK8', precio: 149,  tipo: 'fisico',  img: ''                  },
    };

    var COUPONS = {
        'BLINK10':    { label: '10% descuento Blink Galaxy', pct: 10 },
        'TIJUANA15':  { label: '15% descuento evento',       pct: 15 },
        'MAKER20':    { label: '20% makers especial',        pct: 20 },
    };

    /* ===== HELPERS ===== */
    function loadCart() {
        try {
            var r = localStorage.getItem(STORAGE_KEY);
            var parsed = JSON.parse(r);
            return Array.isArray(parsed) ? parsed : [];
        } catch (e) { return []; }
    }
    function saveCart(items) {
        try { localStorage.setItem(STORAGE_KEY, JSON.stringify(items)); } catch(e){}
    }
    function loadMeta() {
        try {
            var r = localStorage.getItem(META_KEY);
            return r ? JSON.parse(r) : {};
        } catch(e) { return {}; }
    }
    function saveMeta(meta) {
        try { localStorage.setItem(META_KEY, JSON.stringify(meta)); } catch(e){}
    }
    function getMeta(id) {
        var meta = loadMeta();
        var m = meta[String(id)] || {};
        var cat = CATALOG[String(id)] || {};
        return {
            nombre: m.nombre || cat.nombre || 'Producto #' + id,
            precio: m.precio ? parsePrecio(m.precio) : (cat.precio || 0),
            tipo:   m.tipo   || cat.tipo   || 'fisico',
            img:    m.img    || cat.img    || '',
        };
    }
    function parsePrecio(p) {
        if (typeof p === 'number') return p;
        return parseFloat(String(p).replace(/[^0-9.]/g, '')) || 0;
    }
    function fmt(n) {
        return '$' + n.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') + ' MXN';
    }
    function totalQty(items) {
        return items.reduce(function(s, r){ return s + (parseInt(r.cantidad,10)||0); }, 0);
    }

    /* ===== STATE ===== */
    var cart = loadCart();
    var activeDiscount = 0;

    /* ===== CART COUNT (navbar) ===== */
    var cartCountEl = document.getElementById('cart-count');
    function updateNavCount() {
        if (cartCountEl) cartCountEl.textContent = String(totalQty(cart));
    }

    /* ===== RENDER ===== */
    function render() {
        cart = loadCart();
        updateNavCount();

        var listEl    = document.getElementById('cart-items-list');
        var emptyEl   = document.getElementById('cart-empty');
        var actionsEl = document.getElementById('cart-actions-bar');
        var countEl   = document.getElementById('cart-item-count');

        if (!listEl) return;

        /* empty state */
        if (cart.length === 0) {
            emptyEl.style.display = 'block';
            actionsEl.style.display = 'none';
            listEl.innerHTML = '';
            updateSummary();
            if (countEl) countEl.textContent = '0 artículos';
            return;
        }

        emptyEl.style.display = 'none';
        actionsEl.style.display = 'flex';
        if (countEl) countEl.textContent = cart.length + ' artículo' + (cart.length !== 1 ? 's' : '');

        var html = '';
        cart.forEach(function (row) {
            var id   = String(row.id);
            var qty  = parseInt(row.cantidad, 10) || 1;
            var meta = getMeta(id);
            var lineTotal = meta.precio * qty;
            var imgHtml = meta.img
                ? '<img src="' + meta.img + '" alt="' + meta.nombre + '" onerror="this.style.display=\'none\';this.nextElementSibling.style.display=\'flex\'">'
                : '';
            var typeLabel = meta.tipo === 'digital'
                ? '<span class="cart-item-type digital">💾 Descarga digital</span>'
                : '<span class="cart-item-type fisico">📦 Envío / Recolección Tijuana</span>';

            html += '<div class="cart-item" data-id="' + id + '">';

            /* Image */
            html += '<div class="cart-item-img">';
            html += imgHtml;
            html += '<div class="no-img" style="' + (meta.img ? 'display:none' : '') + '">🖨️</div>';
            html += '</div>';

            /* Info */
            html += '<div class="cart-item-info">';
            html += '<div class="cart-item-name">' + meta.nombre + '</div>';
            html += typeLabel;
            html += '<div class="cart-qty">';
            html += '<button type="button" class="qty-btn qty-dec" data-id="' + id + '">−</button>';
            html += '<input type="number" class="qty-value" data-id="' + id + '" value="' + qty + '" min="1" max="99">';
            html += '<button type="button" class="qty-btn qty-inc" data-id="' + id + '">+</button>';
            html += '</div>';
            html += '</div>';

            /* Price + remove */
            html += '<div class="cart-item-right">';
            html += '<span class="cart-item-price">' + fmt(lineTotal) + '</span>';
            html += '<button type="button" class="cart-item-remove" data-id="' + id + '">🗑 Eliminar</button>';
            html += '</div>';

            html += '</div>';
        });

        listEl.innerHTML = html;
        updateSummary();
        bindItemEvents();
    }

    /* ===== BIND ITEM EVENTS ===== */
    function bindItemEvents() {
        /* Decrease */
        document.querySelectorAll('.qty-dec').forEach(function (btn) {
            btn.addEventListener('click', function () {
                changeQty(this.getAttribute('data-id'), -1);
            });
        });
        /* Increase */
        document.querySelectorAll('.qty-inc').forEach(function (btn) {
            btn.addEventListener('click', function () {
                changeQty(this.getAttribute('data-id'), 1);
            });
        });
        /* Direct input */
        document.querySelectorAll('.qty-value').forEach(function (inp) {
            inp.addEventListener('change', function () {
                var newQty = parseInt(this.value, 10);
                if (isNaN(newQty) || newQty < 1) newQty = 1;
                setQty(this.getAttribute('data-id'), newQty);
            });
        });
        /* Remove */
        document.querySelectorAll('.cart-item-remove').forEach(function (btn) {
            btn.addEventListener('click', function () {
                removeItem(this.getAttribute('data-id'));
            });
        });
    }

    function changeQty(id, delta) {
        cart = cart.map(function (r) {
            if (String(r.id) === String(id)) {
                var newQ = Math.max(1, (parseInt(r.cantidad,10)||1) + delta);
                return { id: r.id, cantidad: newQ };
            }
            return r;
        });
        saveCart(cart);
        render();
    }
    function setQty(id, qty) {
        cart = cart.map(function (r) {
            if (String(r.id) === String(id)) return { id: r.id, cantidad: qty };
            return r;
        });
        saveCart(cart);
        render();
    }
    function removeItem(id) {
        var row = document.querySelector('.cart-item[data-id="' + id + '"]');
        if (row) {
            row.style.opacity = '0';
            row.style.transform = 'translateX(20px)';
            row.style.transition = 'all 0.3s';
        }
        setTimeout(function () {
            cart = cart.filter(function (r) { return String(r.id) !== String(id); });
            saveCart(cart);
            render();
        }, 300);
    }

    /* ===== SUMMARY ===== */
    function updateSummary() {
        var subtotal = cart.reduce(function (s, row) {
            var qty  = parseInt(row.cantidad,10) || 1;
            var meta = getMeta(String(row.id));
            return s + meta.precio * qty;
        }, 0);

        var hasPhysical = cart.some(function (r) { return getMeta(String(r.id)).tipo === 'fisico'; });
        var shipping = cart.length === 0 ? 0 : (hasPhysical ? 99 : 0);
        var shippingLabel = cart.length === 0
            ? '—'
            : (hasPhysical ? fmt(shipping) : '✅ Gratis (digital)');

        var discount = Math.round(subtotal * activeDiscount / 100);
        var total = subtotal - discount + (hasPhysical && cart.length > 0 ? shipping : 0);

        document.getElementById('summary-subtotal').textContent = fmt(subtotal);
        document.getElementById('summary-shipping').textContent = shippingLabel;
        document.getElementById('summary-shipping').className = hasPhysical ? 'shipping-label' : 'discount-value';

        var discRow = document.getElementById('discount-row');
        if (activeDiscount > 0 && cart.length > 0) {
            discRow.style.display = 'flex';
            document.getElementById('summary-discount').textContent = '-' + fmt(discount);
        } else {
            discRow.style.display = 'none';
        }
        document.getElementById('summary-total').textContent = fmt(total);

        var checkoutBtn = document.getElementById('checkout-btn');
        if (checkoutBtn) checkoutBtn.disabled = cart.length === 0;
    }

    /* ===== COUPON ===== */
    document.getElementById('coupon-apply').addEventListener('click', function () {
        var code = (document.getElementById('coupon-input').value || '').trim().toUpperCase();
        var msgEl = document.getElementById('coupon-msg');
        if (COUPONS[code]) {
            activeDiscount = COUPONS[code].pct;
            msgEl.textContent = '✅ ' + COUPONS[code].label + ' aplicado';
            msgEl.className = 'coupon-msg coupon-ok';
        } else {
            msgEl.textContent = '❌ Código inválido o expirado';
            msgEl.className = 'coupon-msg coupon-err';
        }
        updateSummary();
    });
    document.getElementById('coupon-input').addEventListener('keydown', function (e) {
        if (e.key === 'Enter') document.getElementById('coupon-apply').click();
    });

    /* ===== CLEAR CART ===== */
    document.getElementById('cart-clear-btn').addEventListener('click', function () {
        if (!confirm('¿Vaciar el carrito?')) return;
        cart = [];
        saveCart(cart);
        render();
    });

    /* ===== CHECKOUT ===== */
    document.getElementById('checkout-btn').addEventListener('click', function () {
        if (cart.length === 0) return;

        /* Build order summary */
        var html = '';
        cart.forEach(function (row) {
            var meta = getMeta(String(row.id));
            var qty  = parseInt(row.cantidad,10) || 1;
            html += '<div class="order-row">';
            html += '<span>' + meta.nombre + ' × ' + qty + '</span>';
            html += '<span>' + fmt(meta.precio * qty) + '</span>';
            html += '</div>';
        });
        var subtotal = cart.reduce(function (s, r) {
            return s + getMeta(String(r.id)).precio * (parseInt(r.cantidad,10)||1);
        }, 0);
        var discount = Math.round(subtotal * activeDiscount / 100);
        html += '<div class="order-total-row"><span>Total pagado</span><span>' + fmt(subtotal - discount) + '</span></div>';

        document.getElementById('order-summary-box').innerHTML = html;
        document.getElementById('checkout-modal').classList.add('active');
    });

    document.getElementById('checkout-modal-close').addEventListener('click', function () {
        document.getElementById('checkout-modal').classList.remove('active');
    });
    document.getElementById('checkout-done').addEventListener('click', function () {
        /* Clear cart and go back */
        cart = [];
        saveCart(cart);
        window.location.href = 'index.php';
    });
    document.getElementById('checkout-modal').addEventListener('click', function (e) {
        if (e.target === this) this.classList.remove('active');
    });

    /* ===== ADD TO CART (recommended) ===== */
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.add-to-cart-btn');
        if (!btn) return;

        var id      = btn.getAttribute('data-id');
        var nombre  = btn.getAttribute('data-nombre') || '';
        var precio  = btn.getAttribute('data-precio')  || '';
        var tipo    = btn.getAttribute('data-tipo')    || 'fisico';
        var img     = btn.getAttribute('data-img')     || '';
        var spanEl  = btn.querySelector('span');

        /* Save meta */
        var meta = loadMeta();
        meta[String(id)] = { nombre: nombre, precio: precio, tipo: tipo, img: img };
        saveMeta(meta);

        /* Upsert */
        var found = false;
        cart = cart.map(function (r) {
            if (String(r.id) === String(id)) { found = true; return { id: r.id, cantidad: (parseInt(r.cantidad,10)||0)+1 }; }
            return r;
        });
        if (!found) cart.push({ id: id, cantidad: 1 });
        saveCart(cart);
        render();

        /* Feedback */
        var orig = spanEl ? spanEl.textContent : '';
        if (spanEl) spanEl.textContent = '✓ Añadido';
        btn.style.borderColor = '#22c55e';
        btn.style.color = '#22c55e';
        setTimeout(function () {
            if (spanEl) spanEl.textContent = orig;
            btn.style.borderColor = '';
            btn.style.color = '';
        }, 1500);
    });

    /* ===== INIT ===== */
    render();

})();
