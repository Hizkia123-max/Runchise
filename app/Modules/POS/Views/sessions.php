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
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h4 style="font-weight:700; margin:0; font-size:1.1rem; color: var(--text-primary);">⚡ Transaksi Kasir Tertunda (Hanging Cart)</h4>
                <div class="d-flex gap-2">
                    <button id="clearPendingCartBtn" class="btn btn-sm btn-outline-danger" style="border-radius: 8px; font-weight: 600;"><i class="bi bi-trash"></i> Batalkan Transaksi</button>
                    <a href="/pos/terminal" class="btn btn-sm btn-primary-nexapos" style="font-size: 0.85rem;"><i class="bi bi-arrow-right-circle"></i> Lanjutkan Pembayaran</a>
                </div>
            </div>

            <div class="card-nexapos p-4">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th class="text-center">Kuantitas</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody id="pendingCartItemsList">
                            <!-- Rendered dynamically via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const pendingCartSection = document.getElementById('pendingCartSection');
    const pendingCartItemsList = document.getElementById('pendingCartItemsList');
    const clearPendingCartBtn = document.getElementById('clearPendingCartBtn');

    const fmt = (n) => 'Rp ' + Math.round(n).toLocaleString('id-ID');

    function renderPendingCart() {
        const pending = localStorage.getItem('runchise_pending_cart');
        if (pending) {
            const cartItems = JSON.parse(pending);
            if (cartItems && cartItems.length > 0) {
                pendingCartItemsList.innerHTML = '';
                cartItems.forEach(item => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td style="font-weight:600; color: var(--text-primary);"><span style="font-size:1.25rem; margin-right:0.5rem;">📦</span> ${item.name}</td>
                        <td class="text-center" style="font-weight:700; color: var(--text-primary);">${item.qty}x</td>
                        <td class="text-end" style="color:var(--text-muted);">${fmt(item.price)}</td>
                        <td class="text-end" style="font-weight:700; color: var(--text-primary);">${fmt(item.price * item.qty)}</td>
                    `;
                    pendingCartItemsList.appendChild(tr);
                });
                pendingCartSection.style.display = 'block';
                return;
            }
        }
        pendingCartSection.style.display = 'none';
    }

    if (clearPendingCartBtn) {
        clearPendingCartBtn.addEventListener('click', () => {
            if (confirm('Apakah Anda yakin ingin membatalkan transaksi tertunda ini?')) {
                localStorage.removeItem('runchise_pending_cart');
                renderPendingCart();
                // Trigger sidebar widget reload if exists
                if (window.refreshPendingCartWidget) {
                    window.refreshPendingCartWidget();
                } else {
                    location.reload();
                }
            }
        });
    }

    renderPendingCart();
});
</script>
</body>
</html>
