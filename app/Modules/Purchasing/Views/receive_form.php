<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Runchise — Penerimaan Barang</title>
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
        .form-control { border: 1px solid var(--border-light); border-radius: 10px; font-size: 0.85rem; padding: 0.6rem 0.9rem; transition: all 0.2s; }
        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(226,167,148,0.15); }
        .btn-primary-custom { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border: none; color: white; padding: 0.6rem 1.5rem; border-radius: 10px; font-weight: 600; font-size: 0.85rem; transition: all 0.2s; }
        .btn-primary-custom:hover { transform: translateY(-1px); box-shadow: 0 4px 15px rgba(226,167,148,0.4); color: white; }
        .btn-outline-custom { border: 1px solid var(--border-light); background: white; color: var(--text-primary); padding: 0.5rem 1rem; border-radius: 10px; font-size: 0.82rem; font-weight: 500; text-decoration: none; transition: all 0.2s; }
        .btn-outline-custom:hover { border-color: var(--primary); background: rgba(226,167,148,0.05); }
        .table-premium { width: 100%; border-collapse: separate; border-spacing: 0; }
        .table-premium thead th { background: rgba(226,167,148,0.08); padding: 0.75rem 1rem; font-size: 0.78rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); border-bottom: 2px solid var(--border-light); }
        .table-premium tbody td { padding: 0.75rem 1rem; font-size: 0.85rem; border-bottom: 1px solid rgba(226,167,148,0.1); vertical-align: middle; }
        .info-label { font-size: 0.78rem; color: var(--text-muted); font-weight: 500; }
        .info-value { font-size: 0.9rem; font-weight: 600; }
        .progress-bar-custom { height: 6px; border-radius: 3px; background: rgba(226,167,148,0.15); overflow: hidden; }
        .progress-fill { height: 100%; border-radius: 3px; background: linear-gradient(90deg, #10b981, #059669); transition: width 0.3s; }
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
            <h1><i class="bi bi-box-arrow-in-down"></i> Penerimaan Barang</h1>
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

            <div class="glass-card">
                <div class="section-title"><i class="bi bi-file-text"></i> Detail Purchase Order</div>
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="info-label">No. PO</div>
                        <div class="info-value"><?= esc($po['po_number']) ?></div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-label">Supplier</div>
                        <div class="info-value"><?= esc($po['supplier_name'] ?? '-') ?></div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-label">Tanggal Order</div>
                        <div class="info-value"><?= date('d M Y', strtotime($po['order_date'])) ?></div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-label">Status</div>
                        <div class="info-value"><?= esc($po['status']) ?></div>
                    </div>
                </div>
            </div>

            <form action="/purchasing/receive" method="POST">
                <input type="hidden" name="purchase_order_id" value="<?= $po['id'] ?>">

                <div class="glass-card">
                    <div class="section-title"><i class="bi bi-box-seam"></i> Item yang Diterima</div>
                    <div class="table-responsive">
                        <table class="table-premium">
                            <thead>
                                <tr>
                                    <th>SKU</th>
                                    <th>Produk</th>
                                    <th>Qty Order</th>
                                    <th>Sudah Diterima</th>
                                    <th>Progress</th>
                                    <th>Qty Terima Kali Ini</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                    <?php
                                        $outstanding = $item['quantity_ordered'] - $item['quantity_received'];
                                        $pct = $item['quantity_ordered'] > 0 ? ($item['quantity_received'] / $item['quantity_ordered']) * 100 : 0;
                                    ?>
                                    <tr>
                                        <td><code><?= esc($item['sku'] ?? '-') ?></code></td>
                                        <td><?= esc($item['product_name'] ?? '-') ?></td>
                                        <td class="text-center"><?= number_format($item['quantity_ordered'], 0) ?></td>
                                        <td class="text-center"><?= number_format($item['quantity_received'], 0) ?></td>
                                        <td>
                                            <div class="progress-bar-custom">
                                                <div class="progress-fill" style="width: <?= min(100, $pct) ?>%"></div>
                                            </div>
                                            <small class="text-muted"><?= number_format($pct, 0) ?>%</small>
                                        </td>
                                        <td>
                                            <?php if ($outstanding > 0): ?>
                                                <input type="number" name="received_quantities[<?= $item['id'] ?>]" class="form-control" min="0" max="<?= $outstanding ?>" value="0" style="width:100px;">
                                            <?php else: ?>
                                                <span class="text-success fw-bold" style="font-size:0.82rem;"><i class="bi bi-check-circle me-1"></i>Selesai</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <label class="form-label" style="font-size:0.82rem; font-weight:600; color:var(--text-muted);">Catatan Penerimaan</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Catatan (opsional)"></textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="/purchasing/orders" class="btn-outline-custom"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
                    <button type="submit" class="btn btn-primary-custom"><i class="bi bi-check-lg me-1"></i> Proses Penerimaan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
