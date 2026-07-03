<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Runchise — Purchase Orders</title>
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
        .btn-primary-custom { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border: none; color: white; padding: 0.6rem 1.25rem; border-radius: 10px; font-weight: 600; font-size: 0.85rem; transition: all 0.2s; }
        .btn-primary-custom:hover { transform: translateY(-1px); box-shadow: 0 4px 15px rgba(226,167,148,0.4); color: white; }
        .table-premium { width: 100%; border-collapse: separate; border-spacing: 0; }
        .table-premium thead th { background: rgba(226,167,148,0.08); padding: 0.75rem 1rem; font-size: 0.78rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); border-bottom: 2px solid var(--border-light); }
        .table-premium tbody td { padding: 0.75rem 1rem; font-size: 0.85rem; border-bottom: 1px solid rgba(226,167,148,0.1); vertical-align: middle; }
        .table-premium tbody tr:hover { background: rgba(226,167,148,0.04); }
        .badge-status { padding: 0.3rem 0.7rem; border-radius: 20px; font-size: 0.72rem; font-weight: 600; }
        .badge-draft { background: rgba(107,114,128,0.12); color: #6b7280; }
        .badge-ordered { background: rgba(59,130,246,0.12); color: #2563eb; }
        .badge-partial { background: rgba(245,158,11,0.12); color: #d97706; }
        .badge-completed { background: rgba(16,185,129,0.12); color: #059669; }
        .badge-cancelled { background: rgba(239,68,68,0.12); color: #dc2626; }
        .btn-action { padding: 0.35rem 0.7rem; border-radius: 8px; font-size: 0.78rem; font-weight: 500; border: 1px solid var(--border-light); background: white; color: var(--text-primary); transition: all 0.2s; text-decoration: none; }
        .btn-action:hover { background: rgba(226,167,148,0.1); border-color: var(--primary); color: var(--primary-dark); }
        .empty-state { text-align: center; padding: 3rem; color: var(--text-muted); }
        .empty-state i { font-size: 3rem; margin-bottom: 1rem; opacity: 0.4; }
        .user-details { text-align: right; }
        .user-name { font-size: 0.9rem; font-weight: 600; }
        .user-role { font-size: 0.75rem; color: var(--text-muted); }
        .btn-logout { padding: 0.5rem 1rem; background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.15); border-radius: 8px; color: #d94646; font-size: 0.85rem; font-weight: 500; text-decoration: none; transition: all 0.2s; }
        .btn-logout:hover { background: rgba(239,68,68,0.15); color: #b91c1c; }
        .alert-custom { border-radius: 12px; border: none; font-size: 0.85rem; padding: 0.75rem 1.25rem; }
    </style>
</head>
<body>
<div class="page-layout">
    <?= view('App\Views\partials\sidebar') ?>
    <div class="main-content">
        <div class="page-header">
            <h1><i class="bi bi-cart-check"></i> Purchase Orders</h1>
            <div class="d-flex align-items-center gap-3">
                <div class="user-details">
                    <div class="user-name"><?= esc($userName) ?></div>
                    <div class="user-role"><?= esc($userRole) ?></div>
                </div>
                <a href="/auth/logout" class="btn-logout">Sign Out →</a>
            </div>
        </div>

        <div class="content-area">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-custom"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-custom"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <p class="text-muted mb-0" style="font-size:0.85rem;">Kelola Purchase Order (Pembelian Barang) dari supplier.</p>
                <a href="/purchasing/orders/create" class="btn btn-primary-custom"><i class="bi bi-plus-lg me-1"></i> Buat PO Baru</a>
            </div>

            <div class="glass-card">
                <?php if (empty($orders)): ?>
                    <div class="empty-state">
                        <i class="bi bi-cart-x d-block"></i>
                        <h5>Belum ada Purchase Order</h5>
                        <p>Klik "Buat PO Baru" untuk membuat pembelian pertama.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table-premium">
                            <thead>
                                <tr>
                                    <th>No. PO</th>
                                    <th>Supplier</th>
                                    <th>Tanggal Order</th>
                                    <th>Exp. Terima</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Dibuat Oleh</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $o): ?>
                                    <?php
                                        $statusClass = 'badge-draft';
                                        if ($o['status'] === 'Ordered') $statusClass = 'badge-ordered';
                                        elseif ($o['status'] === 'Partially Received') $statusClass = 'badge-partial';
                                        elseif ($o['status'] === 'Completed') $statusClass = 'badge-completed';
                                        elseif ($o['status'] === 'Cancelled') $statusClass = 'badge-cancelled';
                                    ?>
                                    <tr>
                                        <td><strong><?= esc($o['po_number']) ?></strong></td>
                                        <td><?= esc($o['supplier_name'] ?? '-') ?></td>
                                        <td><?= date('d M Y', strtotime($o['order_date'])) ?></td>
                                        <td><?= $o['expected_date'] ? date('d M Y', strtotime($o['expected_date'])) : '-' ?></td>
                                        <td><strong>Rp <?= number_format($o['total_amount'], 0, ',', '.') ?></strong></td>
                                        <td><span class="badge-status <?= $statusClass ?>"><?= esc($o['status']) ?></span></td>
                                        <td><?= esc($o['creator_name'] ?? '-') ?></td>
                                        <td>
                                            <?php if (!in_array($o['status'], ['Completed', 'Cancelled'])): ?>
                                                <a href="/purchasing/receive/<?= $o['id'] ?>" class="btn-action"><i class="bi bi-box-arrow-in-down me-1"></i>Terima</a>
                                            <?php else: ?>
                                                <span class="text-muted" style="font-size:0.78rem;">—</span>
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
