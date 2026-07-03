<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Runchise — Buat Purchase Order</title>
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
        .content-area { padding: 2rem; max-width: 1000px; margin: 0 auto; }
        .glass-card { background: var(--bg-card); border: 1px solid var(--border-light); border-radius: 16px; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 4px 20px rgba(226,167,148,0.06); }
        .section-title { font-size: 1rem; font-weight: 600; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; }
        .form-label { font-size: 0.82rem; font-weight: 600; color: var(--text-muted); }
        .form-control, .form-select { border: 1px solid var(--border-light); border-radius: 10px; font-size: 0.85rem; padding: 0.6rem 0.9rem; transition: all 0.2s; }
        .form-control:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(226,167,148,0.15); }
        .btn-primary-custom { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border: none; color: white; padding: 0.6rem 1.5rem; border-radius: 10px; font-weight: 600; font-size: 0.85rem; transition: all 0.2s; }
        .btn-primary-custom:hover { transform: translateY(-1px); box-shadow: 0 4px 15px rgba(226,167,148,0.4); color: white; }
        .btn-outline-custom { border: 1px solid var(--border-light); background: white; color: var(--text-primary); padding: 0.5rem 1rem; border-radius: 10px; font-size: 0.82rem; font-weight: 500; transition: all 0.2s; }
        .btn-outline-custom:hover { border-color: var(--primary); background: rgba(226,167,148,0.05); }
        .item-row { background: rgba(226,167,148,0.03); border: 1px solid rgba(226,167,148,0.12); border-radius: 12px; padding: 1rem; margin-bottom: 0.75rem; }
        .btn-remove-item { width: 32px; height: 32px; border-radius: 8px; border: 1px solid rgba(239,68,68,0.2); background: rgba(239,68,68,0.06); color: #dc2626; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; }
        .btn-remove-item:hover { background: rgba(239,68,68,0.15); }
        .total-display { font-size: 1.25rem; font-weight: 700; color: var(--primary-dark); }
        .user-details { text-align: right; }
        .user-name { font-size: 0.9rem; font-weight: 600; }
        .user-role { font-size: 0.75rem; color: var(--text-muted); }
        .btn-logout { padding: 0.5rem 1rem; background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.15); border-radius: 8px; color: #d94646; font-size: 0.85rem; font-weight: 500; text-decoration: none; }
        .alert-custom { border-radius: 12px; border: none; font-size: 0.85rem; padding: 0.75rem 1.25rem; }
    </style>
</head>
<body>
<div class="page-layout">
    <?= view('App\Views\partials\sidebar') ?>
    <div class="main-content">
        <div class="page-header">
            <h1><i class="bi bi-plus-circle"></i> Buat Purchase Order</h1>
            <div class="d-flex align-items-center gap-3">
                <div class="user-details">
                    <div class="user-name"><?= esc($userName) ?></div>
                    <div class="user-role"><?= esc($userRole) ?></div>
                </div>
                <a href="/auth/logout" class="btn-logout">Sign Out →</a>
            </div>
        </div>

        <div class="content-area">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-custom"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <form action="/purchasing/orders" method="POST" id="poForm">
                <div class="glass-card">
                    <div class="section-title"><i class="bi bi-info-circle"></i> Informasi PO</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Supplier *</label>
                            <select name="supplier_id" class="form-select" required>
                                <option value="">— Pilih Supplier —</option>
                                <?php foreach ($suppliers as $s): ?>
                                    <option value="<?= $s['id'] ?>"><?= esc($s['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Order</label>
                            <input type="date" name="order_date" class="form-control" value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Exp. Terima</label>
                            <input type="date" name="expected_date" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Catatan</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Catatan tambahan (opsional)"></textarea>
                        </div>
                    </div>
                </div>

                <div class="glass-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="section-title mb-0"><i class="bi bi-box-seam"></i> Item Pembelian</div>
                        <button type="button" class="btn btn-outline-custom" onclick="addItem()"><i class="bi bi-plus-lg me-1"></i> Tambah Item</button>
                    </div>

                    <div id="itemsContainer">
                        <div class="item-row" data-idx="0">
                            <div class="row g-2 align-items-end">
                                <div class="col-md-5">
                                    <label class="form-label">Produk *</label>
                                    <select name="product_ids[]" class="form-select product-select" required onchange="autofillCost(this)">
                                        <option value="">— Pilih Produk —</option>
                                        <?php foreach ($products as $p): ?>
                                            <option value="<?= $p['id'] ?>" data-cost="<?= $p['cost'] ?>"><?= esc($p['sku'] . ' - ' . $p['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Qty *</label>
                                    <input type="number" name="quantities[]" class="form-control qty-input" min="1" step="1" value="1" required onchange="calcTotal()">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Harga Beli /unit *</label>
                                    <input type="number" name="unit_costs[]" class="form-control cost-input" min="0" step="100" required onchange="calcTotal()">
                                </div>
                                <div class="col-md-2 d-flex align-items-end gap-2">
                                    <div class="flex-grow-1 text-end">
                                        <small class="text-muted">Subtotal</small>
                                        <div class="fw-bold line-total" style="font-size:0.85rem;">Rp 0</div>
                                    </div>
                                    <button type="button" class="btn-remove-item" onclick="removeItem(this)" title="Hapus item">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr style="border-color: var(--border-light);">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted" style="font-size:0.85rem;">Total Pembelian:</span>
                        <div class="total-display" id="grandTotal">Rp 0</div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="/purchasing/orders" class="btn btn-outline-custom"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
                    <button type="submit" class="btn btn-primary-custom"><i class="bi bi-check-lg me-1"></i> Simpan Purchase Order</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
let itemIdx = 1;
function addItem() {
    const container = document.getElementById('itemsContainer');
    const productOptions = document.querySelector('.product-select').innerHTML;
    const html = `
        <div class="item-row" data-idx="${itemIdx}">
            <div class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label">Produk *</label>
                    <select name="product_ids[]" class="form-select product-select" required onchange="autofillCost(this)">${productOptions}</select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Qty *</label>
                    <input type="number" name="quantities[]" class="form-control qty-input" min="1" step="1" value="1" required onchange="calcTotal()">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Harga Beli /unit *</label>
                    <input type="number" name="unit_costs[]" class="form-control cost-input" min="0" step="100" required onchange="calcTotal()">
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <div class="flex-grow-1 text-end">
                        <small class="text-muted">Subtotal</small>
                        <div class="fw-bold line-total" style="font-size:0.85rem;">Rp 0</div>
                    </div>
                    <button type="button" class="btn-remove-item" onclick="removeItem(this)" title="Hapus item">
                        <i class="bi bi-trash3"></i>
                    </button>
                </div>
            </div>
        </div>`;
    container.insertAdjacentHTML('beforeend', html);
    itemIdx++;
}

function removeItem(btn) {
    const rows = document.querySelectorAll('.item-row');
    if (rows.length <= 1) return;
    btn.closest('.item-row').remove();
    calcTotal();
}

function autofillCost(select) {
    const opt = select.options[select.selectedIndex];
    const cost = opt.dataset.cost || 0;
    const row = select.closest('.item-row');
    row.querySelector('.cost-input').value = cost;
    calcTotal();
}

function calcTotal() {
    let grand = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
        const cost = parseFloat(row.querySelector('.cost-input').value) || 0;
        const sub = qty * cost;
        row.querySelector('.line-total').textContent = 'Rp ' + sub.toLocaleString('id-ID');
        grand += sub;
    });
    document.getElementById('grandTotal').textContent = 'Rp ' + grand.toLocaleString('id-ID');
}
</script>
</body>
</html>
