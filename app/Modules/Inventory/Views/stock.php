<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Runchise — Inventory Stock</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- DataTables for lazy loading and pagination -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        :root { --primary:#E2A794; --bg-dark:#FAF6F3; --bg-card:#FFFFFF; --text-primary:#2C1E1A; --text-muted:#8A756E; --border:rgba(226,167,148,0.25); }
        body { font-family:'Inter',sans-serif; background:var(--bg-dark); color:var(--text-primary); min-height:100vh; }
        .page-header { padding:2rem; border-bottom:1px solid var(--border); background:var(--bg-card); }
        .page-header h1 { font-size:1.5rem; font-weight:700; color:var(--text-primary); }
        .content { padding:2rem; }
        .card-nexapos { background:var(--bg-card); border:1px solid var(--border); border-radius:16px; overflow:hidden; box-shadow: 0 4px 20px rgba(226, 167, 148, 0.06); }
        .table { color:var(--text-primary); }
        .table th { background:#faf5f2; color:var(--text-muted); font-size:0.8rem; text-transform:uppercase; letter-spacing:0.05em; border-bottom:1px solid var(--border); }
        .table td { border-bottom:1px solid var(--border); vertical-align:middle; color:var(--text-primary); }
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

        <div class="page-header d-flex align-items-center justify-content-between p-4" style="border-bottom:1px solid var(--border); background:#faf5f2;">
            <div>
                <h1 style="font-size:1.5rem; font-weight:700; color:var(--text-primary);"><i class="bi bi-boxes"></i> Inventory Stock</h1>
                <p class="mb-0" style="color:var(--text-muted);font-size:0.9rem;">Real-time stock levels across all branches</p>
            </div>
            <div class="d-flex gap-2">
                <a href="/inventory/products#wasted-pane" class="btn btn-outline-danger btn-sm" style="border-radius:10px; font-weight:600;"><i class="bi bi-trash"></i> Catat Barang Rusak</a>
                <a href="/inventory/opname" class="btn btn-outline-secondary btn-sm" style="border-radius:10px; font-weight:600;">📋 Stock Opname</a>
                <a href="/report/stock-card" class="btn btn-outline-secondary btn-sm" style="border-radius:10px; font-weight:600;">📊 Kartu Stok</a>
                <a href="/inventory/transfers" class="btn btn-outline-secondary btn-sm" style="border-radius:10px; font-weight:600;">🔄 Transfer</a>
            </div>
        </div>

        <div class="content p-4">
            <div class="card-nexapos">
                <div class="table-responsive p-3">
                    <table class="table mb-0" id="stockTable">
                        <thead>
                                <th style="white-space: nowrap;">SKU</th>
                                <th>Product Name</th>
                                <th>Branch</th>
                                <th>Qty</th>
                                <th>Batas Minimum (Reorder)</th>
                                <th>Status</th>
                                <th>Aksi</th>
                        </thead>
                        <tbody>
                        <?php if (!empty($stocks)): ?>
                            <?php foreach ($stocks as $stock): ?>
                            <tr class="stock-row" data-name="<?= esc(strtolower($stock['product_name'])) ?>">
                                <td style="font-family:monospace; font-weight:bold; color:var(--primary); white-space: nowrap;"><?= esc($stock['product_sku']) ?></td>
                                <td style="font-weight:600;"><?= esc($stock['product_name']) ?></td>
                                <td class="text-muted"><?= esc($stock['branch_name']) ?></td>
                                <td><strong><?= esc(round($stock['quantity'])) ?></strong></td>
                                <td class="text-muted">
                                    <form action="/inventory/stock/reorder_point" method="POST" class="d-flex align-items-center gap-2" style="max-width: 120px;">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="product_id" value="<?= $stock['product_id'] ?>">
                                        <input type="number" name="reorder_point" class="form-control form-control-sm" value="<?= esc($stock['reorder_point'] ?? '10') ?>" min="0">
                                        <button type="submit" class="btn btn-sm btn-outline-secondary" style="padding: 0.15rem 0.4rem;" title="Set Batas"><i class="bi bi-check2"></i></button>
                                    </form>
                                </td>
                                <td>
                                    <?php if ($stock['quantity'] <= 0): ?>
                                        <span class="badge-out">Out of Stock</span>
                                    <?php elseif ($stock['quantity'] <= ($stock['reorder_point'] ?? 10)): ?>
                                        <span class="badge-low">Low Stock (≤ <?= esc($stock['reorder_point'] ?? 10) ?>)</span>
                                    <?php else: ?>
                                        <span class="badge-ok">In Stock</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="/inventory/products/edit/<?= $stock['product_id'] ?>" class="btn btn-sm btn-light">Edit Produk</a>
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

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#stockTable').DataTable({
            "pageLength": 25,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Tidak ditemukan data yang sesuai",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty": "Data tidak tersedia",
                "infoFiltered": "(difilter dari _MAX_ total data)"
            }
        });
    });
</script>
</body>
</html>
