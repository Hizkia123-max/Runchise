<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><title>Runchise — POS Sessions</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
:root{--bg-dark:#0f172a;--bg-card:#1e293b;--text-primary:#f1f5f9;--text-muted:#94a3b8;--border:rgba(148,163,184,0.1);}
body{font-family:'Inter',sans-serif;background:var(--bg-dark);color:var(--text-primary);min-height:100vh;padding:2rem;}
.card-nexapos{background:var(--bg-card);border:1px solid var(--border);border-radius:16px;overflow:hidden;}
.table{color:var(--text-primary);}
.table th{background:rgba(15,23,42,0.5);color:var(--text-muted);font-size:0.8rem;text-transform:uppercase;border-bottom:1px solid var(--border);padding:0.75rem 1rem;}
.table td{border-bottom:1px solid var(--border);vertical-align:middle;padding:1rem;}
.badge-open{background:rgba(16,185,129,0.2);color:#10b981;border-radius:20px;padding:0.25rem 0.75rem;font-size:0.75rem;}
.badge-closed{background:rgba(148,163,184,0.1);color:var(--text-muted);border-radius:20px;padding:0.25rem 0.75rem;font-size:0.75rem;}
.btn-primary-nexapos{background:linear-gradient(135deg,#0d9488,#4f46e5);border:none;color:white;padding:0.5rem 1.25rem;border-radius:10px;font-weight:600;text-decoration:none;}
</style></head>
<body>

<!-- Runchise Premium Interactive Header -->
<div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom" style="border-color:var(--border) !important;">
    <a href="/dashboard" id="white-title" style="text-decoration:none; color:white; display:flex; align-items:center; gap:0.5rem; transition: transform 0.2s;">
        <span style="font-size:1.5rem;">⚡</span>
        <span style="font-weight:700; font-size:1.25rem; letter-spacing:-0.02em;">Runchise</span>
    </a>
    <span class="text-muted" style="font-size:0.85rem; font-weight: 500;">POS Management</span>
</div>

<div class="d-flex align-items-center justify-content-between mb-3">
    <h4 style="font-weight:700;">🖥️ POS Sessions</h4>
    <a href="/pos/terminal" class="btn-primary-nexapos">Open Terminal</a>
</div>
<div class="card-nexapos">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>#</th><th>Branch</th><th>Opened By</th><th>Opening Cash</th><th>Closing Cash</th><th>Status</th><th>Opened At</th></tr></thead>
            <tbody>
            <?php if (!empty($sessions)): ?>
                <?php foreach ($sessions as $s): ?>
                <tr>
                    <td><?= $s['id'] ?></td>
                    <td><?= esc($s['branch_id']) ?></td>
                    <td><?= esc($s['user_id']) ?></td>
                    <td>Rp <?= number_format($s['opening_cash']) ?></td>
                    <td><?= $s['closing_cash'] ? 'Rp ' . number_format($s['closing_cash']) : '—' ?></td>
                    <td><?= $s['status'] === 'Open' ? '<span class="badge-open">Open</span>' : '<span class="badge-closed">Closed</span>' ?></td>
                    <td style="font-size:0.85rem;color:var(--text-muted);"><?= $s['opened_at'] ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7" class="text-center py-4" style="color:var(--text-muted);">No sessions found. <a href="/pos/terminal">Open the terminal</a> to start.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body></html>
