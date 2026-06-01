<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><title>NexaPOS — New Product</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
:root{--bg-dark:#0f172a;--bg-card:#1e293b;--text-primary:#f1f5f9;--text-muted:#94a3b8;--border:rgba(148,163,184,0.1);--primary:#0d9488;}
body{font-family:'Inter',sans-serif;background:var(--bg-dark);color:var(--text-primary);min-height:100vh;padding:2rem;}
.card-nexapos{background:var(--bg-card);border:1px solid var(--border);border-radius:16px;padding:2rem;max-width:640px;}
.form-label{color:var(--text-muted);font-size:0.85rem;font-weight:500;}
.form-control{background:rgba(15,23,42,0.7);border:1px solid var(--border);color:var(--text-primary);border-radius:10px;}
.form-control:focus{background:rgba(15,23,42,0.9);border-color:var(--primary);box-shadow:0 0 0 3px rgba(13,148,136,0.2);color:var(--text-primary);}
.form-control::placeholder{color:rgba(148,163,184,0.5);}
.btn-save{background:linear-gradient(135deg,#0d9488,#4f46e5);border:none;color:white;padding:0.65rem 2rem;border-radius:10px;font-weight:600;}
.alert-error{background:rgba(239,68,68,0.15);border:1px solid rgba(239,68,68,0.3);border-radius:10px;color:#fca5a5;padding:0.75rem 1rem;margin-bottom:1.5rem;}
</style></head>
<body>
<div class="card-nexapos">
    <h4 class="mb-4">➕ New Product</h4>

    <?php if (session()->getFlashdata('error')): ?>
    <div class="alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <form action="/inventory/products" method="POST">
        <?= csrf_field() ?>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Product Name *</label>
                <input type="text" name="name" class="form-control" placeholder="e.g. Wireless Mouse" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">SKU *</label>
                <input type="text" name="sku" class="form-control" placeholder="e.g. PROD-001" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Barcode</label>
                <input type="text" name="barcode" class="form-control" placeholder="Scan or enter barcode">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Reorder Point</label>
                <input type="number" name="reorder_point" class="form-control" value="10" min="0">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Selling Price (Rp) *</label>
                <input type="number" name="price" class="form-control" placeholder="0" step="0.01" min="0" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Cost Price (Rp) *</label>
                <input type="number" name="cost" class="form-control" placeholder="0" step="0.01" min="0" required>
            </div>
        </div>
        <div class="mb-4">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Optional product description..."></textarea>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn-save">Save Product</button>
            <a href="/inventory/products" class="btn btn-sm btn-outline-secondary align-self-center">Cancel</a>
        </div>
    </form>
</div>
</body></html>
