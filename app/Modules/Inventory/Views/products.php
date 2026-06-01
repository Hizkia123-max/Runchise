<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><title>Runchise — Products</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
:root{--bg-dark:#0f172a;--bg-card:#1e293b;--text-primary:#f1f5f9;--text-muted:#94a3b8;--border:rgba(148,163,184,0.1);--primary:#0d9488;}
body{font-family:'Inter',sans-serif;background:var(--bg-dark);color:var(--text-primary);min-height:100vh;padding:2rem;}
.card-nexapos{background:var(--bg-card);border:1px solid var(--border);border-radius:16px;overflow:hidden;}
.table{color:var(--text-primary);}
.table th{background:rgba(15,23,42,0.5);color:var(--text-muted);font-size:0.8rem;text-transform:uppercase;border-bottom:1px solid var(--border);padding:0.75rem 1rem;}
.table td{border-bottom:1px solid var(--border);padding:1rem;}
.btn-primary-nexapos{background:linear-gradient(135deg,#0d9488,#4f46e5);border:none;color:white;padding:0.5rem 1.25rem;border-radius:10px;font-weight:600;text-decoration:none;}
.btn-outline-warning { border-radius:6px; font-weight:600; font-size:0.8rem; }
.btn-outline-danger { border-radius:6px; font-weight:600; font-size:0.8rem; }
</style></head>
<body>

<!-- Runchise Premium Interactive Header -->
<div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom" style="border-color:var(--border) !important;">
    <a href="/dashboard" id="white-title" style="text-decoration:none; color:white; display:flex; align-items:center; gap:0.5rem; transition: transform 0.2s;">
        <span style="font-size:1.5rem;">⚡</span>
        <span style="font-weight:700; font-size:1.25rem; letter-spacing:-0.02em;">Runchise</span>
    </a>
    <span class="text-muted" style="font-size:0.85rem; font-weight: 500;">Product Catalog Management</span>
</div>

<div class="d-flex align-items-center justify-content-between mb-3">
    <h4 style="font-weight:700;">📦 Products Catalog</h4>
    <a href="/inventory/products/new" class="btn-primary-nexapos">+ New Product</a>
</div>

<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success" style="background: rgba(13, 148, 136, 0.15); border: 1px solid rgba(13, 148, 136, 0.3); color: #2dd4bf; border-radius: 10px;"><?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
<div class="alert alert-danger" style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5; border-radius: 10px;"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<div class="card-nexapos">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>SKU</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Cost</th>
                    <th>Reorder</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><code><?= esc($p['sku']) ?></code></td>
                    <td style="font-weight:600;"><?= esc($p['name']) ?></td>
                    <td>Rp <?= number_format($p['price']) ?></td>
                    <td>Rp <?= number_format($p['cost']) ?></td>
                    <td><?= $p['reorder_point'] ?></td>
                    <td class="text-end">
                        <a href="/inventory/products/edit/<?= $p['id'] ?>" class="btn btn-sm btn-outline-warning me-2">Edit</a>
                        <a href="/inventory/products/delete/<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7" class="text-center py-4" style="color:var(--text-muted);">No products yet. <a href="/inventory/products/new">Add one</a>.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body></html>
