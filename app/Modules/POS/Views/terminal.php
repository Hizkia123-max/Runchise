<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Runchise — POS Terminal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary: #E2A794; --primary-dark: #c98570;
            --secondary: #4f46e5; --bg-dark: #FAF6F3;
            --bg-card: #FFFFFF; --bg-sidebar: #FAF6F3;
            --text-primary: #2C1E1A; --text-muted: #8A756E;
            --border: rgba(226,167,148,0.25);
            --success: #10b981; --danger: #ef4444; --warning: #f59e0b;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: var(--bg-dark); color: var(--text-primary); height: 100vh; overflow: hidden; }

        /* Layout */
        .pos-layout { display: flex; height: 100vh; }
        .pos-products { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
        .pos-cart { width: 360px; min-width: 360px; background: var(--bg-card); border-left: 1px solid var(--border); display: flex; flex-direction: column; }

        /* Header */
        .pos-header {
            padding: 1rem 1.5rem; background: var(--bg-card);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: 1rem;
        }
        .pos-header .brand { font-weight: 700; font-size: 1.1rem; color: var(--primary); }
        .pos-search { flex: 1; }
        .pos-search input {
            width: 100%; background: #ffffff;
            border: 1px solid var(--border); color: var(--text-primary);
            border-radius: 10px; padding: 0.6rem 1rem; font-size: 0.95rem;
        }
        .pos-search input:focus { outline: none; border-color: var(--primary); }
        .pos-search input::placeholder { color: var(--text-muted); }

        /* Category Bar */
        .category-bar {
            padding: 0.75rem 1.5rem; background: var(--bg-card);
            border-bottom: 1px solid var(--border);
            display: flex; gap: 0.5rem; overflow-x: auto;
        }
        .category-bar::-webkit-scrollbar { display: none; }
        .cat-btn {
            padding: 0.4rem 1rem; border-radius: 20px; border: 1px solid var(--border);
            background: transparent; color: var(--text-muted); font-size: 0.85rem;
            cursor: pointer; white-space: nowrap; transition: all 0.2s;
        }
        .cat-btn:hover, .cat-btn.active { background: var(--primary); border-color: var(--primary); color: white; }

        /* Product Grid */
        .product-grid {
            flex: 1; overflow-y: auto; padding: 1.5rem;
            display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 1rem;
        }
        .product-grid::-webkit-scrollbar { width: 4px; }
        .product-grid::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }
        .product-card {
            background: var(--bg-card); 
            border: 1px solid var(--border);
            border-radius: 16px; 
            padding: 1.25rem; 
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); 
            position: relative; 
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            min-height: 160px;
            box-shadow: 0 4px 12px rgba(226, 167, 148, 0.05);
        }
        .product-card:hover { 
            border-color: var(--primary); 
            transform: translateY(-4px); 
            box-shadow: 0 8px 25px rgba(226, 167, 148, 0.2); 
        }
        .product-card.out-of-stock { opacity: 0.5; pointer-events: none; }
        .product-icon { font-size: 2.2rem; margin-bottom: 0.5rem; display: block; }
        .product-name { 
            font-size: 0.88rem; 
            font-weight: 600; 
            color: var(--text-primary); 
            margin-bottom: 0.35rem; 
            min-height: 2.4em;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .product-price { 
            font-size: 0.95rem; 
            color: #b07765; 
            font-weight: 700; 
            margin-bottom: 0.25rem; 
        }
        .product-stock { 
            font-size: 0.75rem; 
            color: var(--text-muted); 
            font-weight: 500;
        }
        .stock-badge {
            position: absolute; top: 0.5rem; right: 0.5rem;
            font-size: 0.65rem; padding: 0.2rem 0.5rem; border-radius: 20px;
            font-weight: 600;
        }
        .stock-badge.low { background: rgba(245,158,11,0.15); color: var(--warning); }
        .stock-badge.out { background: rgba(239,68,68,0.15); color: var(--danger); }

        /* Cart */
        .cart-header { padding: 1rem 1.5rem; border-bottom: 1px solid var(--border); }
        .cart-header h6 { font-weight: 600; font-size: 0.9rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; }
        .cart-items { flex: 1; overflow-y: auto; padding: 1rem; }
        .cart-items::-webkit-scrollbar { display: none; }
        .cart-item {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.75rem; border-radius: 12px; background: #faf5f2;
            margin-bottom: 0.5rem; border: 1px solid var(--border);
        }
        .cart-item-info { flex: 1; }
        .cart-item-name { font-size: 0.85rem; font-weight: 500; }
        .cart-item-price { font-size: 0.8rem; color: var(--text-muted); }
        .cart-qty { display: flex; align-items: center; gap: 0.5rem; }
        .qty-btn {
            width: 26px; height: 26px; border-radius: 8px;
            border: 1px solid var(--border); background: var(--bg-dark);
            color: var(--text-primary); cursor: pointer; font-size: 1rem;
            display: flex; align-items: center; justify-content: center; transition: all 0.2s;
        }
        .qty-btn:hover { background: var(--primary); border-color: var(--primary); }
        .qty-num { font-size: 0.9rem; font-weight: 600; min-width: 20px; text-align: center; }
        .remove-btn { color: var(--danger); cursor: pointer; font-size: 0.85rem; padding: 0.25rem; }

        /* Cart Footer */
        .cart-footer { padding: 1rem 1.5rem; border-top: 1px solid var(--border); }
        .cart-customer { margin-bottom: 1rem; }
        .cart-customer select {
            width: 100%; background: #ffffff;
            border: 1px solid var(--border); color: var(--text-primary);
            border-radius: 10px; padding: 0.5rem 0.75rem; font-size: 0.85rem;
        }
        .cart-totals { margin-bottom: 1rem; }
        .total-row { display: flex; justify-content: space-between; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.3rem; }
        .total-row.grand { color: var(--text-primary); font-weight: 700; font-size: 1.1rem; margin-top: 0.5rem; padding-top: 0.5rem; border-top: 1px solid var(--border); }
        .payment-methods { display: flex; gap: 0.5rem; margin-bottom: 1rem; }
        .pay-btn {
            flex: 1; padding: 0.5rem; border-radius: 10px; border: 1px solid var(--border);
            background: transparent; color: var(--text-muted); font-size: 0.8rem;
            cursor: pointer; transition: all 0.2s; text-align: center;
        }
        .pay-btn:hover, .pay-btn.active { background: rgba(226,167,148,0.2); border-color: var(--primary); color: var(--primary); }
        .pay-now-btn {
            width: 100%; padding: 1rem; background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none; border-radius: 12px; color: white; font-weight: 700; font-size: 1rem;
            cursor: pointer; transition: all 0.3s;
        }
        .pay-now-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(226,167,148,0.4); }
        .pay-now-btn:disabled { opacity: 0.5; transform: none; cursor: not-allowed; }

        /* Empty cart */
        .cart-empty { text-align: center; padding: 2rem; color: var(--text-muted); }
        .cart-empty i { font-size: 2.5rem; margin-bottom: 0.75rem; opacity: 0.4; }
    </style>
