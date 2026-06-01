<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><title>Runchise — POS Sessions</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
:root{--bg-dark:#FAF6F3;--bg-card:#FFFFFF;--text-primary:#2C1E1A;--text-muted:#8A756E;--border:rgba(226,167,148,0.25);--primary:#E2A794;}
body{font-family:'Inter',sans-serif;background:var(--bg-dark);color:var(--text-primary);min-height:100vh;padding:2rem;}
.card-nexapos{background:var(--bg-card);border:1px solid var(--border);border-radius:16px;overflow:hidden;box-shadow: 0 4px 20px rgba(226, 167, 148, 0.06);}
.table{color:var(--text-primary);}
.table th{background:#faf5f2;color:var(--text-muted);font-size:0.8rem;text-transform:uppercase;border-bottom:1px solid var(--border);padding:0.75rem 1rem;}
.table td{border-bottom:1px solid var(--border);vertical-align:middle;padding:1rem;}
.badge-open{background:rgba(16,185,129,0.2);color:#10b981;border-radius:20px;padding:0.25rem 0.75rem;font-size:0.75rem;}
.badge-closed{background:rgba(148,163,184,0.1);color:var(--text-muted);border-radius:20px;padding:0.25rem 0.75rem;font-size:0.75rem;}
.btn-primary-nexapos{background:linear-gradient(135deg,#E2A794,#d97757);border:none;color:white;padding:0.5rem 1.25rem;border-radius:10px;font-weight:600;text-decoration:none;}
.pending-session-item {
    background: #ffffff;
    color: var(--text-primary);
    border: none;
    border-bottom: 1px solid var(--border);
    padding: 1rem;
    cursor: pointer;
    transition: all 0.2s;
    text-align: left;
    display: block;
    width: 100%;
}
.pending-session-item:hover {
    background: #faf5f2;
}
.pending-session-item.active {
    background: rgba(226, 167, 148, 0.15);
    color: #b07765;
    font-weight: 600;
    border-left: 4px solid var(--primary);
}
</style></head>
<body>

<div class="d-flex" style="min-height: 100vh;">
    <!-- Shared Premium Sidebar -->
    <?= view('partials/sidebar') ?>

    <!-- Main Content Area -->
    <div class="flex-grow-1" style="overflow-x: hidden; padding: 2rem;">
        <!-- Header Navigation Title -->
        <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom" style="border-color:var(--border) !important;">
            <h2 style="font-size: 1.25rem; font-weight: 700; margin: 0; color: var(--text-primary);">🖥️ POS Shifts & Sessions</h2>
            <span class="text-muted" style="font-size:0.85rem; font-weight: 500;">POS Management</span>
        </div>

        <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 style="font-weight:700; margin:0; font-size:1.1rem;">Active Shifts & Drawer Balances</h4>
            <a href="/pos/terminal" class="btn-primary-nexapos">Open POS Terminal</a>
        </div>

        <div class="card-nexapos">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Branch</th>
                            <th>Opened By</th>
                            <th>Opening Cash</th>
                            <th>Closing Cash</th>
                            <th>Status</th>
                            <th>Opened At</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($sessions)): ?>
                        <?php foreach ($sessions as $s): ?>
                        <tr>
                            <td><?= $s['id'] ?></td>
                            <td>Branch #<?= esc($s['branch_id']) ?></td>
                            <td style="font-weight:600;"><i class="bi bi-person-circle"></i> Cashier #<?= esc($s['user_id']) ?></td>
                            <td>Rp <?= number_format($s['opening_cash']) ?></td>
                            <td><?= $s['closing_cash'] ? 'Rp ' . number_format($s['closing_cash']) : '—' ?></td>
                            <td>
                                <?= $s['status'] === 'Open' ? '<span class="badge-open">Active / Open</span>' : '<span class="badge-closed">Closed</span>' ?>
                            </td>
                            <td style="font-size:0.85rem;color:var(--text-muted);"><?= $s['opened_at'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center py-4" style="color:var(--text-muted);">No sessions found. <a href="/pos/terminal">Open the terminal</a> to start.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Hanging / Pending Purchases Section -->
        <div id="pendingCartSection" class="mt-5" style="display: none;">
            <h4 style="font-weight:700; margin-bottom:1.5rem; font-size:1.1rem; color: var(--text-primary);"><i class="bi bi-clock-history"></i> Antrean Transaksi Tertunda (Hanging Carts)</h4>
            
            <div class="row">
                <!-- Left: List of Pending Carts -->
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="card-nexapos" style="max-height: 400px; overflow-y: auto; border: 1px solid var(--border);">
                        <div class="list-group list-group-flush" id="pendingCartSessionList">
                            <!-- Populated dynamically via JS -->
                        </div>
                    </div>
                </div>

                <!-- Right: Selected Session Details -->
                <div class="col-md-8">
                    <div class="card-nexapos p-4" id="pendingCartDetailsPanel" style="display: none; border: 1px solid var(--border);">
                        <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom" style="border-color: var(--border) !important;">
                            <div>
                                <h5 style="font-weight: 700; margin: 0; color: var(--text-primary);" id="detailsSessionTitle">Order Tertunda #1</h5>
                                <span class="text-muted" style="font-size: 0.85rem;" id="detailsSessionTime">Waktu tunda: 14:53</span>
                            </div>
                            <div class="d-flex gap-2">
                                <button id="detailsCancelBtn" class="btn btn-sm btn-outline-danger" style="border-radius: 8px; font-weight: 600;"><i class="bi bi-trash"></i> Batalkan</button>
                                <button id="detailsResumeBtn" class="btn btn-sm btn-primary-nexapos" style="font-size: 0.85rem; font-weight: 600;"><i class="bi bi-arrow-right-circle"></i> Lanjutkan Pembayaran</button>
                            </div>
                        </div>

                        <div class="table-responsive" style="max-height: 280px; overflow-y: auto;">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th class="text-center">Kuantitas</th>
                                        <th class="text-end">Harga Satuan</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody id="pendingCartDetailsItems">
                                    <!-- Rendered dynamically -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="card-nexapos p-5 text-center text-muted" id="pendingCartNoSelectPrompt" style="border: 1px solid var(--border); background: var(--bg-card);">
                        <i class="bi bi-receipt d-block mb-2" style="font-size: 2.2rem; color: var(--primary); opacity: 0.6;"></i>
                        Pilih antrean transaksi tertunda di sebelah kiri untuk melihat rincian belanja
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const pendingCartSection = document.getElementById('pendingCartSection');
    const pendingCartSessionList = document.getElementById('pendingCartSessionList');
    const pendingCartDetailsPanel = document.getElementById('pendingCartDetailsPanel');
    const pendingCartNoSelectPrompt = document.getElementById('pendingCartNoSelectPrompt');
    const detailsSessionTitle = document.getElementById('detailsSessionTitle');
    const detailsSessionTime = document.getElementById('detailsSessionTime');
    const pendingCartDetailsItems = document.getElementById('pendingCartDetailsItems');
    
    const detailsCancelBtn = document.getElementById('detailsCancelBtn');
    const detailsResumeBtn = document.getElementById('detailsResumeBtn');

    let pendingCarts = [];
    let selectedSessionId = null;

    const fmt = (n) => 'Rp ' + Math.round(n).toLocaleString('id-ID');

    // Migration and fetching logic
    function fetchPendingCarts() {
        const singlePending = localStorage.getItem('runchise_pending_cart');
        const multiplePending = localStorage.getItem('runchise_pending_carts');
        
        if (multiplePending) {
            pendingCarts = JSON.parse(multiplePending);
        } else {
            pendingCarts = [];
        }

        // Migrate single pending cart if found
        if (singlePending) {
            try {
                const items = JSON.parse(singlePending);
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
                console.error(e);
            }
            localStorage.removeItem('runchise_pending_cart');
        }
    }

    function renderPendingCartSessions() {
        fetchPendingCarts();
        
        if (pendingCarts.length === 0) {
            pendingCartSection.style.display = 'none';
            return;
        }

        pendingCartSection.style.display = 'block';
        pendingCartSessionList.innerHTML = '';
        
        pendingCarts.forEach(cart => {
            const btn = document.createElement('button');
            btn.className = 'pending-session-item' + (selectedSessionId === cart.id ? ' active' : '');
            
            const totalAmount = cart.items.reduce((sum, item) => sum + (item.price * item.qty), 0);
            const totalQty = cart.items.reduce((sum, item) => sum + item.qty, 0);

            btn.innerHTML = `
                <div class="d-flex justify-content-between align-items-center">
                    <strong style="font-size:0.9rem; color: var(--text-primary);">${cart.name}</strong>
                    <span style="font-size:0.75rem; color: var(--text-muted);">${cart.created_at}</span>
                </div>
                <div style="font-size:0.75rem; color: var(--text-muted); margin-top:0.25rem;">
                    ${totalQty} item • <span style="font-weight:600; color:var(--primary);">${fmt(totalAmount)}</span>
                </div>
            `;

            btn.addEventListener('click', () => {
                selectedSessionId = cart.id;
                renderDetailsPanel(cart);
                renderPendingCartSessions();
            });

            pendingCartSessionList.appendChild(btn);
        });

        // Update details panel if a session was already selected
        if (selectedSessionId) {
            const activeCart = pendingCarts.find(c => c.id === selectedSessionId);
            if (activeCart) {
                renderDetailsPanel(activeCart);
            } else {
                selectedSessionId = null;
                hideDetailsPanel();
            }
        } else {
            hideDetailsPanel();
        }
    }

    function renderDetailsPanel(cart) {
        pendingCartNoSelectPrompt.style.display = 'none';
        pendingCartDetailsPanel.style.display = 'block';
        
        detailsSessionTitle.textContent = cart.name;
        detailsSessionTime.textContent = 'Waktu tunda: ' + cart.created_at;
        
        pendingCartDetailsItems.innerHTML = '';
        cart.items.forEach(item => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td style="font-weight:600; color: var(--text-primary);"><span style="font-size:1.25rem; margin-right:0.5rem;">📦</span> ${item.name}</td>
                <td class="text-center" style="font-weight:700; color: var(--text-primary);">${item.qty}x</td>
                <td class="text-end" style="color:var(--text-muted);">${fmt(item.price)}</td>
                <td class="text-end" style="font-weight:700; color: var(--text-primary);">${fmt(item.price * item.qty)}</td>
            `;
            pendingCartDetailsItems.appendChild(tr);
        });

        // Setup button handlers
        detailsCancelBtn.onclick = () => {
            if (confirm('Apakah Anda yakin ingin membatalkan transaksi tertunda ini?')) {
                pendingCarts = pendingCarts.filter(c => c.id !== cart.id);
                localStorage.setItem('runchise_pending_carts', JSON.stringify(pendingCarts));
                selectedSessionId = null;
                renderPendingCartSessions();
                
                // Refresh sidebar widget if available
                if (window.refreshPendingCartWidget) window.refreshPendingCartWidget();
            }
        };

        detailsResumeBtn.onclick = () => {
            // Restore cart items temporarily
            localStorage.setItem('runchise_pending_cart', JSON.stringify(cart.items));
            
            // Remove from queue list
            pendingCarts = pendingCarts.filter(c => c.id !== cart.id);
            localStorage.setItem('runchise_pending_carts', JSON.stringify(pendingCarts));
            
            // Refresh sidebar widget if available
            if (window.refreshPendingCartWidget) window.refreshPendingCartWidget();
            
            // Redirect to terminal
            window.location.href = '/pos/terminal';
        };
    }

    function hideDetailsPanel() {
        pendingCartDetailsPanel.style.display = 'none';
        pendingCartNoSelectPrompt.style.display = 'block';
    }

    renderPendingCartSessions();
});
</script>
</body>
</html>
