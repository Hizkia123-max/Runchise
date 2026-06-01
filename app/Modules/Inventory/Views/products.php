<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><title>Runchise — Product Catalog</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
:root{--bg-dark:#0f172a;--bg-card:#1e293b;--text-primary:#f1f5f9;--text-muted:#94a3b8;--border:rgba(148,163,184,0.1);--primary:#0d9488;}
body{font-family:'Inter',sans-serif;background:var(--bg-dark);color:var(--text-primary);min-height:100vh;padding:2rem;}
.card-nexapos{background:var(--bg-card);border:1px solid var(--border);border-radius:16px;overflow:hidden;backdrop-filter:blur(10px);}
.table{color:var(--text-primary);}
.table th{background:rgba(15,23,42,0.5);color:var(--text-muted);font-size:0.8rem;text-transform:uppercase;border-bottom:1px solid var(--border);padding:0.75rem 1rem;}
.table td{border-bottom:1px solid var(--border);padding:1rem;}
.btn-primary-nexapos{background:linear-gradient(135deg,#0d9488,#4f46e5);border:none;color:white;padding:0.5rem 1.25rem;border-radius:10px;font-weight:600;text-decoration:none;transition:all 0.2s;}
.btn-primary-nexapos:hover{transform:translateY(-1px);box-shadow:0 5px 15px rgba(13,148,136,0.3);color:white;}
.btn-outline-warning { border-radius:6px; font-weight:600; font-size:0.8rem; }
.btn-outline-danger { border-radius:6px; font-weight:600; font-size:0.8rem; }
.nav-tabs { border-bottom: 2px solid var(--border); }
.nav-link { color: var(--text-muted); font-weight: 600; border: none !important; padding: 0.75rem 1.5rem; transition: all 0.2s; }
.nav-link:hover { color: var(--text-primary); }
.nav-link.active { color: var(--primary) !important; background: transparent !important; border-bottom: 3px solid var(--primary) !important; }
.form-control { background: rgba(15,23,42,0.7); border: 1px solid var(--border); color: var(--text-primary); border-radius: 10px; }
.form-control:focus { background: rgba(15,23,42,0.9); border-color: var(--primary); box-shadow: 0 0 0 3px rgba(13,148,136,0.2); color: var(--text-primary); }
.badge-category { background: rgba(13, 148, 136, 0.15); color: #2dd4bf; border: 1px solid rgba(13, 148, 136, 0.3); border-radius: 6px; padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: 600; }
</style></head>
<body>

<!-- Runchise Premium Interactive Header -->
<div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom" style="border-color:var(--border) !important;">
    <a href="/dashboard" id="white-title" style="text-decoration:none; color:white; display:flex; align-items:center; gap:0.5rem; transition: transform 0.2s;">
        <span style="font-size:1.5rem;">⚡</span>
        <span style="font-weight:700; font-size:1.25rem; letter-spacing:-0.02em;">Runchise</span>
    </a>
    <span class="text-muted" style="font-size:0.85rem; font-weight: 500;">Product & Category Catalog</span>
</div>

<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success mb-3" style="background: rgba(13, 148, 136, 0.15); border: 1px solid rgba(13, 148, 136, 0.3); color: #2dd4bf; border-radius: 10px;"><?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
<div class="alert alert-danger mb-3" style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5; border-radius: 10px;"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<!-- Section Navigation Tabs -->
<ul class="nav nav-tabs mb-4" id="catalogTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="products-tab" data-bs-toggle="tab" data-bs-target="#products-pane" type="button" role="tab" aria-controls="products-pane" aria-selected="true">📦 Products Catalog</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="categories-tab" data-bs-toggle="tab" data-bs-target="#categories-pane" type="button" role="tab" aria-controls="categories-pane" aria-selected="false">📁 Category Management</button>
    </li>
</ul>

<div class="tab-content" id="catalogTabsContent">
    <!-- TAB 1: Products List -->
    <div class="tab-pane fade show active" id="products-pane" role="tabpanel" aria-labelledby="products-tab">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 style="font-weight:700; margin: 0;">Products List</h4>
            <a href="/inventory/products/new" class="btn-primary-nexapos">+ New Product</a>
        </div>

        <div class="card-nexapos">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>SKU</th>
                            <th>Name</th>
                            <th>Category</th>
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
                            <td>
                                <span class="badge-category"><?= esc($p['category_name']) ?></span>
                            </td>
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
                        <tr><td colspan="8" class="text-center py-4" style="color:var(--text-muted);">No products yet. <a href="/inventory/products/new">Add one</a>.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 2: Categories List & Add Form -->
    <div class="tab-pane fade" id="categories-pane" role="tabpanel" aria-labelledby="categories-tab">
        <div class="row">
            <!-- Left Side: Add Category Form -->
            <div class="col-md-4 mb-4">
                <div class="card-nexapos p-4">
                    <h5 style="font-weight:700;" class="mb-3">➕ Add New Category</h5>
                    <form action="/inventory/categories" method="POST">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label" style="color:var(--text-muted); font-size:0.85rem; font-weight:600;">Category Name *</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Desserts, Beverages" required>
                        </div>
                        <button type="submit" class="btn-primary-nexapos w-100 mt-2">Save Category</button>
                    </form>
                </div>
            </div>

            <!-- Right Side: Category List -->
            <div class="col-md-8">
                <div class="card-nexapos">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Category Name</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if (!empty($categories)): ?>
                                <?php foreach ($categories as $cat): ?>
                                <tr>
                                    <td><?= $cat['id'] ?></td>
                                    <td style="font-weight:600;"><?= esc($cat['name']) ?></td>
                                    <td style="font-size:0.85rem; color:var(--text-muted);"><?= $cat['created_at'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="3" class="text-center py-4" style="color:var(--text-muted);">No categories available. Add one using the form on the left.</td></tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body></html>