</head>
<body>

<div class="d-flex" style="min-height: 100vh; overflow: hidden; background: var(--bg-dark);">
    <!-- Shared Premium Sidebar -->
    <?= view('partials/sidebar') ?>

    <div class="flex-grow-1 pos-layout">
        <!-- LEFT: Products -->
        <div class="pos-products">
        <div class="pos-header">
            <a href="/dashboard" class="brand" style="text-decoration:none;">⚡ Runchise</a>
            <div class="pos-search">
                <input id="searchInput" type="text" placeholder="🔍 Search or scan barcode..." autocomplete="off">
            </div>
            <div style="color: var(--text-muted); font-size:0.85rem;">
                <i class="bi bi-person-circle"></i> <?= esc(session()->get('user_name') ?? 'Cashier') ?>
            </div>
        </div>

        <div class="category-bar">
            <button class="cat-btn active" data-cat="all">All Items</button>
            <button class="cat-btn" data-cat="food">Food & Beverage</button>
            <button class="cat-btn" data-cat="retail">Retail</button>
            <button class="cat-btn" data-cat="electronics">Electronics</button>
            <button class="cat-btn" data-cat="fashion">Fashion</button>
            <button class="cat-btn" data-cat="services">Services</button>
        </div>

        <div class="product-grid" id="productGrid">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $p): ?>
                    <?php 
                    $isOut = ($p['stock'] <= 0);
                    $isLow = ($p['stock'] > 0 && $p['stock'] <= ($p['reorder_point'] ?? 5));
                    
                    // Map category name to lower-case code matching button data-cat
                    $rawCat = strtolower($p['category_name'] ?? '');
                    $catCode = 'all';
                    if (strpos($rawCat, 'food') !== false) {
                        $catCode = 'food';
                    } elseif (strpos($rawCat, 'retail') !== false) {
                        $catCode = 'retail';
                    } elseif (strpos($rawCat, 'electro') !== false) {
                        $catCode = 'electronics';
                    } elseif (strpos($rawCat, 'fashion') !== false) {
                        $catCode = 'fashion';
                    } elseif (strpos($rawCat, 'serv') !== false) {
                        $catCode = 'services';
                    }
                    ?>
                    <?php
                    // Map category code to beautiful icons
                    $emoji = '📦';
                    if ($catCode === 'food') {
                        $emoji = '🍛';
                    } elseif ($catCode === 'retail') {
                        $emoji = '🧴';
                    } elseif ($catCode === 'electronics') {
                        $emoji = '🔌';
                    } elseif ($catCode === 'fashion') {
                        $emoji = '👕';
                    } elseif ($catCode === 'services') {
                        $emoji = '🧹';
                    }
                    ?>
                    <div class="product-card <?= $isOut ? 'out-of-stock' : '' ?>" 
                         data-id="<?= $p['id'] ?>" 
                         data-name="<?= esc($p['name']) ?>" 
                         data-price="<?= $p['price'] ?>" 
                         data-stock="<?= $p['stock'] ?>"
                         data-cat="<?= $catCode ?>">
                        <?php if ($isOut): ?>
                            <span class="stock-badge out">Out</span>
                        <?php elseif ($isLow): ?>
                            <span class="stock-badge low">Low</span>
                        <?php endif; ?>
                        <div class="product-icon"><?= $emoji ?></div>
                        <div class="product-name"><?= esc($p['name']) ?></div>
                        <div class="product-price">Rp <?= number_format($p['price']) ?></div>
                        <div class="product-stock"><?= $isOut ? 'Out of Stock' : 'Stock: ' . $p['stock'] ?></div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-4 text-muted w-100">No products available in the catalog.</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- RIGHT: Cart -->
    <div class="pos-cart">
        <div class="cart-header">
            <h6><i class="bi bi-cart3"></i> Current Order</h6>
        </div>

        <div style="flex: 1; display: flex; flex-direction: column; overflow-y: auto;">
            <div class="cart-empty" id="emptyCart" style="padding: 2rem; text-align: center; color: var(--text-muted);">
                <i class="bi bi-cart-x d-block" style="font-size: 2.5rem; margin-bottom: 0.75rem; opacity: 0.4;"></i>
                Scan or click a product to add it to the cart
            </div>
            <div class="cart-items" id="cartItems" style="padding: 1rem; overflow-y: auto;"></div>
        </div>

        <div class="cart-footer">
            <div class="cart-customer">
                <select id="customerSelect">
                    <option value="">👤 Walk-in Customer</option>
                </select>
            </div>

            <div class="cart-totals">
                <div class="total-row"><span>Subtotal</span><span id="subtotalDisplay">Rp 0</span></div>
                <div class="total-row"><span>Discount</span><span id="discountDisplay">Rp 0</span></div>
                <div class="total-row"><span>PPN (11%)</span><span id="taxDisplay">Rp 0</span></div>
                <div class="total-row grand"><span>Grand Total</span><span id="grandTotalDisplay">Rp 0</span></div>
            </div>

            <div class="payment-methods">
                <button class="pay-btn active" data-method="Cash" id="payCash"><i class="bi bi-cash"></i> Cash</button>
                <button class="pay-btn" data-method="Card" id="payCard"><i class="bi bi-credit-card"></i> Card</button>
                <button class="pay-btn" data-method="QRIS" id="payQRIS"><i class="bi bi-qr-code"></i> QRIS</button>
            </div>

            <button class="btn btn-outline-warning w-100 mb-2 py-2" id="holdOrderBtn" style="border-radius:12px; font-weight:600; font-size:0.9rem;" disabled>
                <i class="bi bi-pause-circle"></i> Tunda Pesanan (Hold Order)
            </button>

            <button class="pay-now-btn" id="payNowBtn" disabled>
                <i class="bi bi-check-circle"></i> Pay Now — <span id="payNowTotal">Rp 0</span>
            </button>
        </div>
    </div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
