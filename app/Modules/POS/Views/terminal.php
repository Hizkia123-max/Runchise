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
        .pos-layout { display: flex; height: 100vh; width: calc(100vw - 240px); max-width: calc(100vw - 240px); overflow: hidden; }
        .pos-products { flex: 1; display: flex; flex-direction: column; overflow: hidden; min-width: 0; }
        .pos-cart { width: 360px; min-width: 360px; max-width: 360px; background: var(--bg-card); border-left: 1px solid var(--border); display: flex; flex-direction: column; overflow: hidden; }

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

        /* ==========================================================================
           Interactive Payment & Premium Receipt Simulated Styles
           ========================================================================== */
        
        /* EDC Machine Styles */
        .edc-machine {
            width: 250px; background: #2f3640; border-radius: 16px; padding: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3); border: 4px solid #1e272e;
            position: relative; text-align: center;
        }
        .edc-header { display: flex; justify-content: space-between; font-size: 0.75rem; color: #718093; margin-bottom: 8px; font-weight: 700; }
        .edc-bank-logo { color: #f5f6fa; font-weight: 800; letter-spacing: 0.5px; }
        .edc-signal { color: #44bd32; }
        .edc-screen {
            background: #1dd1a1; color: #222f3e; border-radius: 8px; padding: 12px 10px;
            font-family: 'Courier New', Courier, monospace; font-weight: 700; text-align: left;
            box-shadow: inset 0 3px 5px rgba(0,0,0,0.2); margin-bottom: 12px; height: 95px;
            display: flex; flex-direction: column; justify-content: space-between; border: 2px solid #10ac84;
        }
        .edc-screen-title { font-size: 0.65rem; color: rgba(34,47,62,0.7); }
        .edc-screen-amount { font-size: 1.15rem; font-weight: 800; letter-spacing: -0.5px; }
        .edc-screen-status { font-size: 0.7rem; color: #222f3e; margin-top: 5px; animation: blinker 1.5s linear infinite; }
        @keyframes blinker { 50% { opacity: 0; } }
        .edc-keypad { display: grid; grid-template-columns: repeat(3, 1fr); gap: 6px; }
        .edc-key {
            background: #47535e; color: #f5f6fa; font-size: 0.95rem; font-weight: 700; padding: 6px;
            border-radius: 6px; border: 1px solid #3d4a56; border-bottom: 3px solid #2d3842; cursor: pointer;
            transition: all 0.1s; display: flex; align-items: center; justify-content: center;
        }
        .edc-key:active { transform: translateY(1px); border-bottom-width: 1px; }
        .edc-cancel { background: #ea8685; color: #222f3e; font-size: 0.8rem; }
        .edc-enter { background: #1dd1a1; color: #222f3e; font-size: 0.8rem; }

        /* QRIS Simulator styles */
        .qris-scanner-laser {
            position: absolute; left: 0; top: 0; width: 100%; height: 3px; background: #ef4444;
            opacity: 0.8; box-shadow: 0 0 8px #ef4444; border-radius: 50%;
            animation: laserScan 2.5s cubic-bezier(0.4, 0, 0.2, 1) infinite;
            z-index: 10;
        }
        @keyframes laserScan {
            0%, 100% { top: 0%; }
            50% { top: 100%; }
        }
        .qris-dot-pulse {
            width: 8px; height: 8px; background-color: #f59e0b; border-radius: 50%; display: inline-block;
            animation: dotPulse 1.5s infinite ease-in-out;
        }
        @keyframes dotPulse {
            0%, 100% { transform: scale(0.6); opacity: 0.5; }
            50% { transform: scale(1.2); opacity: 1; }
        }
        
        /* Thermal Receipt styles */
        .thermal-receipt {
            background: #ffffff; color: #000000; font-family: 'Courier New', Courier, monospace;
            width: 380px; max-width: 100%; padding: 25px 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            border-radius: 4px; border: 1px solid #e1d8d2; position: relative;
        }
        .thermal-receipt::before, .thermal-receipt::after {
            content: ''; position: absolute; left: 0; width: 100%; height: 6px;
            background-size: 10px 6px;
        }
        .thermal-receipt::before {
            top: -6px; background-image: radial-gradient(circle, transparent 70%, #ffffff 70%);
        }
        .thermal-receipt::after {
            bottom: -6px; background-image: radial-gradient(circle, transparent 70%, #ffffff 70%);
        }
        .receipt-brand { font-weight: 800; font-size: 1.6rem; letter-spacing: -1px; }
        .receipt-sub { font-size: 0.75rem; letter-spacing: 2px; color: #555; font-weight: 700; }
        .receipt-outlet { font-weight: 700; margin-top: 2px; }
        .receipt-address { font-size: 0.7rem; }
        .receipt-divider { border-top: 1px dashed #333333; margin: 12px 0; }
        .receipt-meta { line-height: 1.4; }
        .receipt-items table th { border-bottom: 1px dashed #333; padding-bottom: 5px; }
        .receipt-items table td { padding: 4px 0; vertical-align: top; }
        .receipt-totals table td { padding: 3px 0; }
        .receipt-barcode { font-size: 1.5rem; letter-spacing: 2px; font-weight: 300; line-height: 1; margin-top: 10px; opacity: 0.75; text-align: center; }

        /* Media Print Queries for perfect POS thermal ticket printing */
        @media print {
            body * { visibility: hidden !important; }
            #receiptModal, #receiptModal * { visibility: visible !important; }
            #receiptModal { position: absolute; left: 0; top: 0; width: 100%; height: auto; display: block !important; padding: 0 !important; margin: 0 !important; box-shadow: none !important; }
            #receiptPaper { width: 100%; border: none !important; box-shadow: none !important; padding: 0 !important; margin: 0 !important; }
            .no-print { display: none !important; }
            .modal-backdrop { display: none !important; }
        }
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
            <div class="d-flex align-items-center gap-3">
                <div style="color: var(--text-muted); font-size:0.85rem;">
                    <i class="bi bi-person-circle"></i> <?= esc(session()->get('user_name') ?? 'Cashier') ?>
                </div>
                <a href="#" onclick="confirmLogout(event)" class="btn btn-sm btn-outline-danger" style="border-radius: 8px; font-weight: 500;">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>

        <div class="category-bar">
            <button class="cat-btn active" data-cat="all">All Items</button>
            <button class="cat-btn" data-cat="laptop">Laptop & PC</button>
            <button class="cat-btn" data-cat="cpu">Komponen</button>
            <button class="cat-btn" data-cat="mouse">Aksesoris</button>
            <button class="cat-btn" data-cat="lain-lain">Jaringan</button>
            <button class="cat-btn" data-cat="service">Service</button>
        </div>

        <div class="product-grid" id="productGrid">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $p): ?>
                    <?php 
                    $isOut = ($p['stock'] <= 0);
                    $isLow = ($p['stock'] > 0 && $p['stock'] <= ($p['reorder_point'] ?? 5));
                    
                    // Map category name to lower-case code matching button data-cat
                    $rawCat = strtolower($p['category_name'] ?? '');
                    $pNameForCat = strtolower($p['name']);
                    $catCode = 'all';
                    if (strpos($rawCat, 'laptop') !== false || strpos($rawCat, 'pc') !== false || strpos($pNameForCat, 'lenovo') !== false || strpos($pNameForCat, 'asus') !== false || strpos($pNameForCat, 'dell') !== false || strpos($pNameForCat, 'macbook') !== false) {
                        $catCode = 'laptop';
                    } elseif (strpos($rawCat, 'service') !== false) {
                        $catCode = 'service';
                    } elseif (strpos($rawCat, 'peripheral') !== false || strpos($pNameForCat, 'mouse') !== false || strpos($pNameForCat, 'keychron') !== false || strpos($pNameForCat, 'headset') !== false || strpos($pNameForCat, 'stream deck') !== false) {
                        $catCode = 'mouse';
                    } elseif (strpos($rawCat, 'component') !== false || strpos($pNameForCat, 'ryzen') !== false || strpos($pNameForCat, 'corsair') !== false || strpos($pNameForCat, 'samsung') !== false || strpos($pNameForCat, 'seasonic') !== false || strpos($pNameForCat, 'rtx') !== false || strpos($pNameForCat, 'intel') !== false) {
                        $catCode = 'cpu';
                    } else {
                        $catCode = 'lain-lain';
                    }
                    ?>
                    <?php
                    // Map category code to beautiful icons
                    $emoji = '📦';
                    if ($catCode === 'laptop') {
                        $emoji = '💻';
                    } elseif ($catCode === 'service') {
                        $emoji = '🧹';
                    } elseif ($catCode === 'mouse') {
                        $emoji = '🖱️';
                    } elseif ($catCode === 'cpu') {
                        $emoji = '⚙️';
                    } elseif ($catCode === 'lain-lain') {
                        $emoji = '🔌';
                    }
                    $imgSrc = null;
                    $pName = strtolower($p['name']);
                    if (strpos($pName, 'lenovo') !== false) {
                        $imgSrc = '/images/products/lenovo.png';
                    } elseif (strpos($pName, 'asus') !== false) {
                        $imgSrc = '/images/products/asus.png';
                    } elseif (strpos($pName, 'dell') !== false) {
                        $imgSrc = '/images/products/dell.png';
                    } elseif (strpos($pName, 'macbook') !== false) {
                        $imgSrc = '/images/products/macbook.png';
                    } elseif (strpos($pName, 'nvidia') !== false) {
                        $imgSrc = '/images/products/nvidia.png';
                    } elseif (strpos($pName, 'amd') !== false || strpos($pName, 'ryzen') !== false) {
                        $imgSrc = '/images/products/amd.png';
                    } elseif (strpos($pName, 'corsair') !== false) {
                        $imgSrc = '/images/products/corsair.png';
                    } elseif (strpos($pName, 'samsung') !== false) {
                        $imgSrc = '/images/products/samsung.png';
                    } elseif (strpos($pName, 'logitech') !== false) {
                        $imgSrc = '/images/products/logitech.png';
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
                        
                        <?php if ($imgSrc): ?>
                            <div class="product-icon" style="height: 60px; display: flex; align-items: center; justify-content: center; margin-bottom: 0.5rem;">
                                <img src="<?= $imgSrc ?>" alt="<?= esc($p['name']) ?>" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                            </div>
                        <?php else: ?>
                            <div class="product-icon"><?= $emoji ?></div>
                        <?php endif; ?>
                        
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
                    <?php if (isset($customers) && is_array($customers)): ?>
                        <?php foreach ($customers as $cust): ?>
                            <option value="<?= $cust['id'] ?>">👤 <?= esc($cust['name']) ?> <?= $cust['phone'] ? ' - ' . esc($cust['phone']) : '' ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="cart-totals">
                <div class="total-row"><span>Subtotal</span><span id="subtotalDisplay">Rp 0</span></div>
                <div class="total-row"><span>Discount</span><span id="discountDisplay">Rp 0</span></div>
                <div class="total-row grand"><span>Grand Total</span><span id="grandTotalDisplay">Rp 0</span></div>
            </div>

            <div class="payment-methods">
                <button class="pay-btn active" data-method="Cash" id="payCash"><i class="bi bi-cash"></i> Cash</button>
                <button class="pay-btn" data-method="Card" id="payCard"><i class="bi bi-credit-card"></i> Card</button>
                <button class="pay-btn" data-method="QRIS" id="payQRIS"><i class="bi bi-qr-code"></i> QRIS</button>
            </div>

            <div class="d-flex gap-2 mb-2">
                <button class="btn btn-outline-warning w-100 py-2" id="holdOrderBtn" style="border-radius:12px; font-weight:600; font-size:0.9rem;" disabled>
                    <i class="bi bi-pause-circle"></i> Tunda
                </button>
                <button class="btn btn-outline-info w-100 py-2" id="viewHeldOrdersBtn" style="border-radius:12px; font-weight:600; font-size:0.9rem;" onclick="showHeldOrdersModal()">
                    <i class="bi bi-card-list"></i> Antrean (<span id="heldCountBadge">0</span>)
                </button>
            </div>

            <button class="pay-now-btn" id="payNowBtn" disabled>
                <i class="bi bi-check-circle"></i> Pay Now — <span id="payNowTotal">Rp 0</span>
            </button>
        </div>
    </div>
</div>
</div>

<!-- ==========================================================================
     SIMULATED PAYMENT MODALS & CUSTOM PRINTABLE RECEIPT
     ========================================================================== -->

<!-- Modal Held Orders -->
<div class="modal fade" id="heldOrdersModal" tabindex="-1" aria-labelledby="heldOrdersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="heldOrdersModalLabel"><i class="bi bi-card-list text-info"></i> Antrean Pesanan Tertunda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="heldOrdersContainer">
                    <div class="text-center text-muted py-4">Tidak ada pesanan yang ditunda.</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal QRIS -->
<div class="modal fade" id="qrisModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="qrisModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; background: #FFF;">
            <div class="modal-header border-0 pb-0 justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span style="font-weight: 800; font-size: 1.4rem; color: #1f3a52; letter-spacing: -1px;">QRIS</span>
                    <span class="badge bg-danger rounded-pill" style="font-size: 0.65rem; font-weight: 700;">GPN SECURE</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="qrisCloseBtn"></button>
            </div>
            <div class="modal-body text-center pt-2">
                <div class="text-muted small mb-1">MERCHANT ID: MID1029402948</div>
                <h6 style="color: var(--text-primary); font-weight: 700;" class="mb-3">⚡ Runchise — Tenant Admin</h6>
                
                <!-- Dynamic QR Screen -->
                <div class="d-inline-block p-4 bg-white border rounded-4 shadow-sm position-relative mb-3" style="border-color: rgba(226,167,148,0.3) !important;">
                    <div class="qris-scanner-laser"></div>
                    <svg width="200" height="200" viewBox="0 0 29 29" shape-rendering="crispEdges" style="background-color: #FFF;">
                        <path fill="#000" d="M0 0h7v7H0zm9 0h1v1H9zm1 1h1v1h-1zm-1 1h1v1H9zm2-2h1v1h-1zm1 1h1v1h-1zm1-1h1v1h-1zm1 1h2v1h-2zm2-1h1v1h-1zm1 1h1v1h-1zm2-1h7v7h-7zM1 1v5h5V1zm18 0v5h5V1zM0 9h1v1H0zm2 0h1v1H2zm1 1h1v1H3zm-2 1h1v1H1zm2 0h2v1H3zm2-2h2v1H5zm1 2h1v1H6zm1-1h1v1H7zm1-1h1v1H8zm2 1h1v1h-1zm1 1h1v1h-1zm1-2h1v1h-1zm1 1h1v1h-1zm1 1h1v1h-1zm1-2h2v1h-2zm2 1h1v1h-1zm1 1h1v1h-1zm1-2h1v1h-1zm2 1h1v1h-1zm-1-2h1v1h-1zm-1-1h1v1h-1zm3 2v1h1v-1zm1 1v1h1v-1zm1-2v1h1v-1zm-2 4h1v1h-1zm2 0h1v1h-1zm-2 1h2v1h-2zm-3-2h1v1h-1zm1 1h1v1h-1zm-2 1h1v1h-1zm5 1h1v1h-1zm-1 1h1v1h-1zm-1-1h1v1h-1zm-2 1h1v1h-1zm-3-2h2v1h-2zm-2 1h1v1h-1zm-1-1h1v1h-1zm-2 1h1v1h-1zm-2-4h1v1H0zm1 1h1v1H1zm1 1h1v1H2zm1-2h1v1H3zm1 1h1v1H4zm1 1h1v1H5zm1-2h1v1H6zm1 1h2v1H7zm2 1h1v1H9zm1-2h1v1h-1zm2 2h1v1h-1zm1-1h1v1h-1zm-3-1h2v1h-2zm4 2h1v1h-1zm-9 3h7v7H0zm18 0h1v1h-1zm1 1h1v1h-1zm-1 1h1v1h-1zm2-3h1v1h-1zm1 1h1v1h-1zm-1 1h2v1h-2zm2-1h1v1h-1zm1-2h1v1h-1zm1 1h1v1h-1zm-3 4h1v1h-1zm2 0h2v1h-2zm1 1h1v1h-1zm-3 1h1v1h-1zm1 1h1v1h-1zm1-2h1v1h-1zm2 2h1v1h-1zM1 19v5h5v-5z" />
                    </svg>
                </div>
                
                <div class="mb-3">
                    <span class="fs-4 fw-bold text-dark" id="qrisGrandTotal">Rp 0</span>
                </div>
                
                <div class="p-3 bg-light rounded-3 mb-3 d-flex align-items-center justify-content-between text-start border" style="font-size:0.85rem;">
                    <div>
                        <div class="text-muted">Status Transaksi</div>
                        <div class="fw-bold text-warning d-flex align-items-center gap-2">
                            <span class="qris-dot-pulse"></span>
                            <span id="qrisStatusText">Menunggu Pembayaran...</span>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="text-muted">Sisa Waktu</div>
                        <div class="fw-bold text-danger font-monospace" id="qrisTimer">05:00</div>
                    </div>
                </div>
                
                <div id="qrisActionPanel" class="mb-2">
                    <button class="btn btn-success w-100 py-2.5 mb-2 d-flex align-items-center justify-content-center gap-2" id="qrisSimulateBtn" style="border-radius:12px; font-weight: 700;">
                        <i class="bi bi-qr-code-scan"></i> Simulasikan Pembayaran (Scan QR)
                    </button>
                    <button class="btn btn-outline-secondary w-100 py-2" data-bs-dismiss="modal" style="border-radius:12px; font-weight: 600;">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Card EDC -->
<div class="modal fade" id="cardModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="cardModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; background: #FFF;">
            <div class="modal-header border-0 pb-0 justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span style="font-weight: 800; font-size: 1.2rem; color: var(--text-primary);"><i class="bi bi-credit-card-2-front"></i> Terminal EDC</span>
                    <span class="badge bg-success rounded-pill" style="font-size: 0.65rem;">🟢 ONLINE</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="cardCloseBtn"></button>
            </div>
            <div class="modal-body pt-2 text-center">
                <div class="edc-machine mx-auto mb-3">
                    <div class="edc-header">
                        <div class="edc-bank-logo" id="edcBankLogo">BANK MANDIRI</div>
                        <div class="edc-signal"><i class="bi bi-wifi"></i></div>
                    </div>
                    <div class="edc-screen">
                        <div class="edc-screen-title">TOTAL PEMBAYARAN</div>
                        <div class="edc-screen-amount" id="edcAmountDisplay">Rp 0</div>
                        <div class="edc-screen-status" id="edcStatusText">SILAKAN INPUT / GESEK KARTU</div>
                    </div>
                    <div class="edc-keypad">
                        <div class="edc-key">1</div><div class="edc-key">2</div><div class="edc-key">3</div>
                        <div class="edc-key">4</div><div class="edc-key">5</div><div class="edc-key">6</div>
                        <div class="edc-key">7</div><div class="edc-key">8</div><div class="edc-key">9</div>
                        <div class="edc-key edc-cancel">C</div><div class="edc-key">0</div><div class="edc-key edc-enter">E</div>
                    </div>
                </div>
                
                <div class="card-input-panel text-start p-3 bg-light rounded-4 border mb-3" id="cardInputPanel">
                    <h6 class="fw-bold mb-3 text-dark" style="font-size: 0.9rem;">Informasi Kartu Kredit / Debit</h6>
                    
                    <div class="mb-3">
                        <label class="form-label small text-muted mb-1">Tipe Kartu / Bank Penerbit</label>
                        <select class="form-select border-0 shadow-sm" id="cardBrandSelect" style="border-radius: 10px;">
                            <option value="Mandiri-VISA">Mandiri VISA</option>
                            <option value="BCA-Mastercard">BCA Mastercard</option>
                            <option value="BRI-GPN">BRI GPN Debit</option>
                            <option value="BNI-VISA">BNI VISA Credit</option>
                            <option value="GPN-Debit">Bank Indonesia GPN</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small text-muted mb-1">Nomor Kartu (Simulasi)</label>
                        <input type="text" class="form-control border-0 shadow-sm" id="cardNumberInput" placeholder="4111 2222 3333 4444" maxlength="19" style="border-radius: 10px;">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small text-muted mb-1">Nama Pemegang Kartu</label>
                        <input type="text" class="form-control border-0 shadow-sm" id="cardHolderInput" placeholder="CASHIER VISITOR" style="border-radius: 10px;">
                    </div>
                    
                    <button class="btn btn-primary w-100 py-2.5 d-flex align-items-center justify-content-center gap-2" id="cardProcessBtn" style="border-radius: 12px; font-weight:700; background: var(--secondary); border: none;">
                        <i class="bi bi-wallet2"></i> Proses Pembayaran Kartu
                    </button>
                </div>
                
                <div class="card-processing-panel py-4 d-none" id="cardProcessingPanel">
                    <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem; color: var(--secondary) !important;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <h5 class="fw-bold" id="processingStepTitle">Menghubungi Bank Otorisasi...</h5>
                    <p class="text-muted small" id="processingStepDesc">Mohon jangan mencabut kartu atau mematikan mesin EDC.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Struk Thermal (Receipt) -->
<div class="modal fade" id="receiptModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 bg-transparent shadow-none" style="align-items: center;">
            <div class="thermal-receipt shadow-lg" id="receiptPaper">
                <div class="receipt-header text-center">
                    <h4 class="receipt-brand mb-0">⚡ Runchise</h4>
                    <div class="receipt-sub">CLOUD ERP & POS SYSTEM</div>
                    <div class="receipt-outlet small">Cabang Utama #001 (Jakarta)</div>
                    <div class="receipt-address text-muted">Jl. Jenderal Sudirman Kav 21, Jakarta</div>
                </div>
                
                <div class="receipt-divider"></div>
                
                <div class="receipt-meta font-monospace small">
                    <table class="w-100">
                        <tr><td>No. Invoice:</td><td class="text-end" id="recInvoice">INV-20260602-0001</td></tr>
                        <tr><td>Tanggal:</td><td class="text-end" id="recDate">02/06/2026 14:25</td></tr>
                        <tr><td>Kasir:</td><td class="text-end" id="recCashier">Admin Runchise</td></tr>
                        <tr><td>Pelanggan:</td><td class="text-end" id="recCustomer">Walk-in Customer</td></tr>
                    </table>
                </div>
                
                <div class="receipt-divider"></div>
                
                <div class="receipt-items small font-monospace">
                    <table class="w-100" id="recItemsTable">
                    </table>
                </div>
                
                <div class="receipt-divider"></div>
                
                <div class="receipt-totals font-monospace small">
                    <table class="w-100">
                        <tr><td>SUBTOTAL:</td><td class="text-end" id="recSubtotal">Rp 0</td></tr>
                        <tr><td>DISKON:</td><td class="text-end" id="recDiscount">Rp 0</td></tr>
                        <tr><td>PPN (11%):</td><td class="text-end" id="recTax">Rp 0</td></tr>
                        <tr class="fw-bold fs-6"><td>TOTAL AKHIR:</td><td class="text-end" id="recGrandTotal">Rp 0</td></tr>
                    </table>
                </div>
                
                <div class="receipt-divider"></div>
                
                <div class="receipt-payment font-monospace small text-center" id="receiptPaymentSection">
                </div>
                
                <div class="receipt-divider"></div>
                
                <div class="receipt-footer text-center small text-muted mt-3 mb-1">
                    <p class="mb-1 fw-bold">TERIMA KASIH ATAS KUNJUNGAN ANDA</p>
                    <p class="mb-0 font-monospace" style="font-size:0.75rem;">Powered by Runchise SaaS ERP</p>
                    <div class="receipt-barcode mx-auto mt-2">
                        <span>||||| | |||| ||| || |||| || ||</span>
                    </div>
                </div>
            </div>
            
            <div class="d-flex gap-2 justify-content-center mt-4 no-print w-100" style="max-width: 380px;">
                <button class="btn btn-light py-2.5 px-4 d-flex align-items-center justify-content-center gap-2 border" id="btnPrintReceipt" style="border-radius:12px; font-weight:700; flex:1;">
                    <i class="bi bi-printer-fill"></i> Cetak Struk (Print)
                </button>
                <button class="btn btn-primary py-2.5 px-4 d-flex align-items-center justify-content-center gap-2" id="btnNewTrx" style="border-radius:12px; font-weight:700; flex:1; background: var(--primary); border:none;">
                    <i class="bi bi-plus-circle-fill"></i> Transaksi Baru
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

// Add product to cart (using event delegation for 100% reliability and dynamic elements support)
document.getElementById('productGrid').addEventListener('click', (e) => {
    const card = e.target.closest('.product-card');
    if (!card || card.classList.contains('out-of-stock')) return;
    
    const id    = card.dataset.id;
    const name  = card.dataset.name;
    const price = parseFloat(card.dataset.price);
    const existing = cart.find(i => i.id === id);
    if (existing) { 
        existing.qty++; 
    } else { 
        cart.push({ id, name, price, qty: 1, discount: 0 }); 
    }
    renderCart();
});

// Payment method selection
document.querySelectorAll('.pay-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.pay-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        selectedPayment = btn.dataset.method;
    });
});

// Global Bootstrap Modal References
let qrisBsModal = null;
let cardBsModal = null;
let receiptBsModal = null;
let qrisCountdownTimer = null;

// Initialize custom modal components
document.addEventListener('DOMContentLoaded', () => {
    qrisBsModal = new bootstrap.Modal(document.getElementById('qrisModal'));
    cardBsModal = new bootstrap.Modal(document.getElementById('cardModal'));
    receiptBsModal = new bootstrap.Modal(document.getElementById('receiptModal'));
});

// Helper: Show Receipt Modal with complete data
function showReceiptModal(invoiceNumber, paymentMethod, paymentDetails, totals) {
    document.getElementById('recInvoice').textContent = invoiceNumber;
    document.getElementById('recDate').textContent = new Date().toLocaleString('id-ID', {
        day: '2-digit', month: '2-digit', year: 'numeric',
        hour: '2-digit', minute: '2-digit'
    });
    document.getElementById('recCashier').textContent = document.querySelector('.pos-header i').nextSibling.textContent.trim() || 'Cashier';
    
    const custOption = document.getElementById('customerSelect');
    const selectedCustName = custOption.options[custOption.selectedIndex].text;
    document.getElementById('recCustomer').textContent = selectedCustName;
    
    // Render items table
    const tableBody = document.getElementById('recItemsTable');
    tableBody.innerHTML = '';
    cart.forEach(item => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${item.name}<br><span class="text-muted">${item.qty} × ${fmt(item.price)}</span></td>
            <td class="text-end text-nowrap" style="vertical-align: middle;">${fmt(item.price * item.qty)}</td>
        `;
        tableBody.appendChild(tr);
    });
    
    document.getElementById('recSubtotal').textContent = fmt(totals.subtotal);
    document.getElementById('recDiscount').textContent = fmt(totals.discount);
    document.getElementById('recTax').textContent = fmt(totals.tax);
    document.getElementById('recGrandTotal').textContent = fmt(totals.grandTotal);
    
    // Render payment specific section
    const paySec = document.getElementById('receiptPaymentSection');
    paySec.innerHTML = '';
    if (paymentMethod === 'Cash') {
        paySec.innerHTML = `
            <div class="fw-bold">METODE: CASH</div>
            <div>STATUS: PAID (LUNAS)</div>
        `;
    } else if (paymentMethod === 'QRIS') {
        paySec.innerHTML = `
            <div class="fw-bold">METODE: QRIS DYNAMIC</div>
            <div class="small">NMID: ID102030405060</div>
            <div class="small">TRX REF: QRIS-${paymentDetails.refId}</div>
            <div class="fw-bold text-success mt-1">LUNAS via QRIS GPN</div>
        `;
    } else if (paymentMethod === 'Card') {
        paySec.innerHTML = `
            <div class="fw-bold">METODE: DEBIT/CREDIT CARD</div>
            <div class="small">CARD: ${paymentDetails.brand}</div>
            <div class="small">NO: ${paymentDetails.maskedNo}</div>
            <div class="small">AUTH CODE: ${paymentDetails.authCode}</div>
            <div class="fw-bold text-success mt-1">APPROVED via EDC TERMINAL</div>
        `;
    }
    
    receiptBsModal.show();
}

// Reset cashier and cart for next transaction
function resetCashierTransaction() {
    cart.length = 0;
    localStorage.removeItem('runchise_pending_cart');
    renderCart();
}

// Receipt printable action
document.getElementById('btnPrintReceipt').addEventListener('click', () => {
    window.print();
});

// New transaction button action
document.getElementById('btnNewTrx').addEventListener('click', () => {
    receiptBsModal.hide();
    resetCashierTransaction();
});

// Immediate checkout for cash payments
function processPaymentImmediate(method, paymentDetails = null) {
    let subtotal = 0;
    cart.forEach(item => {
        subtotal += item.price * item.qty;
    });
    const tax = subtotal * TAX_RATE;
    const discount = 0;
    const grandTotal = subtotal + tax;
    
    const payload = {
        branch_id:      1,
        pos_session_id: 1,
        payment_method: method,
        items: cart.map(i => ({
            product_id:      i.id,
            quantity:        i.qty,
            unit_price:      i.price,
            discount_amount: i.discount,
        })),
    };
    
    // Add visual loading indicator on payNowBtn
    const payNowBtn = document.getElementById('payNowBtn');
    const originalText = payNowBtn.innerHTML;
    payNowBtn.disabled = true;
    payNowBtn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses Transaksi...`;
    
    fetch('/api/v1/pos/transactions', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify(payload),
    })
    .then(r => r.json())
    .then(res => {
        payNowBtn.disabled = false;
        payNowBtn.innerHTML = originalText;
        
        if (res.success) {
            showReceiptModal(res.data.invoice_number, method, paymentDetails, { subtotal, tax, discount, grandTotal });
        } else {
            alert('❌ Transaction failed: ' + res.message);
        }
    })
    .catch(err => {
        payNowBtn.disabled = false;
        payNowBtn.innerHTML = originalText;
        alert('⚠️ Network error: ' + err.message);
    });
}

// QRIS payment simulated flow
function openQRISModalSimulated(grandTotal) {
    document.getElementById('qrisGrandTotal').textContent = fmt(grandTotal);
    document.getElementById('qrisStatusText').textContent = 'Menunggu Pembayaran...';
    document.getElementById('qrisStatusText').parentElement.className = 'fw-bold text-warning d-flex align-items-center gap-2';
    
    // Reset timer to 5 minutes
    clearInterval(qrisCountdownTimer);
    let timeRemaining = 300; // 5 mins in seconds
    const timerDisplay = document.getElementById('qrisTimer');
    
    const updateTimer = () => {
        const mins = Math.floor(timeRemaining / 60);
        const secs = timeRemaining % 60;
        timerDisplay.textContent = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        if (timeRemaining <= 0) {
            clearInterval(qrisCountdownTimer);
            document.getElementById('qrisStatusText').textContent = 'EXPIRED (Kadaluarsa)';
            document.getElementById('qrisStatusText').parentElement.className = 'fw-bold text-danger d-flex align-items-center gap-2';
            document.getElementById('qrisSimulateBtn').disabled = true;
        }
        timeRemaining--;
    };
    
    updateTimer();
    qrisCountdownTimer = setInterval(updateTimer, 1000);
    
    document.getElementById('qrisSimulateBtn').disabled = false;
    document.getElementById('qrisSimulateBtn').innerHTML = `<i class="bi bi-qr-code-scan"></i> Simulasikan Pembayaran (Scan QR)`;
    
    qrisBsModal.show();
}

// QRIS simulation success trigger
document.getElementById('qrisSimulateBtn').addEventListener('click', () => {
    const simBtn = document.getElementById('qrisSimulateBtn');
    simBtn.disabled = true;
    simBtn.innerHTML = `<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Memindai QR & Otorisasi...`;
    
    document.getElementById('qrisStatusText').textContent = 'Memverifikasi Pembayaran...';
    document.getElementById('qrisStatusText').parentElement.className = 'fw-bold text-info d-flex align-items-center gap-2';
    
    let subtotal = 0;
    cart.forEach(item => {
        subtotal += item.price * item.qty;
    });
    const tax = subtotal * TAX_RATE;
    const discount = 0;
    const grandTotal = subtotal + tax;
    
    const payload = {
        branch_id:      1,
        pos_session_id: 1,
        payment_method: 'QRIS',
        items: cart.map(i => ({
            product_id:      i.id,
            quantity:        i.qty,
            unit_price:      i.price,
            discount_amount: i.discount,
        })),
    };
    
    // Simulate brief processing delay
    setTimeout(() => {
        fetch('/api/v1/pos/transactions', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(payload),
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                document.getElementById('qrisStatusText').textContent = 'Pembayaran Sukses!';
                document.getElementById('qrisStatusText').parentElement.className = 'fw-bold text-success d-flex align-items-center gap-2';
                
                setTimeout(() => {
                    clearInterval(qrisCountdownTimer);
                    qrisBsModal.hide();
                    
                    const mockRefId = Math.floor(1000000000 + Math.random() * 9000000000).toString();
                    showReceiptModal(res.data.invoice_number, 'QRIS', { refId: mockRefId }, { subtotal, tax, discount, grandTotal });
                }, 800);
            } else {
                simBtn.disabled = false;
                simBtn.innerHTML = `<i class="bi bi-qr-code-scan"></i> Simulasikan Pembayaran (Scan QR)`;
                document.getElementById('qrisStatusText').textContent = 'Gagal: ' + res.message;
                document.getElementById('qrisStatusText').parentElement.className = 'fw-bold text-danger d-flex align-items-center gap-2';
            }
        })
        .catch(err => {
            simBtn.disabled = false;
            simBtn.innerHTML = `<i class="bi bi-qr-code-scan"></i> Simulasikan Pembayaran (Scan QR)`;
            document.getElementById('qrisStatusText').textContent = 'Error: Koneksi Terputus';
            document.getElementById('qrisStatusText').parentElement.className = 'fw-bold text-danger d-flex align-items-center gap-2';
            alert('⚠️ Network error: ' + err.message);
        });
    }, 1500);
});

// Card EDC simulated payment flow
function openCardModalSimulated(grandTotal) {
    document.getElementById('edcAmountDisplay').textContent = fmt(grandTotal);
    document.getElementById('edcStatusText').textContent = 'SILAKAN GESEK / INPUT KARTU';
    
    // Auto-generate a beautiful mock card number to save time
    const brands = ["4111 5028 9204 8831", "5482 1192 8830 4829", "1982 7739 1238 9920", "4222 9844 1928 3331"];
    const names = ["BUDI ARTO", "EKA SAPUTRA", "RENI ANGGRAENI", "RATNA WATI"];
    const randomIndex = Math.floor(Math.random() * brands.length);
    
    document.getElementById('cardNumberInput').value = brands[randomIndex];
    document.getElementById('cardHolderInput').value = names[randomIndex];
    document.getElementById('cardBrandSelect').selectedIndex = randomIndex % 5;
    
    // Form formatting for card number input
    document.getElementById('cardNumberInput').addEventListener('input', (e) => {
        let val = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
        let formatted = '';
        for (let i = 0; i < val.length; i++) {
            if (i > 0 && i % 4 === 0) formatted += ' ';
            formatted += val[i];
        }
        e.target.value = formatted;
    });

    // Reset layout view
    document.getElementById('cardInputPanel').classList.remove('d-none');
    document.getElementById('cardProcessingPanel').classList.add('d-none');
    
    cardBsModal.show();
}

// Process Card Transaction through Simulated EDC steps
document.getElementById('cardProcessBtn').addEventListener('click', () => {
    const cardNum = document.getElementById('cardNumberInput').value.trim();
    const cardHolder = document.getElementById('cardHolderInput').value.trim() || 'DEBIT HOLDER';
    const brandSelect = document.getElementById('cardBrandSelect');
    const selectedBrand = brandSelect.options[brandSelect.selectedIndex].text;
    
    if (cardNum.length < 15) {
        alert('❌ Nomor kartu debit/kredit tidak valid!');
        return;
    }
    
    // Switch UI panels to loading screens
    document.getElementById('cardInputPanel').classList.add('d-none');
    document.getElementById('cardProcessingPanel').classList.remove('d-none');
    
    const stepTitle = document.getElementById('processingStepTitle');
    const stepDesc = document.getElementById('processingStepDesc');
    const edcStatus = document.getElementById('edcStatusText');
    
    // Step 1: Connecting
    stepTitle.textContent = 'Menghubungi Bank Otorisasi...';
    stepDesc.textContent = 'Memverifikasi status jaringan EDC dan validasi PIN kartu.';
    edcStatus.textContent = 'MENGHUBUNGI BANK...';
    
    let subtotal = 0;
    cart.forEach(item => {
        subtotal += item.price * item.qty;
    });
    const tax = subtotal * TAX_RATE;
    const discount = 0;
    const grandTotal = subtotal + tax;
    
    const payload = {
        branch_id:      1,
        pos_session_id: 1,
        payment_method: 'Card',
        items: cart.map(i => ({
            product_id:      i.id,
            quantity:        i.qty,
            unit_price:      i.price,
            discount_amount: i.discount,
        })),
    };
    
    // Step 2: Authorizing (1.2s delay)
    setTimeout(() => {
        stepTitle.textContent = 'Melakukan Otorisasi Dana...';
        stepDesc.textContent = 'Meminta alokasi limit kartu untuk transaksi sebesar ' + fmt(grandTotal);
        edcStatus.textContent = 'OTORISASI LIMIT...';
        
        // Step 3: API Processing (2.4s delay)
        setTimeout(() => {
            stepTitle.textContent = 'Menyimpan Invoice Transaksi...';
            stepDesc.textContent = 'Menyimpan riwayat pembelian dan memperbarui database.';
            edcStatus.textContent = 'MEMPROSES DATABASE...';
            
            fetch('/api/v1/pos/transactions', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(payload),
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    stepTitle.textContent = 'Transaksi Disetujui!';
                    stepDesc.textContent = 'Pembayaran sukses, mencetak struk thermal POS.';
                    edcStatus.textContent = 'APPROVED OK (LUNAS)';
                    
                    setTimeout(() => {
                        cardBsModal.hide();
                        
                        const maskedNo = cardNum.substring(0, 4) + ' **** **** ' + cardNum.substring(cardNum.length - 4);
                        const mockAuthCode = Math.random().toString(36).substring(2, 8).toUpperCase();
                        
                        showReceiptModal(res.data.invoice_number, 'Card', { brand: selectedBrand, maskedNo, authCode: mockAuthCode }, { subtotal, tax, discount, grandTotal });
                    }, 800);
                } else {
                    alert('❌ EDC Error: ' + res.message);
                    // Reset to input view
                    document.getElementById('cardInputPanel').classList.remove('d-none');
                    document.getElementById('cardProcessingPanel').classList.add('d-none');
                    edcStatus.textContent = 'TRANSAKSI GAGAL';
                }
            })
            .catch(err => {
                alert('⚠️ EDC Connection Failure: ' + err.message);
                document.getElementById('cardInputPanel').classList.remove('d-none');
                document.getElementById('cardProcessingPanel').classList.add('d-none');
                edcStatus.textContent = 'CONNECTION TIMEOUT';
            });
            
        }, 1200);
        
    }, 1200);
});

// Intercept payNowBtn for interactive flows
document.getElementById('payNowBtn').addEventListener('click', () => {
    if (!cart.length) return;
    
    // Calculate total
    let subtotal = 0;
    cart.forEach(item => {
        subtotal += item.price * item.qty;
    });
    const tax = subtotal * TAX_RATE;
    const grand = subtotal + tax;
    
    if (selectedPayment === 'Cash') {
        processPaymentImmediate('Cash');
    } else if (selectedPayment === 'QRIS') {
        openQRISModalSimulated(grand);
    } else if (selectedPayment === 'Card') {
        openCardModalSimulated(grand);
    }
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
    updateHeldOrdersBadge();
});

// Show held orders modal
function showHeldOrdersModal() {
    const container = document.getElementById('heldOrdersContainer');
    let pendingCarts = [];
    try {
        pendingCarts = JSON.parse(localStorage.getItem('runchise_pending_carts')) || [];
    } catch(e) {}
    
    if (pendingCarts.length === 0) {
        container.innerHTML = '<div class="text-center text-muted py-4">Tidak ada pesanan yang ditunda.</div>';
    } else {
        container.innerHTML = '';
        pendingCarts.forEach((order, index) => {
            let totalItems = 0;
            let totalPrice = 0;
            order.items.forEach(i => {
                totalItems += i.qty;
                totalPrice += i.price * i.qty;
            });
            
            container.innerHTML += `
                <div class="card mb-3 border-0 shadow-sm" style="border-radius:12px; border: 1px solid var(--border)!important;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-bold mb-1">${order.name}</h6>
                            <div class="text-muted small"><i class="bi bi-clock"></i> ${order.created_at} &bull; ${totalItems} item &bull; ${fmt(totalPrice)}</div>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-primary" onclick="restoreHeldOrder(${index})" style="border-radius:8px;">Lanjutkan</button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteHeldOrder(${index})" style="border-radius:8px;"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                </div>
            `;
        });
    }
    
    new bootstrap.Modal(document.getElementById('heldOrdersModal')).show();
}

// Restore a held order to active cart
window.restoreHeldOrder = function(index) {
    if (cart.length > 0) {
        if (!confirm('Cart saat ini tidak kosong. Menarik antrean akan mengganti pesanan saat ini. Lanjutkan?')) {
            return;
        }
    }
    let pendingCarts = JSON.parse(localStorage.getItem('runchise_pending_carts')) || [];
    if (pendingCarts[index]) {
        cart.length = 0; // Clear current
        pendingCarts[index].items.forEach(i => cart.push(i));
        pendingCarts.splice(index, 1); // Remove from pending
        localStorage.setItem('runchise_pending_carts', JSON.stringify(pendingCarts));
        
        renderCart();
        updateHeldOrdersBadge();
        bootstrap.Modal.getInstance(document.getElementById('heldOrdersModal')).hide();
    }
};

window.deleteHeldOrder = function(index) {
    if (confirm('Hapus antrean pesanan ini?')) {
        let pendingCarts = JSON.parse(localStorage.getItem('runchise_pending_carts')) || [];
        pendingCarts.splice(index, 1);
        localStorage.setItem('runchise_pending_carts', JSON.stringify(pendingCarts));
        updateHeldOrdersBadge();
        showHeldOrdersModal(); // re-render modal
    }
};

function updateHeldOrdersBadge() {
    let pendingCarts = [];
    try {
        pendingCarts = JSON.parse(localStorage.getItem('runchise_pending_carts')) || [];
    } catch(e) {}
    document.getElementById('heldCountBadge').textContent = pendingCarts.length;
}

// Call on load
document.addEventListener('DOMContentLoaded', updateHeldOrdersBadge);

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
        updateTotals(0, 0);
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

    const total = subtotal;
    updateTotals(subtotal, total);
    document.getElementById('payNowBtn').disabled = false;
    document.getElementById('holdOrderBtn').disabled = false;
    
    // Save to localStorage
    localStorage.setItem('runchise_pending_cart', JSON.stringify(cart));
}

function updateTotals(sub, grand) {
    document.getElementById('subtotalDisplay').textContent   = fmt(sub);
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
