<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Runchise — Stok di Tangan</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --primary: #E2A794; --primary-dark: #c98570; --bg-dark: #FAF6F3; --bg-card: #FFFFFF; --border-light: rgba(226,167,148,0.25); --text-primary: #2C1E1A; --text-muted: #8A756E; }
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--bg-dark); color: var(--text-primary); min-height: 100vh; margin: 0; }
        .page-layout { display: flex; min-height: 100vh; }
        .main-content { flex: 1; overflow-x: hidden; }
        .page-header { background: rgba(250,246,243,0.8); backdrop-filter: blur(15px); border-bottom: 1px solid var(--border-light); padding: 1.25rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        .page-header h1 { font-size: 1.35rem; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 0.5rem; }
        .content-area { padding: 2rem; max-width: 1200px; margin: 0 auto; }
        .glass-card { background: var(--bg-card); border: 1px solid var(--border-light); border-radius: 16px; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 4px 20px rgba(226,167,148,0.06); }
        .stat-card { background: linear-gradient(135deg, rgba(226,167,148,0.08), rgba(250,246,243,0.95)); border: 1px solid var(--border-light); border-radius: 14px; padding: 1.25rem; text-align: center; }
        .stat-value { font-size: 1.5rem; font-weight: 700; }
        .stat-label { font-size: 0.75rem; color: var(--text-muted); font-weight: 500; margin-top: 0.25rem; }
        .table-premium { width: 100%; border-collapse: separate; border-spacing: 0; }
        .table-premium thead th { background: rgba(226,167,148,0.08); padding: 0.75rem 1rem; font-size: 0.78rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); border-bottom: 2px solid var(--border-light); }
        .table-premium tbody td { padding: 0.7rem 1rem; font-size: 0.85rem; border-bottom: 1px solid rgba(226,167,148,0.1); }
        .table-premium tbody tr:hover { background: rgba(226,167,148,0.04); }
        .stock-low { background: rgba(239,68,68,0.06); }
        .stock-low td { color: #dc2626; }
        .badge-warning-stock { background: rgba(245,158,11,0.12); color: #d97706; padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.72rem; font-weight: 600; }
        .badge-ok-stock { background: rgba(16,185,129,0.12); color: #059669; padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.72rem; font-weight: 600; }
        .user-details { text-align: right; }
        .user-name { font-size: 0.9rem; font-weight: 600; }
        .user-role { font-size: 0.75rem; color: var(--text-muted); }
        .btn-logout { padding: 0.5rem 1rem; background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.15); border-radius: 8px; color: #d94646; font-size: 0.85rem; font-weight: 500; text-decoration: none; }
        .search-box { border: 1px solid var(--border-light); border-radius: 10px; font-size: 0.85rem; padding: 0.5rem 0.75rem; min-width: 250px; }
        .search-box:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(226,167,148,0.15); outline: none; }
    </style>
</head>
<body>
<div class="page-layout">
    <?= view('App\Views\partials\sidebar') ?>
    <div class="main-content">
        <div class="page-header">
            <h1><i class="bi bi-box-seam"></i> Stok di Tangan</h1>
            <div class="d-flex align-items-center gap-3">
                <div class="user-details">
                    <div class="user-name"><?= esc($userName) ?></div>
                    <div class="user-role"><?= esc($userRole) ?></div>
                </div>
                <a href="/auth/logout" class="btn-logout">Sign Out →</a>
            </div>
        </div>

        <div class="content-area">
            <!-- Summary Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-value"><?= count($stocks) ?></div>
                        <div class="stat-label">Total SKU Terdaftar</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-value"><?= number_format($totalItems, 0) ?></div>
                        <div class="stat-label">Total Unit di Tangan</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-value" style="color: <?= $lowStockCount > 0 ? '#dc2626' : '#059669' ?>;">
                            <?= $lowStockCount ?>
                            <?php if ($lowStockCount > 0): ?><i class="bi bi-exclamation-triangle-fill" style="font-size:1rem;"></i><?php endif; ?>
                        </div>
                        <div class="stat-label">Item Stok Rendah</div>
                    </div>
                </div>
            </div>

            <div class="glass-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0"><i class="bi bi-table me-1"></i> Daftar Stok per Produk</h6>
                    <input type="text" class="search-box" placeholder="🔍 Cari produk..." id="searchStock" onkeyup="filterTable()">
                </div>

                <div class="table-responsive">
                    <table class="table-premium" id="stockTable">
                        <thead>
                            <tr>
                                <th>SKU</th>
                                <th>Produk</th>
                                <th>Kategori</th>
                                <th>Cabang</th>
                                <th class="text-center">Qty</th>
                                <th class="text-center">Reorder Point</th>
                                <th class="text-end">Nilai Stok (HPP)</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stocks as $s): ?>
                                <?php
                                    $qty = (float) $s['quantity'];
                                    $isLow = $qty < (float) $s['reorder_point'];
                                    $stockValue = $qty * (float) $s['cost'];
                                ?>
                                <tr class="<?= $isLow ? 'stock-low' : '' ?>">
                                    <td><code><?= esc($s['sku']) ?></code></td>
                                    <td><strong><?= esc($s['name']) ?></strong></td>
                                    <td><?= esc($s['category_name'] ?? 'Uncategorized') ?></td>
                                    <td><?= esc($s['branch_name'] ?? 'Main') ?></td>
                                    <td class="text-center fw-bold"><?= number_format($qty, 0) ?></td>
                                    <td class="text-center"><?= number_format($s['reorder_point'], 0) ?></td>
                                    <td class="text-end">Rp <?= number_format($stockValue, 0, ',', '.') ?></td>
                                    <td>
                                        <?php if ($isLow): ?>
                                            <span class="badge-warning-stock"><i class="bi bi-exclamation-triangle me-1"></i>Rendah</span>
                                        <?php else: ?>
                                            <span class="badge-ok-stock"><i class="bi bi-check-circle me-1"></i>Aman</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr style="background:rgba(226,167,148,0.06); font-weight:700;">
                                <td colspan="4">TOTAL</td>
                                <td class="text-center"><?= number_format($totalItems, 0) ?></td>
                                <td></td>
                                <td class="text-end">Rp <?= number_format($totalValue, 0, ',', '.') ?></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function filterTable() {
    const q = document.getElementById('searchStock').value.toLowerCase();
    document.querySelectorAll('#stockTable tbody tr').forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(q) ? '' : 'none';
    });
}
</script>
</body>
</html>
