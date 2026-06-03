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
    .runchise-sidebar > div {
        width: 240px;
        min-width: 240px;
        flex-shrink: 0;
    }

    /* Collapsed state: completely slide off-screen */
    body.sidebar-collapsed .runchise-sidebar {
        width: 0 !important;
        min-width: 0 !important;
        overflow: hidden !important;
        /* box-shadow: none; */
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
    body.sidebar-collapsed .sidebar-floating-toggle {
        opacity: 1;
        pointer-events: auto;
        transform: scale(1);
    }

    /* Transition for headers/nav to slide smoothly when sidebar collapses/expands */
    .flex-grow-1 > :first-child,
    .pos-header {
        transition: padding-left 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }

    /* Header shifts to prevent overlap with floating toggle button */
    body.sidebar-collapsed .flex-grow-1 > :first-child {
        padding-left: 4.5rem !important;
    }
    body.sidebar-collapsed .pos-header {
        padding-left: 4.5rem !important;
    }

    /* Ensure POS layout takes full screen width when sidebar is collapsed */
    .pos-layout {
        transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), max-width 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }
    body.sidebar-collapsed .pos-layout {
        width: 100vw !important;
        max-width: 100vw !important;
    }
</style>

<!-- Floating Toggle Button (Visible when sidebar is collapsed) -->
<button class="sidebar-floating-toggle" id="sidebarFloatingToggle" onclick="toggleSidebar(event)">
    <i class="bi bi-list"></i>
</button>

