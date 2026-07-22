<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Runchise — Riwayat Penerimaan</title>
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
        .table-premium { width: 100%; border-collapse: separate; border-spacing: 0; }
        .table-premium thead th { background: rgba(226,167,148,0.08); padding: 0.75rem 1rem; font-size: 0.78rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); border-bottom: 2px solid var(--border-light); }
        .table-premium tbody td { padding: 0.75rem 1rem; font-size: 0.85rem; border-bottom: 1px solid rgba(226,167,148,0.1); vertical-align: middle; }
        .table-premium tbody tr:hover { background: rgba(226,167,148,0.04); }
        .items-sub { margin-top: 0.5rem; padding: 0.5rem; background: rgba(226,167,148,0.04); border-radius: 8px; font-size: 0.78rem; }
        .empty-state { text-align: center; padding: 3rem; color: var(--text-muted); }
        .empty-state i { font-size: 3rem; margin-bottom: 1rem; opacity: 0.4; }
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
            <h1><i class="bi bi-clock-history"></i> Riwayat Penerimaan Barang</h1>
            <div class="d-flex align-items-center gap-3">
                <div class="user-details">
                    <div class="user-name"><?= esc($userName) ?></div>
                    <div class="user-role"><?= esc($userRole) ?></div>
                </div>
                <a href="/auth/logout" class="btn-logout">Sign Out →</a>
            </div>
        </div>

        <div class="content-area">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <p class="text-muted mb-0" style="font-size:0.85rem;">Riwayat penerimaan barang (Goods Receipt) dari Purchase Order.</p>
                <a href="/purchasing/orders" class="btn btn-primary-custom" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border: none; color: white; padding: 0.6rem 1.25rem; border-radius: 10px; font-weight: 600; font-size: 0.85rem; text-decoration: none;"><i class="bi bi-plus-lg me-1"></i> Add Penerimaan Barang (via PO)</a>
            </div>
            
            <div class="glass-card">
                <?php if (empty($receivings)): ?>
                    <div class="empty-state">
                        <i class="bi bi-inbox d-block"></i>
                        <h5>Belum ada penerimaan barang</h5>
                        <p>Penerimaan barang akan muncul setelah Anda menerima item dari Purchase Order.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table-premium">
                            <thead>
                                <tr>
                                    <th>No. GR</th>
                                    <th>No. PO</th>
                                    <th>Supplier</th>
                                    <th>Tanggal Terima</th>
                                    <th>Diterima Oleh</th>
                                    <th>Item Diterima</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($receivings as $gr): ?>
                                    <tr>
                                        <td><strong><?= esc($gr['gr_number']) ?></strong></td>
                                        <td><code><?= esc($gr['po_number'] ?? '-') ?></code></td>
                                        <td><?= esc($gr['supplier_name'] ?? '-') ?></td>
                                        <td><?= date('d M Y', strtotime($gr['received_date'])) ?></td>
                                        <td><?= esc($gr['receiver_name'] ?? '-') ?></td>
                                        <td>
                                            <?php if (!empty($gr['items'])): ?>
                                                <div class="items-sub">
                                                    <?php foreach ($gr['items'] as $item): ?>
                                                        <div><strong><?= esc($item['product_name']) ?></strong> — <?= number_format($item['quantity_received'], 0) ?> unit</div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted">—</span>
                                            <?php endif; ?>
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
</body>
</html>