const cart = [];
let selectedPayment = 'Cash';
const TAX_RATE = 0.11;

// Format currency
const fmt = (n) => 'Rp ' + Math.round(n).toLocaleString('id-ID');

// Restore pending cart on load if exists
document.addEventListener('DOMContentLoaded', () => {
    try {
        const pending = localStorage.getItem('runchise_pending_cart');
        if (pending) {
            const items = JSON.parse(pending);
            if (items && items.length > 0) {
                items.forEach(i => cart.push(i));
                renderCart();
            }
        }
    } catch (e) {
        console.error("Failed to restore pending cart", e);
    }
    
    // Handle URL category filtering if present
    const urlParams = new URLSearchParams(window.location.search);
    const catParam = urlParams.get('cat');
    if (catParam) {
        const targetBtn = document.querySelector(`.cat-btn[data-cat="${catParam}"]`);
        if (targetBtn) {
            targetBtn.click();
        }
    }
});

// Add product to cart
document.querySelectorAll('.product-card:not(.out-of-stock)').forEach(card => {
    card.addEventListener('click', () => {
        const id    = card.dataset.id;
        const name  = card.dataset.name;
        const price = parseFloat(card.dataset.price);
        const existing = cart.find(i => i.id === id);
        if (existing) { existing.qty++; }
        else { cart.push({ id, name, price, qty: 1, discount: 0 }); }
        renderCart();
    });
});

