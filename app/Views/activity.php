<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Runchise — Aktivitas Hari Ini</title>
    <!-- Fonts & CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #E2A794;
            --primary-dark: #c98570;
            --bg-dark: #FAF6F3;
            --bg-card: #FFFFFF;
            --border-light: rgba(226, 167, 148, 0.25);
            --text-primary: #2C1E1A;
            --text-muted: #8A756E;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-dark);
            color: var(--text-primary);
            min-height: 100vh;
            margin: 0;
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(226, 167, 148, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 85% 30%, rgba(226, 167, 148, 0.05) 0%, transparent 50%);
        }

        .page-layout { display: flex; min-height: 100vh; }
        .main-content { flex: 1; overflow-x: hidden; display: flex; flex-direction: column; }

        /* Header */
        .page-header {
            background: rgba(250, 246, 243, 0.8); backdrop-filter: blur(15px);
            border-bottom: 1px solid var(--border-light);
            padding: 1.25rem 2rem; display: flex; justify-content: space-between;
            align-items: center; position: sticky; top: 0; z-index: 10;
        }
        .page-header h1 { font-size: 1.35rem; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 0.5rem; }
        .user-details { text-align: right; margin-right: 1rem; }
        .user-name { font-size: 0.9rem; font-weight: 600; line-height: 1.2; }
        .user-role { font-size: 0.75rem; color: var(--text-muted); }
        .btn-logout {
            padding: 0.5rem 1rem; background: rgba(239, 68, 68, 0.08); border: 1px solid rgba(239, 68, 68, 0.15);
            color: #d94646; font-size: 0.85rem; font-weight: 500; text-decoration: none; border-radius: 8px;
        }
        .btn-logout:hover { background: rgba(239, 68, 68, 0.15); color: #b91c1c; }

        /* Content Area */
        .content-area { padding: 2rem; max-width: 1400px; margin: 0 auto; width: 100%; }

        .metric-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem; margin-bottom: 2rem; }
        .metric-card { background: var(--bg-card); border: 1px solid var(--border-light); border-radius: 16px; padding: 1.5rem; display: flex; align-items: center; gap: 1.25rem; box-shadow: 0 4px 20px rgba(226, 167, 148, 0.05); }
        .metric-icon { width: 54px; height: 54px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
        .icon-revenue { background: rgba(16, 185, 129, 0.12); color: #059669; }
        .icon-orders { background: rgba(59, 130, 246, 0.12); color: #2563eb; }
        
        .metric-value { font-size: 1.5rem; font-weight: 700; line-height: 1.2; }
        .metric-label { font-size: 0.8rem; color: var(--text-muted); font-weight: 500; margin-top: 0.25rem; }

        .panel-card { background: var(--bg-card); border: 1px solid var(--border-light); border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 20px rgba(226, 167, 148, 0.05); }
        .panel-title { font-size: 1.1rem; font-weight: 600; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem; }
        
        .table th { background: rgba(250, 246, 243, 0.5); font-weight: 600; color: var(--text-muted); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border-light); padding: 1rem; }
        .table td { padding: 1rem; vertical-align: middle; border-bottom: 1px dashed var(--border-light); font-size: 0.9rem; }
    </style>
</head>
<body>

<div class="page-layout">
    <!-- Sidebar -->
    <?= view('App\Views\partials\sidebar') ?>

    <!-- Main Content -->
    <div class="main-content">
        <header class="page-header">
            <h1><i class="bi bi-calendar-day"></i> Aktivitas Hari Ini</h1>
            <div class="d-flex align-items-center">
                <div class="user-details">
                    <div class="user-name"><?= esc($userName) ?></div>
                    <div class="user-role"><?= esc($userRole) ?></div>
                </div>
            </div>
        </header>

        <div class="content-area">
            <div class="metric-grid">
                <div class="metric-card">
                    <div class="metric-icon icon-revenue"><i class="bi bi-cash-stack"></i></div>
                    <div>
                        <div class="metric-value">Rp <?= number_format($totalSales, 0, ',', '.') ?></div>
                        <div class="metric-label">Total Penjualan Hari Ini</div>
                    </div>
                </div>
                <div class="metric-card">
                    <div class="metric-icon icon-orders"><i class="bi bi-receipt"></i></div>
                    <div>
                        <div class="metric-value"><?= number_format($totalCount) ?> Transaksi</div>
                        <div class="metric-label">Jumlah Transaksi Hari Ini</div>
                    </div>
                </div>
            </div>

            <div class="panel-card">
                <h3 class="panel-title"><i class="bi bi-list-check"></i> Daftar Transaksi Hari Ini</h3>
                <div class="table-responsive">
                    <table class="table mb-0" id="activityTable">
                        <thead>
                            <tr>
                                <th style="width: 5%">No</th>
                                <th>Waktu</th>
                                <th>No. Transaksi</th>
                                <th>Kasir</th>
                                <th>Item Terjual</th>
                                <th>Metode</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($transactions)): ?>
                                <?php $no = 1; foreach($transactions as $t): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= date('d M Y, H:i', strtotime($t['created_at'])) ?></td>
                                        <td class="fw-bold text-primary"><?= esc($t['invoice_number'] ?? $t['id']) ?></td>
                                        <td><?= esc($t['cashier_name'] ?? 'Admin') ?></td>
                                        <td>
                                            <?php if(isset($t['items']) && count($t['items']) > 0): ?>
                                                <ul class="mb-0 ps-3" style="font-size: 0.8rem;">
                                                    <?php foreach($t['items'] as $item): ?>
                                                        <li><?= esc($item['product_name']) ?> (<?= $item['quantity'] ?>x)</li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php else: ?>
                                                <span class="text-muted" style="font-size: 0.8rem;">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><span class="badge bg-light text-dark border"><?= esc($t['payment_method']) ?></span></td>
                                        <td class="text-end fw-bold text-success">Rp <?= number_format($t['total'], 0, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#activityTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json',
            },
            order: [[1, 'desc']]
        });
    });
</script>
</body>
</html>
