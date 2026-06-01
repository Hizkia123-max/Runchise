<style>
    .runchise-sidebar {
        width: 280px;
        min-width: 280px;
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
        padding: 1.25rem 1.5rem;
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
        font-size: 1.75rem;
        color: white;
    }
    .outlet-name {
        font-size: 0.95rem;
        font-weight: 700;
        line-height: 1.2;
    }
    .outlet-sub {
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.88);
        font-weight: 500;
    }
    .outlet-toggle-btn {
        width: 32px;
        height: 32px;
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
        padding: 1rem 0.75rem;
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
        padding: 0.75rem 1rem;
        color: rgba(255, 255, 255, 0.95);
        text-decoration: none;
        border-radius: 10px;
        font-size: 0.9rem;
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
        gap: 0.85rem;
    }
    .menu-link i {
        font-size: 1.15rem;
    }

    /* Submenu styling */
    .submenu-list {
        list-style: none;
        padding-left: 2.85rem;
        margin: 0.15rem 0 0.5rem 0;
    }
    .submenu-link {
        display: block;
        padding: 0.45rem 1rem;
        color: rgba(255, 255, 255, 0.88);
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.2s;
    }
    .submenu-link:hover, .submenu-link.active {
        color: white;
        background-color: rgba(255, 255, 255, 0.12);
    }

    /* Pending Cart Pulse Notification */
    .pending-cart-alert {
        margin: 0.75rem 1rem 0.25rem 1rem;
        background: linear-gradient(135deg, #ff9f00, #ff5e00);
        border-radius: 12px;
        padding: 0.75rem;
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
</style>

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
            <button class="outlet-toggle-btn" onclick="location.href='/dashboard'">
                <i class="bi bi-list-nested"></i>
            </button>
        </div>

        <!-- Pending Cart Session Resume Notification Widget -->
        <a href="/pos/terminal?restore=1" class="pending-cart-alert" id="pendingCartNotification">
            <div class="d-flex align-items-center gap-2">
                <span class="spinner-grow spinner-grow-sm text-light" role="status"></span>
                <span style="font-weight:700; font-size:0.8rem; letter-spacing:0.02em;">⚡ Keranjang Tertunda!</span>
            </div>
            <div style="font-size:0.75rem; color:rgba(255,255,255,0.9); margin-top:0.25rem;">Klik untuk melanjutkan transaksi kasir.</div>
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
                            <a href="/pos/terminal" class="submenu-link <?= (uri_string() === 'pos/terminal') ? 'active' : '' ?>">
                                <i class="bi bi-calculator me-1"></i> POS Terminal
                            </a>
                        </li>
                        <li>
                            <a href="/pos/sessions" class="submenu-link <?= (uri_string() === 'pos/sessions') ? 'active' : '' ?>">
                                <i class="bi bi-clock-history me-1"></i> POS Shifts & Sessions
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
        // Client-side local storage scan for pending POS carts
        try {
            const pendingCartStr = localStorage.getItem('runchise_pending_cart');
            if (pendingCartStr) {
                const pendingCart = JSON.parse(pendingCartStr);
                if (pendingCart && pendingCart.length > 0) {
                    const notifyEl = document.getElementById('pendingCartNotification');
                    if (notifyEl) {
                        notifyEl.style.display = 'block';
                    }
                }
            }
        } catch (e) {
            console.error("Failed to parse pending cart from localStorage", e);
        }
    });
</script>
