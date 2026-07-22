<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Runchise — Diskon & Promo</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #FAF6F3;
            --bg-card: #FFFFFF;
            --text-primary: #2C1E1A;
            --text-muted: #8A756E;
            --border: rgba(226,167,148,0.25);
            --primary: #E2A794;
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg-dark); color: var(--text-primary); min-height: 100vh; padding: 2rem; }
        .card-nexapos { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(226, 167, 148, 0.06); padding: 2rem; }
        .btn-primary-nexapos { background: linear-gradient(135deg, #E2A794, #d97757); border: none; color: white; padding: 0.5rem 1.25rem; border-radius: 10px; font-weight: 600; text-decoration: none; transition: all 0.2s; }
        .btn-primary-nexapos:hover { transform: translateY(-1px); box-shadow: 0 5px 15px rgba(226,167,148,0.3); color: white; }
    </style>
</head>
<body>

<div class="d-flex" style="min-height: 100vh;">
    <!-- Shared Premium Sidebar -->
    <?= view('partials/sidebar') ?>

    <!-- Main Content Area -->
    <div class="flex-grow-1" style="overflow-x: hidden; padding: 2rem;">
        <!-- Header Navigation Title -->
        <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom" style="border-color:var(--border) !important;">
            <h2 style="font-size: 1.25rem; font-weight: 700; margin: 0; color: var(--text-primary);">🎉 Diskon & Promo</h2>
            <span class="text-muted" style="font-size:0.85rem; font-weight: 500;">Manajemen Kampanye Promosi</span>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success mb-3" style="background: rgba(13, 148, 136, 0.15); border: 1px solid rgba(13, 148, 136, 0.3); color: #2dd4bf; border-radius: 10px;"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger mb-3" style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5; border-radius: 10px;"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <div class="row">
            <!-- Left Side: Add Promo Form -->
            <div class="col-md-4 mb-4">
                <div class="card-nexapos p-4">
                    <h5 style="font-weight:700;" class="mb-3">➕ Buat Promo Baru</h5>
                    <form action="/inventory/promos" method="POST">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label" style="color:var(--text-muted); font-size:0.85rem; font-weight:600;">Nama Promo *</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Diskon Akhir Tahun" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" style="color:var(--text-muted); font-size:0.85rem; font-weight:600;">Tipe Diskon</label>
                            <select name="discount_type" class="form-control">
                                <option value="percentage">Persentase (%)</option>
                                <option value="fixed">Nominal Tetap (Rp)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" style="color:var(--text-muted); font-size:0.85rem; font-weight:600;">Nilai Diskon *</label>
                            <input type="number" step="0.01" name="discount_value" class="form-control" placeholder="e.g. 10 or 50000" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" style="color:var(--text-muted); font-size:0.85rem; font-weight:600;">Mulai (Opsional)</label>
                            <input type="date" name="start_date" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" style="color:var(--text-muted); font-size:0.85rem; font-weight:600;">Berakhir (Opsional)</label>
                            <input type="date" name="end_date" class="form-control">
                        </div>
                        <button type="submit" class="btn-primary-nexapos w-100 mt-2">Simpan Promo</button>
                    </form>
                </div>
            </div>

            <!-- Right Side: Promo List -->
            <div class="col-md-8">
                <div class="card-nexapos p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead style="background:#faf5f2;">
                                <tr>
                                    <th style="padding: 1rem;">Nama Promo</th>
                                    <th style="padding: 1rem;">Tipe</th>
                                    <th style="padding: 1rem;">Nilai</th>
                                    <th style="padding: 1rem;">Masa Berlaku</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if (!empty($promos)): ?>
                                <?php foreach ($promos as $promo): ?>
                                <tr>
                                    <td style="padding: 1rem; font-weight:600;"><?= esc($promo['name']) ?></td>
                                    <td style="padding: 1rem;"><?= ucfirst($promo['discount_type']) ?></td>
                                    <td style="padding: 1rem; font-weight:600; color:var(--primary);">
                                        <?= $promo['discount_type'] === 'percentage' ? $promo['discount_value'] . '%' : 'Rp ' . number_format($promo['discount_value'], 0, ',', '.') ?>
                                    </td>
                                    <td style="padding: 1rem; font-size:0.85rem; color:var(--text-muted);">
                                        <?php 
                                            $start = $promo['start_date'] ? date('d M Y', strtotime($promo['start_date'])) : '-';
                                            $end = $promo['end_date'] ? date('d M Y', strtotime($promo['end_date'])) : '-';
                                            echo $start . ' s/d ' . $end;
                                        ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center py-4" style="color:var(--text-muted);">Belum ada promo yang ditambahkan.</td></tr>
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
</body>
</html>
