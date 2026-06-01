<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><title>Runchise — POS Sessions</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
:root{--bg-dark:#140f0e;--bg-card:#221a18;--text-primary:#f5eae6;--text-muted:#bdafa9;--border:rgba(226,167,148,0.15);--primary:#E2A794;}
body{font-family:'Inter',sans-serif;background:var(--bg-dark);color:var(--text-primary);min-height:100vh;padding:2rem;}
.card-nexapos{background:var(--bg-card);border:1px solid var(--border);border-radius:16px;overflow:hidden;}
.table{color:var(--text-primary);}
.table th{background:rgba(20,15,14,0.5);color:var(--text-muted);font-size:0.8rem;text-transform:uppercase;border-bottom:1px solid var(--border);padding:0.75rem 1rem;}
.table td{border-bottom:1px solid var(--border);vertical-align:middle;padding:1rem;}
.badge-open{background:rgba(16,185,129,0.2);color:#10b981;border-radius:20px;padding:0.25rem 0.75rem;font-size:0.75rem;}
.badge-closed{background:rgba(148,163,184,0.1);color:var(--text-muted);border-radius:20px;padding:0.25rem 0.75rem;font-size:0.75rem;}
.btn-primary-nexapos{background:linear-gradient(135deg,#E2A794,#d97757);border:none;color:white;padding:0.5rem 1.25rem;border-radius:10px;font-weight:600;text-decoration:none;}
</style></head>
<body>

<div class="d-flex" style="min-height: 100vh;">
    <!-- Shared Premium Sidebar -->
    <?= view('partials/sidebar') ?>

    <!-- Main Content Area -->
    <div class="flex-grow-1" style="overflow-x: hidden; padding: 2rem;">
        <!-- Header Navigation Title -->
        <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom" style="border-color:var(--border) !important;">
            <h2 style="font-size: 1.25rem; font-weight: 700; margin: 0; color: var(--text-primary);">🖥️ POS Shifts & Sessions</h2>
            <span class="text-muted" style="font-size:0.85rem; font-weight: 500;">POS Management</span>
        </div>

        <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 style="font-weight:700; margin:0; font-size:1.1rem;">Active Shifts & Drawer Balances</h4>
            <a href="/pos/terminal" class="btn-primary-nexapos">Open POS Terminal</a>
        </div>

        <div class="card-nexapos">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Branch</th>
                            <th>Opened By</th>
                            <th>Opening Cash</th>
                            <th>Closing Cash</th>
                            <th>Status</th>
                            <th>Opened At</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($sessions)): ?>
                        <?php foreach ($sessions as $s): ?>
                        <tr>
                            <td><?= $s['id'] ?></td>
                            <td>Branch #<?= esc($s['branch_id']) ?></td>
                            <td style="font-weight:600;"><i class="bi bi-person-circle"></i> Cashier #<?= esc($s['user_id']) ?></td>
                            <td>Rp <?= number_format($s['opening_cash']) ?></td>
                            <td><?= $s['closing_cash'] ? 'Rp ' . number_format($s['closing_cash']) : '—' ?></td>
                            <td>
                                <?= $s['status'] === 'Open' ? '<span class="badge-open">Active / Open</span>' : '<span class="badge-closed">Closed</span>' ?>
                            </td>
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
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
