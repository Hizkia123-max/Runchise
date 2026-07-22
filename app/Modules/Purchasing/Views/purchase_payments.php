<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Runchise — Riwayat Pembayaran PO</title>
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
        .content-area { padding: 2rem; max-width: 1200px; margin: 0 auto; }
        .glass-card { background: var(--bg-card); border: 1px solid var(--border-light); border-radius: 16px; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 4px 20px rgba(226,167,148,0.06); }
        .btn-primary-custom { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border: none; color: white; padding: 0.6rem 1.25rem; border-radius: 10px; font-weight: 600; font-size: 0.85rem; transition: all 0.2s; }
        .btn-primary-custom:hover { transform: translateY(-1px); box-shadow: 0 4px 15px rgba(226,167,148,0.4); color: white; }
        .table-premium { width: 100%; border-collapse: separate; border-spacing: 0; }
        .table-premium thead th { background: rgba(226,167,148,0.08); padding: 0.75rem 1rem; font-size: 0.78rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); border-bottom: 2px solid var(--border-light); }
        .table-premium tbody td { padding: 0.75rem 1rem; font-size: 0.85rem; border-bottom: 1px solid rgba(226,167,148,0.1); vertical-align: middle; }
        .empty-state { text-align: center; padding: 3rem; color: var(--text-muted); }
        .empty-state i { font-size: 3rem; margin-bottom: 1rem; opacity: 0.4; }
        .alert-custom { border-radius: 12px; border: none; font-size: 0.85rem; padding: 0.75rem 1.25rem; }
    </style>
</head>
<body>
<div class="page-layout">
    <?= view('App\Views\partials\sidebar') ?>
    <div class="main-content">
        <div class="page-header">
            <h1>
                <a href="/purchasing/orders" class="text-decoration-none" style="color: var(--text-muted);"><i class="bi bi-arrow-left me-2"></i></a>
                Pembayaran PO: <?= esc($po['po_number']) ?>
            </h1>
        </div>

        <div class="content-area">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-custom"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-custom"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-4">
                    <div class="glass-card">
                        <h5 class="mb-4">Informasi Tagihan</h5>
                        <div class="mb-3">
                            <span class="text-muted d-block" style="font-size:0.85rem;">Supplier</span>
                            <strong><?= esc($po['supplier_name'] ?? '-') ?></strong>
                        </div>
                        <div class="mb-3">
                            <span class="text-muted d-block" style="font-size:0.85rem;">Total Tagihan</span>
                            <strong class="text-primary-dark" style="font-size:1.2rem;">Rp <?= number_format($po['total_amount'], 0, ',', '.') ?></strong>
                        </div>
                        <div class="mb-3">
                            <span class="text-muted d-block" style="font-size:0.85rem;">Status PO</span>
                            <strong><?= esc($po['status']) ?></strong>
                        </div>

                        <hr style="border-color: var(--border-light);">

                        <h5 class="mb-3 mt-4">Catat Pembayaran Baru</h5>
                        <form action="/purchasing/payments/store" method="POST" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <input type="hidden" name="purchase_order_id" value="<?= $po['id'] ?>">
                            
                            <div class="mb-3">
                                <label class="form-label text-muted" style="font-size:0.85rem;">Tanggal Pembayaran *</label>
                                <input type="date" name="payment_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted" style="font-size:0.85rem;">Jumlah Pembayaran (Rp) *</label>
                                <input type="number" name="amount" class="form-control" min="1" step="0.01" value="<?= $po['total_amount'] ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted" style="font-size:0.85rem;">Metode Pembayaran *</label>
                                <select name="payment_method" class="form-select" required>
                                    <option value="Transfer Bank">Transfer Bank</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Cek/Giro">Cek/Giro</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted" style="font-size:0.85rem;">Bukti Pembayaran (Opsional)</label>
                                <input type="file" name="payment_proof" class="form-control" accept="image/*,.pdf">
                                <small class="text-muted">Format: JPG, PNG, PDF max 2MB</small>
                            </div>
                            
                            <button type="submit" class="btn btn-primary-custom w-100"><i class="bi bi-save me-1"></i> Simpan & Update Status</button>
                        </form>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="glass-card">
                        <h5 class="mb-4">Riwayat Pembayaran</h5>
                        <?php if (empty($payments)): ?>
                            <div class="empty-state" style="padding: 2rem;">
                                <i class="bi bi-wallet2 d-block"></i>
                                <h6>Belum ada pembayaran</h6>
                                <p style="font-size:0.85rem;">Catat pembayaran pertama di form sebelah kiri.</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table-premium">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Metode</th>
                                            <th>Jumlah</th>
                                            <th>Bukti</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($payments as $pay): ?>
                                            <tr>
                                                <td><?= date('d M Y', strtotime($pay['payment_date'])) ?></td>
                                                <td><?= esc($pay['payment_method']) ?></td>
                                                <td><strong>Rp <?= number_format($pay['amount'], 0, ',', '.') ?></strong></td>
                                                <td>
                                                    <?php if ($pay['payment_proof_path']): ?>
                                                        <a href="<?= esc($pay['payment_proof_path']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary" style="font-size:0.75rem; border-radius:6px;">
                                                            <i class="bi bi-file-earmark-image"></i> Lihat
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-muted" style="font-size:0.8rem;">-</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
