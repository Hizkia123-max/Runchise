<script>
    // Apply sidebar state immediately to prevent layout shift/flash
    (function() {
        const collapsed = localStorage.getItem('sidebar_collapsed') === 'true';
        if (collapsed) {
            document.body.classList.add('sidebar-collapsed');
        }
    })();
</script>

<style>
    .runchise-sidebar {
        width: 240px;
        min-width: 240px;
        transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                    min-width 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                    box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background-color: #E2A794;
        color: white;
        height: 100vh;
        position: sticky;
        top: 0;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        font-family: 'Inter', sans-serif;
        box-shadow: 4px 0 15px rgba(0, 0, 0, 0.05);
        z-index: 1000;
        overflow-y: auto;
    }
    .runchise-sidebar::-webkit-scrollbar {
        width: 4px;
    }
    .runchise-sidebar::-webkit-scrollbar-thumb {
        background: rgba(255,255,255,0.25);
        border-radius: 4px;
    }
    /* Top Outlet Header */
    .sidebar-outlet-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.18);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .outlet-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .outlet-icon {
        font-size: 1.5rem;
        color: white;
    }
    .outlet-name {
        font-size: 0.85rem;
        font-weight: 700;
        line-height: 1.2;
    }
    .outlet-sub {
        font-size: 0.7rem;
        color: rgba(255, 255, 255, 0.88);
        font-weight: 500;
    }
    .outlet-toggle-btn {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        color: white;
    }
    .outlet-toggle-btn:hover {
        background: rgba(255, 255, 255, 0.3);
    }
    
    /* Navigation Items */
    .sidebar-menu {
        flex: 1;
        padding: 0.75rem 0.5rem;
        list-style: none;
        margin: 0;
    }
    .menu-item {
        margin-bottom: 0.25rem;
    }
    .menu-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.6rem 0.8rem;
        color: rgba(255, 255, 255, 0.95);
        text-decoration: none;
        border-radius: 8px;
        font-size: 0.82rem;
        font-weight: 600;
        transition: all 0.2s;
        border: none;
        background: transparent;
        width: 100%;
        text-align: left;
    }
    .menu-link:hover {
        background-color: rgba(255, 255, 255, 0.15);
        color: white;
    }
    .menu-link.active {
        background-color: rgba(255, 255, 255, 0.22);
        color: white;
        border-left: 4px solid #fff5e6; /* High contrast matching color */
    }
    .menu-link-content {
        display: flex;
        align-items: center;
        gap: 0.65rem;
    }
    .menu-link i {
        font-size: 1rem;
    }

    /* Submenu styling */
    .submenu-list {
        list-style: none;
        padding-left: 2.1rem;
        margin: 0.15rem 0 0.5rem 0;
    }
    .submenu-link {
        display: block;
        padding: 0.35rem 0.8rem;
        color: rgba(255, 255, 255, 0.88);
        text-decoration: none;
        font-size: 0.78rem;
        font-weight: 500;
        border-radius: 6px;
        transition: all 0.2s;
    }
    .submenu-link:hover, .submenu-link.active {
        color: white;
        background-color: rgba(255, 255, 255, 0.12);
    }

    /* Pending Cart Pulse Notification */
    .pending-cart-alert {
        margin: 0.5rem 0.75rem 0.2rem 0.75rem;
        background: linear-gradient(135deg, #ff9f00, #ff5e00);
        border-radius: 8px;
        padding: 0.6rem;
        box-shadow: 0 4px 15px rgba(255, 94, 0, 0.4);
        display: none;
        animation: pulse-border 2s infinite;
        cursor: pointer;
        text-decoration: none;
        color: white !important;
        transition: transform 0.2s;
    }
    .pending-cart-alert:hover {
        transform: translateY(-2px);
    }
    @keyframes pulse-border {
        0% { box-shadow: 0 0 0 0 rgba(255, 159, 0, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(255, 159, 0, 0); }
        100% { box-shadow: 0 0 0 0 rgba(255, 159, 0, 0); }
    }

    /* Reset body padding to ensure sidebar is flush with screen edges */
    body {
        padding: 0 !important;
        margin: 0 !important;
    }

    /* Ensure the inner wrapper remains at 240px to prevent text wrapping */
    .runchise-sidebar .sidebar-main-content {
        width: 240px;
        min-width: 240px;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        flex: 1;
        transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Collapsed Brand Button */
    .sidebar-brand-collapsed {
        display: none;
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0.15));
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.25rem;
        color: white;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        margin: 1rem auto 0.5rem auto;
        border: 2px solid rgba(255, 255, 255, 0.25);
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .sidebar-brand-collapsed:hover {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.4), rgba(255, 255, 255, 0.15));
        transform: scale(1.05);
        box-shadow: 0 6px 15px rgba(255, 255, 255, 0.15);
    }

    /* Sidebar Footer user info card */
    .sidebar-footer {
        padding: 1rem 1.25rem;
        border-top: 1px solid rgba(255, 255, 255, 0.18);
        display: flex;
        align-items: center;
        margin-top: auto;
        transition: padding 0.3s, justify-content 0.3s;
    }
    .user-avatar-circle {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(255,255,255,0.35), rgba(255,255,255,0.15));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.95rem;
        box-shadow: 0 4px 10px rgba(0,0,0,0.06);
        border: 2px solid rgba(255,255,255,0.4);
        flex-shrink: 0;
    }
    .user-info-text {
        display: flex;
        flex-direction: column;
        margin-left: 0.75rem;
    }
    .user-name-label {
        font-size: 0.82rem;
        font-weight: 700;
        color: white;
        line-height: 1.2;
    }
    .user-role-label {
        font-size: 0.68rem;
        color: rgba(255,255,255,0.85);
        font-weight: 500;
        margin-top: 2px;
    }

    /* Collapsed Pending Cart Widget */
    .pending-cart-collapsed-alert {
        display: none;
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, #ff9f00, #ff5e00);
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.15rem;
        margin: 0.5rem auto;
        position: relative;
        box-shadow: 0 4px 12px rgba(255, 94, 0, 0.4);
        animation: pulse-border 2s infinite;
        text-decoration: none;
    }
    .pending-cart-collapsed-badge {
        position: absolute;
        top: -4px;
        right: -4px;
        background: #ef4444;
        color: white;
        font-size: 0.65rem;
        font-weight: 800;
        padding: 2px 6px;
        border-radius: 10px;
        border: 2px solid #E2A794; /* matches sidebar background color */
        line-height: 1;
    }

    /* Floating Toggle Button styling */
    .sidebar-floating-toggle {
        position: fixed;
        left: 15px;
        top: 15px;
        z-index: 1050;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #E2A794;
        border: 1px solid rgba(255, 255, 255, 0.25);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(226, 167, 148, 0.35);
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        opacity: 0;
        pointer-events: none;
        transform: scale(0.8);
    }
    .sidebar-floating-toggle:hover {
        background: #c98570;
        transform: scale(1.05);
        box-shadow: 0 6px 20px rgba(226, 167, 148, 0.45);
    }

    /* Transition for headers/nav to slide smoothly when sidebar collapses/expands */
    .flex-grow-1 > :first-child,
    .pos-header {
        transition: padding-left 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }

    /* Ensure POS layout takes proper width when sidebar is collapsed */
    .pos-layout {
        transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), max-width 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }

    /* Shared Collapsed state: narrow sidebar showing only icons (both desktop and mobile) */
    body.sidebar-collapsed .runchise-sidebar {
        width: 70px !important;
        min-width: 70px !important;
        overflow: visible !important;
    }
    body.sidebar-collapsed .runchise-sidebar .sidebar-main-content {
        width: 70px !important;
        min-width: 70px !important;
    }
    
    /* Show brand and pending cart collapsed versions */
    body.sidebar-collapsed .sidebar-brand-collapsed {
        display: flex;
    }
    body.sidebar-collapsed .pending-cart-collapsed-alert {
        display: flex;
    }
    
    /* Hide normal elements when collapsed */
    body.sidebar-collapsed .sidebar-outlet-header,
    body.sidebar-collapsed .pending-cart-alert,
    body.sidebar-collapsed .menu-link span,
    body.sidebar-collapsed .menu-link .bi-chevron-down,
    body.sidebar-collapsed .submenu-list,
    body.sidebar-collapsed .collapse {
        display: none !important;
    }

    /* Center footer details when collapsed */
    body.sidebar-collapsed .sidebar-footer {
        padding: 1rem 0;
        justify-content: center;
    }
    body.sidebar-collapsed .user-info-text {
        display: none !important;
    }

    /* Centered compact menu links */
    body.sidebar-collapsed .menu-link {
        width: 44px;
        height: 44px;
        padding: 0 !important;
        margin: 0.5rem auto !important;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
    }
    body.sidebar-collapsed .menu-link-content {
        padding: 0 !important;
        margin: 0 !important;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0 !important;
    }
    body.sidebar-collapsed .menu-link i {
        font-size: 1.25rem !important;
        margin: 0 !important;
    }
    body.sidebar-collapsed .menu-link.active {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.35), rgba(255, 255, 255, 0.15)) !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        border-left: none !important;
    }

    /* Desktop collapsible overrides */
    @media (min-width: 769px) {
        /* Overrides layout offset when collapsed on desktop */
        body.sidebar-collapsed .pos-layout {
            width: calc(100vw - 70px) !important;
            max-width: calc(100vw - 70px) !important;
        }

        /* Tooltip styling for collapsed sidebar menu links */
        body.sidebar-collapsed [data-tooltip] {
            position: relative;
        }
        body.sidebar-collapsed [data-tooltip]::after {
            content: attr(data-tooltip);
            position: absolute;
            left: 80px;
            top: 50%;
            transform: translateY(-50%) scale(0.85);
            background: #2C1E1A;
            color: #f5eae6;
            padding: 0.4rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 6px;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 2000;
        }
        body.sidebar-collapsed [data-tooltip]::before {
            content: '';
            position: absolute;
            left: 74px;
            top: 50%;
            transform: translateY(-50%);
            border-width: 5px;
            border-style: solid;
            border-color: transparent #2C1E1A transparent transparent;
            opacity: 0;
            pointer-events: none;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 2000;
        }
        body.sidebar-collapsed [data-tooltip]:hover::after {
            opacity: 1;
            transform: translateY(-50%) scale(1);
        }
        body.sidebar-collapsed [data-tooltip]:hover::before {
            opacity: 1;
        }
    }

    /* Mobile responsive overrides for sidebar sliding/swipe */
    @media (max-width: 768px) {
        .runchise-sidebar {
            position: fixed !important;
            left: 0;
            top: 0;
            bottom: 0;
            height: 100vh !important;
            z-index: 1050 !important;
            width: 240px;
            min-width: 240px;
            transform: none !important;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                        min-width 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                        box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        body.sidebar-collapsed .runchise-sidebar {
            width: 70px !important;
            min-width: 70px !important;
            transform: none !important;
            box-shadow: none;
            overflow: visible !important;
        }

        /* Overlay background when sidebar is active on mobile */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(2px);
            z-index: 1040;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body:not(.sidebar-collapsed) .sidebar-overlay {
            opacity: 1;
            pointer-events: auto;
        }

        .sidebar-floating-toggle {
            display: none !important; /* Hide floating toggle since collapsed sidebar is always visible at 70px */
        }

        /* Adjust content and header styling for mobile collapsed state to avoid unnecessary wide paddings */
        body.sidebar-collapsed .flex-grow-1 > :first-child,
        body.sidebar-collapsed .pos-header {
            padding-left: 85px !important; /* Leave room for collapsed sidebar showing icons */
        }
        
        body.sidebar-collapsed .pos-layout {
            width: 100vw !important;
            max-width: 100vw !important;
        }
    }
</style>

<!-- Floating Toggle Button (Visible when sidebar is collapsed) -->
<button class="sidebar-floating-toggle" id="sidebarFloatingToggle" onclick="toggleSidebar(event)">
    <i class="bi bi-list"></i>
</button>

<!-- Sidebar overlay backdrop for mobile viewports -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar(event)"></div>

<div class="runchise-sidebar">
    <div class="sidebar-main-content">
        <!-- Top Outlet Brand Header (Expanded) -->
        <div class="sidebar-outlet-header">
            <div class="outlet-info">
                <i class="bi bi-shop outlet-icon"></i>
                <div>
                    <div class="outlet-name">⚡ Runchise</div>
                    <div class="outlet-sub">Semua Outlet</div>
                </div>
            </div>
            <button class="outlet-toggle-btn" id="sidebarCollapseBtn" onclick="toggleSidebar(event)">
                <i class="bi bi-list"></i>
            </button>
        </div>

        <!-- Collapsed Brand Logo Button (Click to toggle/expand) -->
        <div class="sidebar-brand-collapsed" onclick="toggleSidebar(event)" data-tooltip="⚡ Runchise (Buka Menu)">
            <i class="bi bi-shop" style="font-size: 1.2rem;"></i>
        </div>

        <!-- Pending Cart Session Resume Notification Widget -->
        <a href="/pos/sessions" class="pending-cart-alert" id="pendingCartNotification">
            <div class="d-flex align-items-center gap-2">
                <span class="spinner-grow spinner-grow-sm text-light" role="status"></span>
                <span style="font-weight:700; font-size:0.72rem; letter-spacing:0.02em;" id="pendingCartCountText">⚡ Antrean Tertunda (0)</span>
            </div>
            <div style="font-size:0.68rem; color:rgba(255,255,255,0.9); margin-top:0.25rem;">Klik untuk mengelola transaksi tertunda.</div>
        </a>

        <!-- Collapsed Pending Cart Widget -->
        <a href="/pos/sessions" class="pending-cart-collapsed-alert" id="pendingCartCollapsedNotification" data-tooltip="Antrean Tertunda (0)">
            <i class="bi bi-lightning-charge-fill"></i>
            <span class="pending-cart-collapsed-badge" id="pendingCartCollapsedCount">0</span>
        </a>

        <!-- Sidebar Navigation List -->
        <?php $currentUri = uri_string(); $userSessionRole = session()->get('user_role') ?? 'TenantOwner'; ?>
        <ul class="sidebar-menu">
            <!-- Dashboard -->
            <li class="menu-item">
                <a href="/dashboard" class="menu-link <?= ($currentUri === 'dashboard') ? 'active' : '' ?>" data-tooltip="Dashboard Utama">
                    <div class="menu-link-content">
                        <i class="bi bi-house-door-fill"></i>
                        <span>Dashboard Utama</span>
                    </div>
                </a>
            </li>

            <!-- POS & Kasir -->
            <li class="menu-item">
                <button class="menu-link" data-bs-toggle="collapse" data-bs-target="#menu-kasir" aria-expanded="true" data-tooltip="Kasir & Transaksi">
                    <div class="menu-link-content">
                        <i class="bi bi-pc-display-horizontal"></i>
                        <span>Kasir & Transaksi</span>
                    </div>
                    <i class="bi bi-chevron-down" style="font-size: 0.75rem;"></i>
                </button>
                <div class="collapse show" id="menu-kasir">
                    <ul class="submenu-list">
                        <li>
                            <a href="/pos/terminal" class="submenu-link <?= ($currentUri === 'pos/terminal') ? 'active' : '' ?>">
                                <i class="bi bi-calculator me-1"></i> POS Terminal
                            </a>
                        </li>
                        <li>
                            <a href="/pos/sessions" class="submenu-link <?= ($currentUri === 'pos/sessions') ? 'active' : '' ?>">
                                <i class="bi bi-clock-history me-1"></i> Shift & Sesi
                            </a>
                        </li>
                        <li>
                            <a href="/pos/returns" class="submenu-link <?= ($currentUri === 'pos/returns') ? 'active' : '' ?>">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Retur Barang
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Pembelian (Purchasing) -->
            <li class="menu-item">
                <button class="menu-link" data-bs-toggle="collapse" data-bs-target="#menu-purchasing" aria-expanded="<?= (strpos($currentUri, 'purchasing') !== false) ? 'true' : 'false' ?>" data-tooltip="Pembelian">
                    <div class="menu-link-content">
                        <i class="bi bi-cart-check-fill"></i>
                        <span>Pembelian</span>
                    </div>
                    <i class="bi bi-chevron-down" style="font-size: 0.75rem;"></i>
                </button>
                <div class="collapse <?= (strpos($currentUri, 'purchasing') !== false) ? 'show' : '' ?>" id="menu-purchasing">
                    <ul class="submenu-list">
                        <li>
                            <a href="/purchasing/orders" class="submenu-link <?= ($currentUri === 'purchasing/orders' || $currentUri === 'purchasing/orders/create') ? 'active' : '' ?>">
                                <i class="bi bi-file-earmark-text me-1"></i> Purchase Order
                            </a>
                        </li>
                        <li>
                            <a href="/purchasing/receivings" class="submenu-link <?= ($currentUri === 'purchasing/receivings' || strpos($currentUri, 'purchasing/receive') !== false) ? 'active' : '' ?>">
                                <i class="bi bi-box-arrow-in-down me-1"></i> Penerimaan Barang
                            </a>
                        </li>
                        <li>
                            <a href="/purchasing/suppliers" class="submenu-link <?= ($currentUri === 'purchasing/suppliers') ? 'active' : '' ?>">
                                <i class="bi bi-people me-1"></i> Data Supplier
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Katalog -->
            <li class="menu-item">
                <button class="menu-link" data-bs-toggle="collapse" data-bs-target="#menu-catalog" aria-expanded="true" data-tooltip="Katalog Produk">
                    <div class="menu-link-content">
                        <i class="bi bi-box-seam-fill"></i>
                        <span>Katalog Produk</span>
                    </div>
                    <i class="bi bi-chevron-down" style="font-size: 0.75rem;"></i>
                </button>
                <div class="collapse show" id="menu-catalog">
                    <ul class="submenu-list">
                        <li>
                            <a href="/inventory/products" class="submenu-link <?= ($currentUri === 'inventory/products') ? 'active' : '' ?>">
                                <i class="bi bi-tags me-1"></i> Produk & Kategori
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Stok & Inventori -->
            <li class="menu-item">
                <button class="menu-link" data-bs-toggle="collapse" data-bs-target="#menu-stok" aria-expanded="true" data-tooltip="Stok & Inventori">
                    <div class="menu-link-content">
                        <i class="bi bi-graph-up-arrow"></i>
                        <span>Stok & Inventori</span>
                    </div>
                    <i class="bi bi-chevron-down" style="font-size: 0.75rem;"></i>
                </button>
                <div class="collapse show" id="menu-stok">
                    <ul class="submenu-list">
                        <li>
                            <a href="/report/stock-onhand" class="submenu-link <?= ($currentUri === 'report/stock-onhand') ? 'active' : '' ?>">
                                <i class="bi bi-box-seam me-1"></i> Stok di Tangan
                            </a>
                        </li>
                        <li>
                            <a href="/report/stock-card" class="submenu-link <?= ($currentUri === 'report/stock-card') ? 'active' : '' ?>">
                                <i class="bi bi-journal-text me-1"></i> Kartu Stok
                            </a>
                        </li>
                        <li>
                            <a href="/inventory/stock" class="submenu-link <?= ($currentUri === 'inventory/stock') ? 'active' : '' ?>">
                                <i class="bi bi-card-checklist me-1"></i> Stock Levels
                            </a>
                        </li>
                        <li>
                            <a href="/inventory/opname" class="submenu-link <?= ($currentUri === 'inventory/opname') ? 'active' : '' ?>">
                                <i class="bi bi-pencil-square me-1"></i> Stock Opname
                            </a>
                        </li>
                        <li>
                            <a href="/inventory/transfers" class="submenu-link <?= ($currentUri === 'inventory/transfers') ? 'active' : '' ?>">
                                <i class="bi bi-arrow-left-right me-1"></i> Stock Transfer
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Laporan -->
            <li class="menu-item">
                <button class="menu-link" data-bs-toggle="collapse" data-bs-target="#menu-laporan" aria-expanded="<?= (strpos($currentUri, 'report/') !== false || $currentUri === 'pos/analytics') ? 'true' : 'false' ?>" data-tooltip="Laporan">
                    <div class="menu-link-content">
                        <i class="bi bi-journal-check"></i>
                        <span>Laporan</span>
                    </div>
                    <i class="bi bi-chevron-down" style="font-size: 0.75rem;"></i>
                </button>
                <div class="collapse <?= (strpos($currentUri, 'report/') !== false || $currentUri === 'pos/analytics') ? 'show' : '' ?>" id="menu-laporan">
                    <ul class="submenu-list">
                        <li>
                            <a href="/report/sales" class="submenu-link <?= ($currentUri === 'report/sales') ? 'active' : '' ?>">
                                <i class="bi bi-bar-chart-line me-1"></i> Report Penjualan
                            </a>
                        </li>
                        <li>
                            <a href="/report/numeric" class="submenu-link <?= ($currentUri === 'report/numeric') ? 'active' : '' ?>">
                                <i class="bi bi-123 me-1"></i> Reporting Numerik
                            </a>
                        </li>
                        <?php if ($userSessionRole === 'TenantOwner' || $userSessionRole === 'SuperAdmin'): ?>
                        <li>
                            <a href="/pos/analytics" class="submenu-link <?= ($currentUri === 'pos/analytics') ? 'active' : '' ?>">
                                <i class="bi bi-cash-stack me-1"></i> Laba Rugi & Laporan
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </li>
        </ul>
    </div>

    <!-- Sidebar Footer (User details) -->
    <?php
    $sessName = session()->get('user_name') ?? 'Admin Runchise';
    $sessRole = session()->get('user_role') ?? 'TenantOwner';
    $firstLetter = esc(substr($sessName, 0, 1));
    ?>
    <div class="sidebar-footer" data-tooltip="<?= esc($sessName) ?> (<?= esc($sessRole) ?>)">
        <div class="user-avatar-circle">
            <?= $firstLetter ?>
        </div>
        <div class="user-info-text">
            <div class="user-name-label"><?= esc($sessName) ?></div>
            <div class="user-role-label"><?= esc($sessRole) ?></div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        window.refreshPendingCartWidget = function() {
            try {
                const notifyEl = document.getElementById('pendingCartNotification');
                const textEl = document.getElementById('pendingCartCountText');
                const collapsedNotifyEl = document.getElementById('pendingCartCollapsedNotification');
                const collapsedCountEl = document.getElementById('pendingCartCollapsedCount');
                
                // Migrate legacy single pending cart to runchise_pending_carts array if present
                const singleCart = localStorage.getItem('runchise_pending_cart');
                let pendingCarts = [];
                const multiplePending = localStorage.getItem('runchise_pending_carts');
                if (multiplePending) {
                    pendingCarts = JSON.parse(multiplePending);
                }
                
                if (singleCart) {
                    try {
                        const items = JSON.parse(singleCart);
                        if (items && items.length > 0) {
                            pendingCarts.unshift({
                                id: Date.now(),
                                name: 'Order Tertunda #' + (pendingCarts.length + 1),
                                created_at: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }),
                                items: items
                            });
                            localStorage.setItem('runchise_pending_carts', JSON.stringify(pendingCarts));
                        }
                    } catch (e) {
                        console.error("Migration error: ", e);
                    }
                    localStorage.removeItem('runchise_pending_cart');
                }
                
                if (pendingCarts && pendingCarts.length > 0) {
                    if (textEl) {
                        textEl.textContent = `⚡ Antrean Tertunda (${pendingCarts.length})`;
                    }
                    if (collapsedCountEl) {
                        collapsedCountEl.textContent = pendingCarts.length;
                    }
                    if (collapsedNotifyEl) {
                        collapsedNotifyEl.setAttribute('data-tooltip', `Antrean Tertunda (${pendingCarts.length})`);
                    }
                    if (notifyEl) {
                        notifyEl.style.display = 'block';
                    }
                } else {
                    if (textEl) {
                        textEl.textContent = `⚡ Antrean Tertunda (0)`;
                    }
                    if (collapsedCountEl) {
                        collapsedCountEl.textContent = '0';
                    }
                    if (collapsedNotifyEl) {
                        collapsedNotifyEl.setAttribute('data-tooltip', `Antrean Tertunda (0)`);
                    }
                    if (notifyEl) {
                        notifyEl.style.display = 'none';
                    }
                }
            } catch (e) {
                console.error("Failed to parse pending carts from localStorage", e);
            }
        };

        // Initialize on load
        window.refreshPendingCartWidget();

        // Expand sidebar when clicking menu links that have collapse triggers if currently collapsed
        document.querySelectorAll('.menu-link').forEach(link => {
            link.addEventListener('click', (e) => {
                const isCollapsed = document.body.classList.contains('sidebar-collapsed');
                if (isCollapsed) {
                    // If it's a dropdown trigger, prevent expanding/collapsing dropdown while sidebar is collapsed
                    if (link.hasAttribute('data-bs-toggle')) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                    // Expand the sidebar
                    document.body.classList.remove('sidebar-collapsed');
                    localStorage.setItem('sidebar_collapsed', 'false');
                    
                    // Trigger bootstrap collapse to show if it was clicked
                    const targetId = link.getAttribute('data-bs-target');
                    if (targetId) {
                        const targetEl = document.querySelector(targetId);
                        if (targetEl && typeof bootstrap !== 'undefined') {
                            const bsCollapse = bootstrap.Collapse.getInstance(targetEl) || new bootstrap.Collapse(targetEl);
                            bsCollapse.show();
                        }
                    }
                }
            });
        });

        // Touch Swipe/Drag gesture implementation for mobile sidebar
        const sidebar = document.querySelector('.runchise-sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        if (sidebar) {
            let touchStartX = 0;
            let touchStartY = 0;
            let touchCurrentX = 0;
            let isDragging = false;
            let startWidth = 70;

            // Detect if device supports touch
            const isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
            if (isTouchDevice) {
                document.addEventListener('touchstart', (e) => {
                    if (window.innerWidth > 768) return;

                    const touch = e.touches[0];
                    touchStartX = touch.clientX;
                    touchStartY = touch.clientY;
                    const isCollapsed = document.body.classList.contains('sidebar-collapsed');

                    // Start dragging if touch is on the sidebar or overlay
                    const target = e.target;
                    if (sidebar.contains(target) || (overlay && overlay.contains(target))) {
                        isDragging = true;
                        startWidth = isCollapsed ? 70 : 240;
                        sidebar.style.transition = 'none';
                        if (overlay) overlay.style.transition = 'none';
                        sidebar.style.overflowX = 'hidden';
                    }
                }, { passive: true });

                document.addEventListener('touchmove', (e) => {
                    if (!isDragging) return;

                    const touch = e.touches[0];
                    touchCurrentX = touch.clientX;
                    const deltaX = touchCurrentX - touchStartX;
                    const deltaY = touch.clientY - touchStartY;

                    // If scroll is mostly vertical, ignore to allow page scrolling
                    if (Math.abs(deltaY) > Math.abs(deltaX) && Math.abs(deltaX) < 10) {
                        return;
                    }

                    // Calculate new width
                    let newWidth = startWidth + deltaX;
                    newWidth = Math.max(70, Math.min(240, newWidth));

                    sidebar.style.width = newWidth + 'px';
                    sidebar.style.minWidth = newWidth + 'px';

                    // Update overlay opacity based on progress
                    if (overlay) {
                        const progress = (newWidth - 70) / (240 - 70);
                        overlay.style.opacity = progress;
                        overlay.style.pointerEvents = progress > 0.1 ? 'auto' : 'none';
                    }
                }, { passive: false });

                document.addEventListener('touchend', () => {
                    if (!isDragging) return;
                    isDragging = false;

                    // Reset inline transitions and styles
                    sidebar.style.transition = '';
                    if (overlay) overlay.style.transition = '';
                    sidebar.style.overflowX = '';

                    const currentWidth = parseFloat(sidebar.style.width) || 70;
                    
                    // Clear inline styles to fall back to class-based CSS
                    sidebar.style.width = '';
                    sidebar.style.minWidth = '';
                    if (overlay) {
                        overlay.style.opacity = '';
                        overlay.style.pointerEvents = '';
                    }

                    const threshold = 155; // (70 + 240) / 2
                    if (currentWidth > threshold) {
                        document.body.classList.remove('sidebar-collapsed');
                        localStorage.setItem('sidebar_collapsed', 'false');
                    } else {
                        document.body.classList.add('sidebar-collapsed');
                        localStorage.setItem('sidebar_collapsed', 'true');
                    }
                }, { passive: true });
            }
        }
    });

    // Sidebar Toggle Function
    function toggleSidebar(e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        const isCollapsed = document.body.classList.toggle('sidebar-collapsed');
        localStorage.setItem('sidebar_collapsed', isCollapsed ? 'true' : 'false');
    }
</script>
