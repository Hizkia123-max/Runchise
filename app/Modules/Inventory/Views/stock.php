<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Runchise — Inventory Stock</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --primary:#E2A794; --bg-dark:#140f0e; --bg-card:#221a18; --text-primary:#f5eae6; --text-muted:#bdafa9; --border:rgba(226,167,148,0.15); }
        body { font-family:'Inter',sans-serif; background:var(--bg-dark); color:var(--text-primary); min-height:100vh; }
        .page-header { padding:2rem; border-bottom:1px solid var(--border); background:var(--bg-card); }
        .page-header h1 { font-size:1.5rem; font-weight:700; }
        .content { padding:2rem; }
        .card-nexapos { background:var(--bg-card); border:1px solid var(--border); border-radius:16px; overflow:hidden; }
        .table { color:var(--text-primary); }
        .table th { background:rgba(20,15,14,0.5); color:var(--text-muted); font-size:0.8rem; text-transform:uppercase; letter-spacing:0.05em; border-bottom:1px solid var(--border); }
        .table td { border-bottom:1px solid var(--border); vertical-align:middle; }
        .badge-low { background:rgba(245,158,11,0.2); color:#f59e0b; border-radius:20px; padding:0.25rem 0.75rem; font-size:0.75rem; }
        .badge-ok  { background:rgba(16,185,129,0.2); color:#10b981; border-radius:20px; padding:0.25rem 0.75rem; font-size:0.75rem; }
        .badge-out { background:rgba(239,68,68,0.2); color:#ef4444; border-radius:20px; padding:0.25rem 0.75rem; font-size:0.75rem; }
    </style>
</head>
<body>

<div class="d-flex" style="min-height: 100vh;">
    <!-- Shared Premium Sidebar -->
    <?= view('partials/sidebar') ?>

    <!-- Main Content Area -->
    <div class="flex-grow-1" style="overflow-x: hidden; padding-bottom: 3rem;">
        <!-- Header Navigation Title -->
        <div class="d-flex align-items-center justify-content-between px-4 py-3 border-bottom" style="border-color:var(--border) !important; background:var(--bg-card);">
            <h2 style="font-size: 1.15rem; font-weight: 700; margin: 0; color: var(--text-primary);">📈 Stock Levels</h2>
            <span class="text-muted" style="font-size:0.85rem; font-weight: 500;">Inventory Management</span>
        </div>

        <div class="page-header d-flex align-items-center justify-content-between p-4" style="border-bottom:1px solid var(--border); background:rgba(34,26,24,0.35);">
            <div>
                <h1 style="font-size:1.5rem; font-weight:700;"><i class="bi bi-boxes"></i> Inventory Stock</h1>
                <p class="mb-0" style="color:var(--text-muted);font-size:0.9rem;">Real-time stock levels across all branches</p>
            </div>
            <div class="d-flex gap-2">
                <a href="/inventory/products#wasted-pane" class="btn btn-outline-danger btn-sm" style="border-radius:10px; font-weight:600;"><i class="bi bi-trash"></i> Catat Barang Rusak</a>
                <a href="/inventory/opname" class="btn btn-outline-secondary btn-sm" style="border-radius:10px; font-weight:600;">📋 Stock Opname</a>
                <a href="/inventory/transfers" class="btn btn-outline-secondary btn-sm" style="border-radius:10px; font-weight:600;">🔄 Transfer</a>
            </div>
        </div>

        <div class="content p-4">
            <div class="card-nexapos">
                <div class="p-3 d-flex justify-content-between align-items-center" style="border-bottom:1px solid var(--border);">
                    <input type="text" id="stockSearchInput" class="form-control" style="max-width:300px;background:rgba(20,15,14,0.6);border:1px solid var(--border);color:var(--text-primary);" placeholder="🔍 Search products...">
                </div>
                <div class="table-responsive">
                    <table class="table mb-0" id="stockTable">
                        <thead>
                            <tr>
                                <th>SKU</th>
                                <th>Product Name</th>
                                <th>Branch</th>
                                <th>Qty</th>
                                <th>Reorder Point</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($stocks)): ?>
                            <?php foreach ($stocks as $stock): ?>
                            <tr class="stock-row" data-name="<?= esc(strtolower($stock['product_name'])) ?>">
                                <td style="font-family:monospace; font-weight:bold; color:var(--primary);"><?= esc($stock['product_sku']) ?></td>
                                <td style="font-weight:600;"><?= esc($stock['product_name']) ?></td>
                                <td class="text-muted"><?= esc($stock['branch_name']) ?></td>
                                <td><strong><?= esc(round($stock['quantity'])) ?></strong></td>
                                <td class="text-muted"><?= esc($stock['reorder_point'] ?? '10') ?></td>
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
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Stock Client-Side Live Search filter
        const searchInput = document.getElementById('stockSearchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const q = this.value.toLowerCase();
                document.querySelectorAll('.stock-row').forEach(row => {
                    row.style.display = row.dataset.name.includes(q) ? '' : 'none';
                });
            });
        }
    });
</script>
</body>
</html>
