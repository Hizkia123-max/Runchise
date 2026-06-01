<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NexaPOS — Inventory Stock</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --primary:#0d9488; --bg-dark:#0f172a; --bg-card:#1e293b; --text-primary:#f1f5f9; --text-muted:#94a3b8; --border:rgba(148,163,184,0.1); }
        body { font-family:'Inter',sans-serif; background:var(--bg-dark); color:var(--text-primary); min-height:100vh; }
        .page-header { padding:2rem; border-bottom:1px solid var(--border); background:var(--bg-card); }
        .page-header h1 { font-size:1.5rem; font-weight:700; }
        .content { padding:2rem; }
        .card-nexapos { background:var(--bg-card); border:1px solid var(--border); border-radius:16px; overflow:hidden; }
        .table { color:var(--text-primary); }
        .table th { background:rgba(15,23,42,0.5); color:var(--text-muted); font-size:0.8rem; text-transform:uppercase; letter-spacing:0.05em; border-bottom:1px solid var(--border); }
        .table td { border-bottom:1px solid var(--border); vertical-align:middle; }
        .badge-low { background:rgba(245,158,11,0.2); color:#f59e0b; border-radius:20px; padding:0.25rem 0.75rem; font-size:0.75rem; }
        .badge-ok  { background:rgba(16,185,129,0.2); color:#10b981; border-radius:20px; padding:0.25rem 0.75rem; font-size:0.75rem; }
        .badge-out { background:rgba(239,68,68,0.2); color:#ef4444; border-radius:20px; padding:0.25rem 0.75rem; font-size:0.75rem; }
    </style>
</head>
<body>
<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <h1><i class="bi bi-boxes"></i> Inventory Stock</h1>
        <p class="mb-0" style="color:var(--text-muted);font-size:0.9rem;">Real-time stock levels across all branches</p>
    </div>
    <div class="d-flex gap-2">
        <a href="/inventory/opname" class="btn btn-outline-secondary btn-sm">📋 Stock Opname</a>
        <a href="/inventory/transfers" class="btn btn-outline-secondary btn-sm">🔄 Transfer</a>
    </div>
</div>
<div class="content">
    <div class="card-nexapos">
        <div class="p-3" style="border-bottom:1px solid var(--border);">
            <input type="text" class="form-control" style="max-width:300px;background:rgba(15,23,42,0.6);border:1px solid var(--border);color:var(--text-primary);" placeholder="🔍 Search products...">
        </div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>SKU</th><th>Product Name</th><th>Branch</th>
                        <th>Qty</th><th>Reorder Point</th><th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($stocks)): ?>
                    <?php foreach ($stocks as $stock): ?>
                    <tr>
                        <td style="font-family:monospace;color:var(--text-muted);"><?= esc($stock['product_id']) ?></td>
                        <td><?= esc($stock['product_id']) ?></td>
                        <td><?= esc($stock['branch_id']) ?></td>
                        <td><strong><?= esc($stock['quantity']) ?></strong></td>
                        <td><?= esc($stock['reorder_point'] ?? '—') ?></td>
                        <td>
                            <?php if ($stock['quantity'] <= 0): ?>
                                <span class="badge-out">Out of Stock</span>
                            <?php elseif ($stock['quantity'] <= ($stock['reorder_point'] ?? 10)): ?>
                                <span class="badge-low">Low Stock</span>
                            <?php else: ?>
                                <span class="badge-ok">In Stock</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center py-4" style="color:var(--text-muted);">No stock records found. <a href="/inventory/products/new">Add products</a>.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
