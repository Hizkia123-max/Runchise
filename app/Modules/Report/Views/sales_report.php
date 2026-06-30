<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Runchise — Report Penjualan</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root { --primary: #E2A794; --primary-dark: #c98570; --bg-dark: #FAF6F3; --bg-card: #FFFFFF; --border-light: rgba(226,167,148,0.25); --text-primary: #2C1E1A; --text-muted: #8A756E; --success: #10b981; --info: #3b82f6; }
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--bg-dark); color: var(--text-primary); min-height: 100vh; margin: 0; }
        .page-layout { display: flex; min-height: 100vh; }
        .main-content { flex: 1; overflow-x: hidden; }
        .page-header { background: rgba(250,246,243,0.8); backdrop-filter: blur(15px); border-bottom: 1px solid var(--border-light); padding: 1.25rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        .page-header h1 { font-size: 1.35rem; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 0.5rem; }
        .content-area { padding: 2rem; max-width: 1200px; margin: 0 auto; }
        .glass-card { background: var(--bg-card); border: 1px solid var(--border-light); border-radius: 16px; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 4px 20px rgba(226,167,148,0.06); }
        .filter-bar { display: flex; gap: 0.75rem; align-items: end; flex-wrap: wrap; }
        .form-control, .form-select { border: 1px solid var(--border-light); border-radius: 10px; font-size: 0.85rem; padding: 0.5rem 0.75rem; }
        .form-control:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(226,167,148,0.15); }
        .form-label { font-size: 0.75rem; font-weight: 600; color: var(--text-muted); margin-bottom: 0.25rem; }
        .btn-filter { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border: none; color: white; padding: 0.5rem 1.25rem; border-radius: 10px; font-weight: 600; font-size: 0.82rem; }
        .btn-filter:hover { color: white; transform: translateY(-1px); box-shadow: 0 4px 15px rgba(226,167,148,0.4); }
        .mode-btn { padding: 0.4rem 0.9rem; border-radius: 8px; font-size: 0.78rem; font-weight: 600; border: 1px solid var(--border-light); background: white; color: var(--text-muted); cursor: pointer; transition: all 0.2s; text-decoration: none; }
        .mode-btn:hover, .mode-btn.active { background: var(--primary); color: white; border-color: var(--primary); }
        .stat-card { background: linear-gradient(135deg, rgba(226,167,148,0.08), rgba(250,246,243,0.95)); border: 1px solid var(--border-light); border-radius: 14px; padding: 1.25rem; text-align: center; transition: transform 0.2s; }
        .stat-card:hover { transform: translateY(-2px); }
        .stat-value { font-size: 1.5rem; font-weight: 700; color: var(--text-primary); }
        .stat-label { font-size: 0.75rem; color: var(--text-muted); font-weight: 500; margin-top: 0.25rem; }
        .table-premium { width: 100%; border-collapse: separate; border-spacing: 0; }
        .table-premium thead th { background: rgba(226,167,148,0.08); padding: 0.75rem 1rem; font-size: 0.78rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); border-bottom: 2px solid var(--border-light); }
        .table-premium tbody td { padding: 0.7rem 1rem; font-size: 0.85rem; border-bottom: 1px solid rgba(226,167,148,0.1); }
        .table-premium tbody tr:hover { background: rgba(226,167,148,0.04); }
        .chart-container { position: relative; height: 300px; }
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
            <h1><i class="bi bi-bar-chart-line"></i> Report Penjualan</h1>
            <div class="d-flex align-items-center gap-3">
                <div class="user-details">
                    <div class="user-name"><?= esc($userName) ?></div>
                    <div class="user-role"><?= esc($userRole) ?></div>
                </div>
                <a href="/auth/logout" class="btn-logout">Sign Out →</a>
            </div>
        </div>

        <div class="content-area">
            <!-- Filter Bar -->
            <div class="glass-card">
                <form method="GET" action="/report/sales">
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
                            <label class="form-label">Mode</label>
                            <select name="mode" class="form-select">
                                <option value="daily" <?= $mode === 'daily' ? 'selected' : '' ?>>Harian</option>
                                <option value="monthly" <?= $mode === 'monthly' ? 'selected' : '' ?>>Bulanan</option>
                                <option value="yearly" <?= $mode === 'yearly' ? 'selected' : '' ?>>Tahunan</option>
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-filter"><i class="bi bi-funnel me-1"></i> Filter</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Summary Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-value">Rp <?= number_format($summary['total_sales'], 0, ',', '.') ?></div>
                        <div class="stat-label">Total Penjualan</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-value"><?= number_format($summary['total_transactions']) ?></div>
                        <div class="stat-label">Jumlah Transaksi</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-value">Rp <?= number_format($summary['avg_transaction'] ?? 0, 0, ',', '.') ?></div>
                        <div class="stat-label">Rata-rata / Transaksi</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-value">Rp <?= number_format($summary['total_discount'], 0, ',', '.') ?></div>
                        <div class="stat-label">Total Diskon</div>
                    </div>
                </div>
            </div>

            <!-- Chart -->
            <div class="glass-card">
                <h6 class="fw-bold mb-3"><i class="bi bi-graph-up me-1"></i> Grafik Penjualan (<?= ucfirst($mode) ?>)</h6>
                <div class="chart-container">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <!-- Payment Breakdown -->
            <?php if (!empty($paymentBreakdown)): ?>
            <div class="glass-card">
                <h6 class="fw-bold mb-3"><i class="bi bi-credit-card me-1"></i> Breakdown Metode Pembayaran</h6>
                <div class="row g-3">
                    <?php foreach ($paymentBreakdown as $pm): ?>
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="stat-value">Rp <?= number_format($pm['amount'], 0, ',', '.') ?></div>
                                <div class="stat-label"><?= esc($pm['payment_method']) ?> (<?= $pm['count'] ?> trx)</div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Transaction Table -->
            <div class="glass-card">
                <h6 class="fw-bold mb-3"><i class="bi bi-receipt me-1"></i> Detail Transaksi</h6>
                <?php if (empty($transactions)): ?>
                    <p class="text-muted text-center py-3">Tidak ada transaksi pada periode ini.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table-premium">
                            <thead>
                                <tr>
                                    <th>Invoice</th>
                                    <th>Tanggal</th>
                                    <th>Subtotal</th>
                                    <th>Diskon</th>
                                    <th>Pajak</th>
                                    <th>Total</th>
                                    <th>Pembayaran</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($transactions as $tx): ?>
                                    <tr>
                                        <td><strong><?= esc($tx['invoice_number']) ?></strong></td>
                                        <td><?= date('d M Y H:i', strtotime($tx['created_at'])) ?></td>
                                        <td>Rp <?= number_format($tx['subtotal'], 0, ',', '.') ?></td>
                                        <td>Rp <?= number_format($tx['discount_amount'], 0, ',', '.') ?></td>
                                        <td>Rp <?= number_format($tx['tax_amount'], 0, ',', '.') ?></td>
                                        <td><strong>Rp <?= number_format($tx['total'], 0, ',', '.') ?></strong></td>
                                        <td><?= esc($tx['payment_method']) ?></td>
                                        <td>
                                            <span style="padding:0.2rem 0.6rem;border-radius:20px;font-size:0.72rem;font-weight:600;background:<?= $tx['payment_status'] === 'Paid' ? 'rgba(16,185,129,0.12)' : 'rgba(239,68,68,0.12)' ?>;color:<?= $tx['payment_status'] === 'Paid' ? '#059669' : '#dc2626' ?>;">
                                                <?= esc($tx['payment_status']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
const grouped = <?= json_encode($grouped) ?>;
const labels = grouped.map(g => g.period);
const amounts = grouped.map(g => parseFloat(g.amount));

new Chart(document.getElementById('salesChart'), {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Penjualan',
            data: amounts,
            backgroundColor: 'rgba(226, 167, 148, 0.6)',
            borderColor: '#c98570',
            borderWidth: 1,
            borderRadius: 8,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { callback: v => 'Rp ' + v.toLocaleString('id-ID') }
            }
        }
    }
});
</script>
</body>
</html>