// Payment method selection
document.querySelectorAll('.pay-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.pay-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        selectedPayment = btn.dataset.method;
    });
});

// Pay now
document.getElementById('payNowBtn').addEventListener('click', () => {
    if (!cart.length) return;
    const payload = {
        branch_id:      1,
        pos_session_id: 1,
        payment_method: selectedPayment,
        items: cart.map(i => ({
            product_id:      i.id,
            quantity:        i.qty,
            unit_price:      i.price,
            discount_amount: i.discount,
        })),
    };
    fetch('/api/v1/pos/transactions', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify(payload),
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            alert(`✅ Payment Success!\nInvoice: ${res.data.invoice_number}\nTotal: Rp ${res.data.grand_total.toLocaleString('id-ID')}`);
            cart.length = 0;
            localStorage.removeItem('runchise_pending_cart');
            renderCart();
        } else {
            alert('❌ Transaction failed: ' + res.message);
        }
    })
    .catch(err => alert('⚠️ Network error: ' + err.message));
});

// Hold current order
document.getElementById('holdOrderBtn').addEventListener('click', () => {
    if (!cart.length) return;
    
    let pendingCarts = [];
    const multiplePending = localStorage.getItem('runchise_pending_carts');
    if (multiplePending) {
        try {
            pendingCarts = JSON.parse(multiplePending);
        } catch(e) {
            pendingCarts = [];
        }
    }
    
    const nextNum = pendingCarts.length + 1;
    const newHeld = {
        id: Date.now(),
        name: 'Order Tertunda #' + nextNum,
        created_at: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }),
        items: [...cart]
    };
    
    pendingCarts.push(newHeld);
    localStorage.setItem('runchise_pending_carts', JSON.stringify(pendingCarts));
    
    // Clear active cart
    cart.length = 0;
    localStorage.removeItem('runchise_pending_cart');
    
    alert(`Order ditunda sebagai "${newHeld.name}"!\nAnda sekarang dapat melayani pelanggan berikutnya.`);
    
    renderCart();
    
    if (window.refreshPendingCartWidget) window.refreshPendingCartWidget();
});

