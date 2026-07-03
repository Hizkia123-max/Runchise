<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8">
<title><?= isset($product) ? 'Runchise — Edit Product' : 'Runchise — New Product' ?></title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
:root{--bg-dark:#FAF6F3;--bg-card:#FFFFFF;--text-primary:#2C1E1A;--text-muted:#8A756E;--border:rgba(226, 167, 148, 0.25);--primary:#E2A794;}
body{font-family:'Inter',sans-serif;background:var(--bg-dark);color:var(--text-primary);min-height:100vh;}
.card-nexapos{background:var(--bg-card);border:1px solid var(--border);border-radius:16px;padding:2rem;max-width:640px;box-shadow: 0 4px 20px rgba(226, 167, 148, 0.06);}
.form-label{color:var(--text-muted);font-size:0.85rem;font-weight:500;}
.form-control{background:#ffffff;border:1px solid var(--border);color:var(--text-primary);border-radius:10px;}
.form-control:focus{background:#ffffff;border-color:var(--primary);box-shadow:0 0 0 3px rgba(226,167,148,0.25);color:var(--text-primary);}
.form-control::placeholder{color:rgba(148,163,184,0.5);}
.btn-save{background:linear-gradient(135deg,#E2A794,#d97757);border:none;color:white;padding:0.65rem 2rem;border-radius:10px;font-weight:600;}
.alert-error{background:rgba(239,68,68,0.15);border:1px solid rgba(239,68,68,0.3);border-radius:10px;color:#fca5a5;padding:0.75rem 1rem;margin-bottom:1.5rem;}
</style></head>
<body>

<div class="d-flex" style="min-height: 100vh;">
    <!-- Shared Premium Sidebar -->
    <?= view('partials/sidebar') ?>

    <!-- Main Content Area -->
    <div class="flex-grow-1" style="overflow-x: hidden; padding: 2rem;">
        <!-- Header Navigation Title -->
        <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom" style="border-color:var(--border) !important; max-width:640px;">
    <a href="/dashboard" id="white-title" style="text-decoration:none; color:white; display:flex; align-items:center; gap:0.5rem; transition: transform 0.2s;">
        <span style="font-size:1.5rem;">⚡</span>
        <span style="font-weight:700; font-size:1.25rem; letter-spacing:-0.02em;">Runchise</span>
    </a>
    <span class="text-muted" style="font-size:0.85rem; font-weight: 500;">Product Form</span>
</div>

<div class="card-nexapos">
    <h4 class="mb-4"><?= isset($product) ? '✏️ Edit Product' : '➕ New Product' ?></h4>

    <?php if (session()->getFlashdata('error')): ?>
    <div class="alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <form action="<?= isset($product) ? '/inventory/products/update/' . $product['id'] : '/inventory/products' ?>" method="POST">
        <?= csrf_field() ?>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Product Name *</label>
                <input type="text" name="name" class="form-control" placeholder="e.g. Wireless Mouse" value="<?= esc($product['name'] ?? '') ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">SKU *</label>
                <input type="text" name="sku" class="form-control" placeholder="e.g. PROD-001" value="<?= esc($product['sku'] ?? '') ?>" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3">
                <label class="form-label">Product Category *</label>
                <select name="category_id" class="form-control" required style="background: #ffffff; color: var(--text-primary);">
                    <option value="">-- Select Product Category --</option>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= (isset($product) && $product['category_id'] == $cat['id']) ? 'selected' : '' ?>><?= esc($cat['name']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Barcode</label>
                <input type="text" name="barcode" class="form-control" placeholder="Scan or enter barcode" value="<?= esc($product['barcode'] ?? '') ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Reorder Point</label>
                <input type="number" name="reorder_point" class="form-control" value="<?= esc($product['reorder_point'] ?? '10') ?>" min="0">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Selling Price (Rp) *</label>
                <input type="number" name="price" class="form-control" placeholder="0" step="0.01" min="0" value="<?= esc($product['price'] ?? '') ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Cost Price (Rp) *</label>
                <input type="number" name="cost" class="form-control" placeholder="0" step="0.01" min="0" value="<?= esc($product['cost'] ?? '') ?>" required>
            </div>
        </div>
        <div class="mb-4">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Optional product description..."><?= esc($product['description'] ?? '') ?></textarea>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn-save">Save Product</button>
            <a href="/inventory/products" class="btn btn-sm btn-outline-secondary align-self-center" style="border-radius:10px; padding: 0.6rem 1.5rem; font-weight:600;">Cancel</a>
        </div>
    </form>
</div>

    </div>
</div>
</body>
</html>
