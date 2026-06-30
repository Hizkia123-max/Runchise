<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Runchise — Supplier</title>
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
        .btn-primary-custom { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border: none; color: white; padding: 0.6rem 1.25rem; border-radius: 10px; font-weight: 600; font-size: 0.85rem; }
        .btn-primary-custom:hover { color: white; transform: translateY(-1px); box-shadow: 0 4px 15px rgba(226,167,148,0.4); }
        .form-control { border: 1px solid var(--border-light); border-radius: 10px; font-size: 0.85rem; padding: 0.6rem 0.9rem; }
        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(226,167,148,0.15); }
        .form-label { font-size: 0.82rem; font-weight: 600; color: var(--text-muted); }
        .table-premium { width: 100%; border-collapse: separate; border-spacing: 0; }
        .table-premium thead th { background: rgba(226,167,148,0.08); padding: 0.75rem 1rem; font-size: 0.78rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); border-bottom: 2px solid var(--border-light); }
        .table-premium tbody td { padding: 0.75rem 1rem; font-size: 0.85rem; border-bottom: 1px solid rgba(226,167,148,0.1); }
        .table-premium tbody tr:hover { background: rgba(226,167,148,0.04); }
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
            <h1><i class="bi bi-people"></i> Data Supplier</h1>
            <div class="d-flex align-items-center gap-3">
                <div class="user-details">
                    <div class="user-name"><?= esc($userName) ?></div>
                    <div class="user-role"><?= esc($userRole) ?></div>
                </div>
                <a href="/auth/logout" class="btn-logout">Sign Out →</a>
            </div>
        </div>

        <div class="content-area">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-custom"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>

            <div class="glass-card">
                <h6 class="fw-bold mb-3"><i class="bi bi-plus-circle me-1"></i> Tambah Supplier Baru</h6>
                <form action="/purchasing/suppliers" method="POST">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Nama Supplier *</label>
                            <input type="text" name="name" class="form-control" required placeholder="PT. Supplier ABC">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Contact Person</label>
                            <input type="text" name="contact_person" class="form-control" placeholder="Budi Santoso">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Telepon</label>
                            <input type="text" name="phone" class="form-control" placeholder="0812-xxx">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="supplier@email.com">
                        </div>
                        <div class="col-md-9">
                            <label class="form-label">Alamat</label>
                            <input type="text" name="address" class="form-control" placeholder="Jl. Supplier No. 1">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary-custom w-100"><i class="bi bi-plus-lg me-1"></i> Simpan</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="glass-card">
                <h6 class="fw-bold mb-3"><i class="bi bi-list-ul me-1"></i> Daftar Supplier</h6>
                <?php if (empty($suppliers)): ?>
                    <p class="text-muted text-center py-3">Belum ada data supplier.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table-premium">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Contact Person</th>
                                    <th>Telepon</th>
                                    <th>Email</th>
                                    <th>Alamat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($suppliers as $idx => $s): ?>
                                    <tr>
                                        <td><?= $idx + 1 ?></td>
                                        <td><strong><?= esc($s['name']) ?></strong></td>
                                        <td><?= esc($s['contact_person'] ?? '-') ?></td>
                                        <td><?= esc($s['phone'] ?? '-') ?></td>
                                        <td><?= esc($s['email'] ?? '-') ?></td>
                                        <td><?= esc($s['address'] ?? '-') ?></td>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