// Search filter
function filterProducts() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    const activeBtn = document.querySelector('.cat-btn.active');
    const cat = activeBtn ? activeBtn.dataset.cat : 'all';
    
    document.querySelectorAll('.product-card').forEach(card => {
        const matchesSearch = card.dataset.name.toLowerCase().includes(q);
        const matchesCategory = (cat === 'all' || card.dataset.cat === cat);
        
        if (matchesSearch && matchesCategory) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}

// Search filter
document.getElementById('searchInput').addEventListener('input', filterProducts);

// Category filter
document.querySelectorAll('.cat-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        filterProducts();
    });
});

function renderCart() {
    const container = document.getElementById('cartItems');
    const emptyEl   = document.getElementById('emptyCart');
    if (!cart.length) {
        container.innerHTML = '';
        emptyEl.style.display = 'block';
        document.getElementById('payNowBtn').disabled = true;
        document.getElementById('holdOrderBtn').disabled = true;
        updateTotals(0, 0, 0);
        localStorage.removeItem('runchise_pending_cart');
        return;
    }
    emptyEl.style.display = 'none';

    let subtotal = 0;
    container.innerHTML = '';
    cart.forEach((item, idx) => {
        const lineTotal = item.price * item.qty;
        subtotal += lineTotal;
        const div = document.createElement('div');
        div.className = 'cart-item';
        div.innerHTML = `
            <div class="cart-item-info">
                <div class="cart-item-name">${item.name}</div>
                <div class="cart-item-price">${fmt(item.price)} × ${item.qty} = ${fmt(lineTotal)}</div>
            </div>
            <div class="cart-qty">
                <button class="qty-btn" onclick="changeQty(${idx}, -1)">−</button>
                <span class="qty-num">${item.qty}</span>
                <button class="qty-btn" onclick="changeQty(${idx}, 1)">+</button>
            </div>
            <span class="remove-btn" onclick="removeItem(${idx})"><i class="bi bi-x-circle-fill"></i></span>`;
        container.appendChild(div);
    });

    const tax   = subtotal * TAX_RATE;
    const total = subtotal + tax;
    updateTotals(subtotal, tax, total);
    document.getElementById('payNowBtn').disabled = false;
    document.getElementById('holdOrderBtn').disabled = false;
    
    // Save to localStorage
    localStorage.setItem('runchise_pending_cart', JSON.stringify(cart));
}

function updateTotals(sub, tax, grand) {
    document.getElementById('subtotalDisplay').textContent   = fmt(sub);
    document.getElementById('taxDisplay').textContent        = fmt(tax);
    document.getElementById('grandTotalDisplay').textContent = fmt(grand);
    document.getElementById('payNowTotal').textContent       = fmt(grand);
}

function changeQty(idx, delta) {
    cart[idx].qty = Math.max(1, cart[idx].qty + delta);
    renderCart();
}

function removeItem(idx) {
    cart.splice(idx, 1);
    renderCart();
}
</script>
</body>
</html>
