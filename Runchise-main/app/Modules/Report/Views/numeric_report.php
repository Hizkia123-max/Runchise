<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Runchise — Reporting Numerik</title>
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
        .filter-bar { display: flex; gap: 0.75rem; align-items: end; flex-wrap: wrap; }
        .form-control { border: 1px solid var(--border-light); border-radius: 10px; font-size: 0.85rem; padding: 0.5rem 0.75rem; }
        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(226,167,148,0.15); }
        .form-label { font-size: 0.75rem; font-weight: 600; color: var(--text-muted); margin-bottom: 0.25rem; }
        .btn-filter { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border: none; color: white; padding: 0.5rem 1.25rem; border-radius: 10px; font-weight: 600; font-size: 0.82rem; }
        .btn-filter:hover { color: white; }
        .metric-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
        .metric-card { background: var(--bg-card); border: 1px solid var(--border-light); border-radius: 14px; padding: 1.25rem; display: flex; align-items: center; gap: 0.75rem; transition: transform 0.2s; }
        .metric-card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(226,167,148,0.12); }
        .metric-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
        .metric-value { font-size: 1.15rem; font-weight: 700; }
        .metric-label { font-size: 0.72rem; color: var(--text-muted); font-weight: 500; }
        .icon-revenue { background: rgba(16,185,129,0.12); color: #059669; }
        .icon-cogs { background: rgba(245,158,11,0.12); color: #d97706; }
        .icon-profit { background: rgba(59,130,246,0.12); color: #2563eb; }
        .icon-waste { background: rgba(239,68,68,0.12); color: #dc2626; }
        .icon-purchase { background: rgba(139,92,246,0.12); color: #7c3aed; }
        .icon-net { background: rgba(16,185,129,0.12); color: #059669; }
        .table-premium { width: 100%; border-collapse: separate; border-spacing: 0; }
        .table-premium thead th { background: rgba(226,167,148,0.08); padding: 0.75rem 1rem; font-size: 0.78rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); border-bottom: 2px solid var(--border-light); }
        .table-premium tbody td { padding: 0.7rem 1rem; font-size: 0.85rem; border-bottom: 1px solid rgba(226,167,148,0.1); }
        .table-premium tbody tr:hover { background: rgba(226,167,148,0.04); }
        .section-title { font-size: 1rem; font-weight: 600; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; }
        .user-details { text-align: right; }
        .user-name { font-size: 0.9rem; font-weight: 600; }
        .user-role { font-size: 0.75rem; color: var(--text-muted); }
        .btn-logout { padding: 0.5rem 1rem; background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.15); border-radius: 8px; color: #d94646; font-size: 0.85rem; font-weight: 500; text-decoration: none; }
    </style>
</head>
<body>
<div class="page-layout">
    <?= view('App\Views\partials\sidebar') ?>
    <div class="main-content">
        <div class="page-header">
            <h1><i class="bi bi-123"></i> Reporting Numerik</h1>
            <div class="d-flex align-items-center gap-3">
                <div class="user-details">
                    <div class="user-name"><?= esc($userName) ?></div>
                    <div class="user-role"><?= esc($userRole) ?></div>
                </div>
                <a href="/auth/logout" class="btn-logout">Sign Out →</a>
            </div>
        </div>

        <div class="content-area">
            <!-- Filter -->
            <div class="glass-card">
                <form method="GET" action="/report/numeric">
                    <div class="filter-bar">
                        <div>
                            <label class="form-label">Dari Tanggal</label>
                            <input type="date" name="date_from" class="form-control" value="<?= esc($dateFrom) ?>">
                        </div>
                        <div>
                            <label class="form-label">Sampai Tanggal</label>
                            <input type="date" name="date_to" class="form-control" value="<?= esc($dateTo) ?>">
                        </div>
                        <div>
                            <button type="submit" class="btn btn-filter"><i class="bi bi-funnel me-1"></i> Filter</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Financial Summary -->
            <div class="metric-grid">
                <div class="metric-card">
                    <div class="metric-icon icon-revenue"><i class="bi bi-cash-stack"></i></div>
                    <div>
                        <div class="metric-value">Rp <?= number_format($totalRevenue, 0, ',', '.') ?></div>
                        <div class="metric-label">Total Revenue</div>
                    </div>
                </div>
                <div class="metric-card">
                    <div class="metric-icon icon-cogs"><i class="bi bi-gear"></i></div>
                    <div>
                        <div class="metric-value">Rp <?= number_format($totalCOGS, 0, ',', '.') ?></div>
                        <div class="metric-label">COGS (HPP)</div>
                    </div>
                </div>
                <div class="metric-card">
                    <div class="metric-icon icon-profit"><i class="bi bi-graph-up-arrow"></i></div>
                    <div>
                        <div class="metric-value" style="color:<?= $grossProfit >= 0 ? '#059669' : '#dc2626' ?>">Rp <?= number_format($grossProfit, 0, ',', '.') ?></div>
                        <div class="metric-label">Gross Profit</div>
                    </div>
                </div>
                <div class="metric-card">
                    <div class="metric-icon icon-waste"><i class="bi bi-trash3"></i></div>
                    <div>
                        <div class="metric-value">Rp <?= number_format($totalWaste, 0, ',', '.') ?></div>
                        <div class="metric-label">Kerugian Waste</div>
                    </div>
                </div>
                <div class="metric-card">
                    <div class="metric-icon icon-purchase"><i class="bi bi-cart-check"></i></div>
                    <div>
                        <div class="metric-value">Rp <?= number_format($totalPurchase, 0, ',', '.') ?></div>
                        <div class="metric-label">Total Pembelian</div>
                    </div>
                </div>
                <div class="metric-card">
                    <div class="metric-icon icon-net"><i class="bi bi-wallet2"></i></div>
                    <div>
                        <div class="metric-value" style="color:<?= $netProfit >= 0 ? '#059669' : '#dc2626' ?>">Rp <?= number_format($netProfit, 0, ',', '.') ?></div>
                        <div class="metric-label">Net Profit</div>
                    </div>
                </div>
            </div>

            <!-- Payment Breakdown -->
            <?php if (!empty($paymentBreakdown)): ?>
            <div class="glass-card">
                <div class="section-title"><i class="bi bi-credit-card"></i> Breakdown Metode Pembayaran</div>
                <div class="table-responsive">
                    <table class="table-premium">
                        <thead>
                            <tr>
                                <th>Metode</th>
                                <th class="text-center">Jumlah Trx</th>
                                <th class="text-end">Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($paymentBreakdown as $pm): ?>
                                <tr>
                                    <td><strong><?= esc($pm['payment_method']) ?></strong></td>
                                    <td class="text-center"><?= number_format($pm['count']) ?></td>
                                    <td class="text-end fw-bold">Rp <?= number_format($pm['amount'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

            <!-- Category Breakdown -->
            <?php if (!empty($categorySales)): ?>
            <div class="glass-card">
                <div class="section-title"><i class="bi bi-tags"></i> Penjualan per Kategori</div>
                <div class="table-responsive">
                    <table class="table-premium">
                        <thead>
                            <tr>
                                <th>Kategori</th>
                                <th class="text-center">Qty Terjual</th>
                                <th class="text-end">Total Penjualan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categorySales as $cs): ?>
                                <tr>
                                    <td><strong><?= esc($cs['category_name'] ?? 'Uncategorized') ?></strong></td>
                                    <td class="text-center"><?= number_format($cs['qty_sold'], 0) ?></td>
                                    <td class="text-end fw-bold">Rp <?= number_format($cs['total_sales'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

            <!-- Product Detail -->
            <?php if (!empty($productSales)): ?>
            <div class="glass-card">
                <div class="section-title"><i class="bi bi-box-seam"></i> Detail Penjualan per Produk</div>
                <div class="table-responsive">
                    <table class="table-premium">
                        <thead>
                            <tr>
                                <th>SKU</th>
                                <th>Produk</th>
                                <th class="text-center">Qty Terjual</th>
                                <th class="text-end">Total Sales</th>
                                <th class="text-end">Total COGS</th>
                                <th class="text-end">Margin</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($productSales as $ps): ?>
                                <?php $margin = (float)$ps['total_sales'] - (float)$ps['total_cogs']; ?>
                                <tr>
                                    <td><code><?= esc($ps['sku']) ?></code></td>
                                    <td><strong><?= esc($ps['name']) ?></strong></td>
                                    <td class="text-center"><?= number_format($ps['qty_sold'], 0) ?></td>
                                    <td class="text-end">Rp <?= number_format($ps['total_sales'], 0, ',', '.') ?></td>
                                    <td class="text-end">Rp <?= number_format($ps['total_cogs'], 0, ',', '.') ?></td>
                                    <td class="text-end fw-bold" style="color:<?= $margin >= 0 ? '#059669' : '#dc2626' ?>">Rp <?= number_format($margin, 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
