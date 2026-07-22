<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><title>Runchise — Product Catalog</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<style>
:root{--bg-dark:#FAF6F3;--bg-card:#FFFFFF;--text-primary:#2C1E1A;--text-muted:#8A756E;--border:rgba(226,167,148,0.25);--primary:#E2A794;}
body{font-family:'Inter',sans-serif;background:var(--bg-dark);color:var(--text-primary);min-height:100vh;padding:2rem;}
.card-nexapos{background:var(--bg-card);border:1px solid var(--border);border-radius:16px;overflow:hidden;box-shadow: 0 4px 20px rgba(226, 167, 148, 0.06);}
.table{color:var(--text-primary);}
.table th{background:#faf5f2;color:var(--text-muted);font-size:0.8rem;text-transform:uppercase;border-bottom:1px solid var(--border);padding:0.75rem 1rem;}
.table td{border-bottom:1px solid var(--border);padding:1rem;color:var(--text-primary);}
.btn-primary-nexapos{background:linear-gradient(135deg,#E2A794,#d97757);border:none;color:white;padding:0.5rem 1.25rem;border-radius:10px;font-weight:600;text-decoration:none;transition:all 0.2s;}
.btn-primary-nexapos:hover{transform:translateY(-1px);box-shadow:0 5px 15px rgba(226,167,148,0.3);color:white;}
.btn-outline-warning { border-radius:6px; font-weight:600; font-size:0.8rem; }
.btn-outline-danger { border-radius:6px; font-weight:600; font-size:0.8rem; }
.nav-tabs { border-bottom: 2px solid var(--border); }
.nav-link { color: var(--text-muted); font-weight: 600; border: none !important; padding: 0.75rem 1.5rem; transition: all 0.2s; }
.nav-link:hover { color: var(--text-primary); }
.nav-link.active { color: var(--primary) !important; background: transparent !important; border-bottom: 3px solid var(--primary) !important; }
.form-control { background: #ffffff; border: 1px solid var(--border); color: var(--text-primary); border-radius: 10px; }
.form-control:focus { background: #ffffff; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(226,167,148,0.25); color: var(--text-primary); }
.badge-category { background: rgba(226, 167, 148, 0.15); color: #E2A794; border: 1px solid rgba(226, 167, 148, 0.3); border-radius: 6px; padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: 600; }
</style></head><body>

<div class="d-flex" style="min-height: 100vh;">
    <!-- Shared Premium Sidebar -->
    <?= view('partials/sidebar') ?>

    <!-- Main Content Area -->
    <div class="flex-grow-1" style="overflow-x: hidden; padding: 2rem;">
        <!-- Header Navigation Title -->
        <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom" style="border-color:var(--border) !important;">
            <h2 style="font-size: 1.25rem; font-weight: 700; margin: 0; color: var(--text-primary);">📦 Product Catalog</h2>
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
                <button class="nav-link" id="wasted-tab" data-bs-toggle="tab" data-bs-target="#wasted-pane" type="button" role="tab" aria-controls="wasted-pane" aria-selected="false">🗑️ Produk Terbuang (Wasted)</button>
            </li>
        </ul>

        <div class="tab-content" id="catalogTabsContent">
            <!-- TAB 1: Products List -->
            <div class="tab-pane fade show active" id="products-pane" role="tabpanel" aria-labelledby="products-tab">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h4 style="font-weight:700; margin: 0; font-size:1.1rem;">Products List</h4>
                    <a href="/inventory/products/new" class="btn-primary-nexapos">+ New Product</a>
                </div>

                <div class="card-nexapos">
                    <div class="table-responsive p-3">
                        <table class="table mb-0" id="productsTable">
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
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 3: Wasted Products List & Add Form -->
            <div class="tab-pane fade" id="wasted-pane" role="tabpanel" aria-labelledby="wasted-tab">
                <div class="row">
                    <!-- Left Side: Add Wasted Product Form -->
                    <div class="col-md-4 mb-4">
                        <div class="card-nexapos p-4">
                            <h5 style="font-weight:700;" class="mb-3">🗑️ Catat Barang Rusak/Expired</h5>
                            <form action="/inventory/wasted" method="POST">
                                <?= csrf_field() ?>
                                <div class="mb-3">
                                    <label class="form-label" style="color:var(--text-muted); font-size:0.85rem; font-weight:600;">Pilih Produk *</label>
                                    <select name="product_id" class="form-control" required style="background: rgba(15,23,42,0.7); color: var(--text-primary);">
                                        <option value="">-- Pilih Produk --</option>
                                        <?php if (!empty($products)): ?>
                                            <?php foreach ($products as $p): ?>
                                                <option value="<?= $p['id'] ?>"><?= esc($p['name']) ?> (SKU: <?= esc($p['sku']) ?>)</option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" style="color:var(--text-muted); font-size:0.85rem; font-weight:600;">Jumlah Terbuang *</label>
                                    <input type="number" name="quantity" class="form-control" placeholder="Kuantitas terbuang" min="1" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" style="color:var(--text-muted); font-size:0.85rem; font-weight:600;">Alasan *</label>
                                    <select name="reason" class="form-control" required style="background: rgba(15,23,42,0.7); color: var(--text-primary);">
                                        <option value="Expired">Kedaluwarsa (Expired)</option>
                                        <option value="Damaged">Barang Rusak / Pecah</option>
                                        <option value="Stolen">Hilang / Dicuri</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn-primary-nexapos w-100 mt-2">Simpan Kerugian</button>
                            </form>
                        </div>
                    </div>

                    <!-- Right Side: Wasted Logs List -->
                    <div class="col-md-8">
                        <div class="card-nexapos">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>SKU</th>
                                            <th>Nama Produk</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-end">Cost per Item</th>
                                            <th class="text-end">Total Kerugian</th>
                                            <th>Alasan</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php if (!empty($wastedLogs)): ?>
                                        <?php foreach ($wastedLogs as $w): ?>
                                        <tr>
                                            <td><code><?= esc($w['product_sku']) ?></code></td>
                                            <td style="font-weight:600;"><?= esc($w['product_name']) ?></td>
                                            <td class="text-center" style="font-weight:bold; color:#fca5a5;"><?= $w['quantity'] ?></td>
                                            <td class="text-end">Rp <?= number_format($w['cost_price']) ?></td>
                                            <td class="text-end" style="font-weight:bold; color:#ef4444;">Rp <?= number_format($w['cost_price'] * $w['quantity']) ?></td>
                                            <td>
                                                <span class="badge" style="background-color: rgba(239, 68, 68, 0.15) !important; color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.3);"><?= esc($w['reason']) ?></span>
                                            </td>
                                            <td style="font-size:0.85rem; color:var(--text-muted);"><?= esc($w['created_at']) ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="7" class="text-center py-4" style="color:var(--text-muted);">Belum ada pencatatan barang rusak/terbuang.</td></tr>
                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
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
        $('#productsTable').DataTable({
            "pageLength": 10,
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

    // Function to activate tab based on hash
    function activateTabFromHash() {
        const hash = window.location.hash;
        if (hash) {
            const activeTabButton = document.querySelector(`button[data-bs-target="${hash}"]`);
            if (activeTabButton) {
                // Deactivate all
                document.querySelectorAll('.nav-link').forEach(btn => btn.classList.remove('active'));
                document.querySelectorAll('.tab-pane').forEach(pane => {
                    pane.classList.remove('show');
                    pane.classList.remove('active');
                });
                
                // Activate target
                activeTabButton.classList.add('active');
                const pane = document.querySelector(hash);
                if (pane) {
                    pane.classList.add('show');
                    pane.classList.add('active');
                }
            }
        }
    }

    // Run on load and on hash change
    document.addEventListener('DOMContentLoaded', activateTabFromHash);
    window.addEventListener('hashchange', activateTabFromHash);
</script>
</body>
</html>