<div class="runchise-sidebar">
    <div>
        <!-- Top Outlet Brand Header -->
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

        <!-- Pending Cart Session Resume Notification Widget -->
        <a href="/pos/sessions" class="pending-cart-alert" id="pendingCartNotification">
            <div class="d-flex align-items-center gap-2">
                <span class="spinner-grow spinner-grow-sm text-light" role="status"></span>
                <span style="font-weight:700; font-size:0.72rem; letter-spacing:0.02em;" id="pendingCartCountText">⚡ Antrean Tertunda (0)</span>
            </div>
            <div style="font-size:0.68rem; color:rgba(255,255,255,0.9); margin-top:0.25rem;">Klik untuk mengelola transaksi tertunda.</div>
        </a>

        <!-- Sidebar Navigation List -->
        <ul class="sidebar-menu">
            <!-- Dashboard -->
            <li class="menu-item">
                <a href="/dashboard" class="menu-link <?= (uri_string() === 'dashboard') ? 'active' : '' ?>">
                    <div class="menu-link-content">
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard Utama</span>
                    </div>
                </a>
            </li>

            <!-- POS & Kasir -->
            <li class="menu-item">
                <button class="menu-link" data-bs-toggle="collapse" data-bs-target="#menu-kasir" aria-expanded="true">
                    <div class="menu-link-content">
                        <i class="bi bi-pc-display-horizontal"></i>
                        <span>Kasir & Transaksi</span>
                    </div>
                    <i class="bi bi-chevron-down" style="font-size: 0.75rem;"></i>
                </button>
                <div class="collapse show" id="menu-kasir">
                    <ul class="submenu-list">
                        <li>
                            <a href="/pos/terminal" class="submenu-link <?= (uri_string() === 'pos/terminal' && empty(service('request')->getGet('cat'))) ? 'active' : '' ?>">
                                <i class="bi bi-calculator me-1"></i> Produk Item
                            </a>
                        </li>
                        <li>
                            <a href="/pos/terminal?cat=food" class="submenu-link <?= (uri_string() === 'pos/terminal' && service('request')->getGet('cat') === 'food') ? 'active' : '' ?>">
                                <i class="bi bi-egg-fried me-1"></i> Food & Beverage
                            </a>
                        </li>
                        <li>
                            <a href="/pos/terminal?cat=retail" class="submenu-link <?= (uri_string() === 'pos/terminal' && service('request')->getGet('cat') === 'retail') ? 'active' : '' ?>">
                                <i class="bi bi-bag-check me-1"></i> Retail
                            </a>
                        </li>
                        <li>
                            <a href="/pos/terminal?cat=electronics" class="submenu-link <?= (uri_string() === 'pos/terminal' && service('request')->getGet('cat') === 'electronics') ? 'active' : '' ?>">
                                <i class="bi bi-laptop me-1"></i> Electronics
                            </a>
                        </li>
                        <li>
                            <a href="/pos/terminal?cat=fashion" class="submenu-link <?= (uri_string() === 'pos/terminal' && service('request')->getGet('cat') === 'fashion') ? 'active' : '' ?>">
                                <i class="bi bi-sunglasses me-1"></i> Fashion
                            </a>
                        </li>
                        <li>
                            <a href="/pos/terminal?cat=services" class="submenu-link <?= (uri_string() === 'pos/terminal' && service('request')->getGet('cat') === 'services') ? 'active' : '' ?>">
                                <i class="bi bi-tools me-1"></i> Services
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Katalog -->
            <li class="menu-item">
                <button class="menu-link" data-bs-toggle="collapse" data-bs-target="#menu-catalog" aria-expanded="true">
                    <div class="menu-link-content">
                        <i class="bi bi-box-seam-fill"></i>
                        <span>Katalog Produk</span>
                    </div>
                    <i class="bi bi-chevron-down" style="font-size: 0.75rem;"></i>
                </button>
                <div class="collapse show" id="menu-catalog">
                    <ul class="submenu-list">
                        <li>
                            <a href="/inventory/products" class="submenu-link <?= (uri_string() === 'inventory/products') ? 'active' : '' ?>">
                                <i class="bi bi-tags me-1"></i> Produk & Kategori
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Stok -->
            <li class="menu-item">
                <button class="menu-link" data-bs-toggle="collapse" data-bs-target="#menu-stok" aria-expanded="true">
                    <div class="menu-link-content">
                        <i class="bi bi-graph-up-arrow"></i>
                        <span>Stok & Inventori</span>
                    </div>
                    <i class="bi bi-chevron-down" style="font-size: 0.75rem;"></i>
                </button>
                <div class="collapse show" id="menu-stok">
                    <ul class="submenu-list">
                        <li>
                            <a href="/inventory/stock" class="submenu-link <?= (uri_string() === 'inventory/stock') ? 'active' : '' ?>">
                                <i class="bi bi-card-checklist me-1"></i> Stock Levels
                            </a>
                        </li>
                        <li>
                            <a href="/inventory/opname" class="submenu-link <?= (uri_string() === 'inventory/opname') ? 'active' : '' ?>">
                                <i class="bi bi-pencil-square me-1"></i> Stock Opname
                            </a>
                        </li>
                        <li>
                            <a href="/inventory/transfers" class="submenu-link <?= (uri_string() === 'inventory/transfers') ? 'active' : '' ?>">
                                <i class="bi bi-arrow-left-right me-1"></i> Stock Transfers
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Laporan Finansial -->
            <li class="menu-item">
                <button class="menu-link" data-bs-toggle="collapse" data-bs-target="#menu-analytics" aria-expanded="true">
                    <div class="menu-link-content">
                        <i class="bi bi-journal-check"></i>
                        <span>Laporan Finansial</span>
                    </div>
                    <i class="bi bi-chevron-down" style="font-size: 0.75rem;"></i>
                </button>
                <div class="collapse show" id="menu-analytics">
                    <ul class="submenu-list">
                        <li>
                            <a href="/pos/analytics" class="submenu-link <?= (uri_string() === 'pos/analytics') ? 'active' : '' ?>">
                                <i class="bi bi-cash-stack me-1"></i> Laba Rugi & Laporan
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        window.refreshPendingCartWidget = function() {
            try {
                const notifyEl = document.getElementById('pendingCartNotification');
                const textEl = document.getElementById('pendingCartCountText');
                
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
                    if (notifyEl) {
                        notifyEl.style.display = 'block';
                    }
                } else {
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
