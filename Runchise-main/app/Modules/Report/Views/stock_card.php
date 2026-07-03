<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Runchise — Kartu Stok</title>
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
        .form-control, .form-select { border: 1px solid var(--border-light); border-radius: 10px; font-size: 0.85rem; padding: 0.5rem 0.75rem; }
        .form-control:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(226,167,148,0.15); }
        .form-label { font-size: 0.75rem; font-weight: 600; color: var(--text-muted); margin-bottom: 0.25rem; }
        .btn-filter { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border: none; color: white; padding: 0.5rem 1.25rem; border-radius: 10px; font-weight: 600; font-size: 0.82rem; }
        .btn-filter:hover { color: white; }
        .table-premium { width: 100%; border-collapse: separate; border-spacing: 0; }
        .table-premium thead th { background: rgba(226,167,148,0.08); padding: 0.75rem 1rem; font-size: 0.78rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); border-bottom: 2px solid var(--border-light); }
        .table-premium tbody td { padding: 0.7rem 1rem; font-size: 0.85rem; border-bottom: 1px solid rgba(226,167,148,0.1); }
        .table-premium tbody tr:hover { background: rgba(226,167,148,0.04); }
        .badge-in { background: rgba(16,185,129,0.12); color: #059669; padding: 0.25rem 0.6rem; border-radius: 20px; font-size: 0.72rem; font-weight: 600; }
        .badge-out { background: rgba(239,68,68,0.12); color: #dc2626; padding: 0.25rem 0.6rem; border-radius: 20px; font-size: 0.72rem; font-weight: 600; }
        .product-header { background: linear-gradient(135deg, rgba(226,167,148,0.1), rgba(250,246,243,0.95)); border: 1px solid var(--border-light); border-radius: 12px; padding: 1rem 1.5rem; margin-bottom: 1rem; }
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
            <h1><i class="bi bi-journal-text"></i> Kartu Stok</h1>
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
                <form method="GET" action="/report/stock-card">
                    <div class="filter-bar">
                        <div style="min-width: 250px;">
                            <label class="form-label">Pilih Produk</label>
                            <select name="product_id" class="form-select" required>
                                <option value="">— Pilih Produk —</option>
                                <?php foreach ($products as $p): ?>
                                    <option value="<?= $p['id'] ?>" <?= ($productId == $p['id']) ? 'selected' : '' ?>><?= esc($p['sku'] . ' — ' . $p['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Dari Tanggal</label>
                            <input type="date" name="date_from" class="form-control" value="<?= esc($dateFrom) ?>">
                        </div>
                        <div>
                            <label class="form-label">Sampai Tanggal</label>
                            <input type="date" name="date_to" class="form-control" value="<?= esc($dateTo) ?>">
                        </div>
                        <div>
                            <button type="submit" class="btn btn-filter"><i class="bi bi-search me-1"></i> Tampilkan</button>
                        </div>
                    </div>
                </form>
            </div>

            <?php if ($selectedProduct): ?>
                <div class="product-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0 fw-bold"><?= esc($selectedProduct['name']) ?></h5>
                            <small class="text-muted">SKU: <?= esc($selectedProduct['sku']) ?></small>
                        </div>
                        <div class="text-end">
                            <small class="text-muted">Periode</small>
                            <div class="fw-bold" style="font-size:0.85rem;"><?= date('d M Y', strtotime($dateFrom)) ?> — <?= date('d M Y', strtotime($dateTo)) ?></div>
                        </div>
                    </div>
                </div>

                <div class="glass-card">
                    <?php if (empty($entries)): ?>
                        <p class="text-muted text-center py-3">Tidak ada riwayat stok pada periode ini.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table-premium">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Tipe</th>
                                        <th>Referensi</th>
                                        <th>Keterangan</th>
                                        <th class="text-center">Masuk</th>
                                        <th class="text-center">Keluar</th>
                                        <th class="text-center">Saldo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($entries as $e): ?>
                                        <tr>
                                            <td><?= date('d M Y H:i', strtotime($e['entry_date'])) ?></td>
                                            <td>
                                                <span class="<?= $e['type'] === 'IN' ? 'badge-in' : 'badge-out' ?>">
                                                    <?= $e['type'] === 'IN' ? '↑ Masuk' : '↓ Keluar' ?>
                                                </span>
                                            </td>
                                            <td>
                                                <code style="font-size:0.78rem;"><?= esc($e['reference_code'] ?? '-') ?></code>
                                                <div style="font-size:0.72rem;color:var(--text-muted);"><?= esc($e['reference_type']) ?></div>
                                            </td>
                                            <td style="font-size:0.82rem;"><?= esc($e['description'] ?? '-') ?></td>
                                            <td class="text-center fw-bold" style="color:#059669;"><?= $e['type'] === 'IN' ? number_format($e['quantity'], 0) : '-' ?></td>
                                            <td class="text-center fw-bold" style="color:#dc2626;"><?= $e['type'] === 'OUT' ? number_format($e['quantity'], 0) : '-' ?></td>
                                            <td class="text-center fw-bold"><?= number_format($e['balance_after'], 0) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="glass-card">
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-journal-text d-block" style="font-size:3rem; opacity:0.3;"></i>
                        <h5 class="mt-2">Pilih produk untuk melihat kartu stok</h5>
                        <p>Gunakan filter di atas untuk memilih produk dan periode.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
