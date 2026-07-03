<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Runchise — Dashboard</title>
    <!-- Fonts & CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --primary: #E2A794;
            --primary-dark: #c98570;
            --secondary: #4f46e5;
            --bg-dark: #FAF6F3;
            --bg-card: #FFFFFF;
            --border-light: rgba(226, 167, 148, 0.25);
            --text-primary: #2C1E1A;
            --text-muted: #8A756E;
            --success: #10b981;
            --danger: #ef4444;
            --info: #3b82f6;
            --warning: #f59e0b;
        }

        * {
            box-sizing: border-box;
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

        .page-layout {
            display: flex;
            min-height: 100vh;
        }

        .main-content {
            flex: 1;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        .page-header {
            background: rgba(250, 246, 243, 0.8);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid var(--border-light);
            padding: 1.25rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .page-header h1 {
            font-size: 1.35rem;
            font-weight: 700;
            margin: 0;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .user-details {
            text-align: right;
            margin-right: 1rem;
        }

        .user-name {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-primary);
            line-height: 1.2;
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .btn-logout {
            padding: 0.5rem 1rem;
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.15);
            border-radius: 8px;
            color: #d94646;
            font-size: 0.85rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-logout:hover {
            background: rgba(239, 68, 68, 0.15);
            color: #b91c1c;
        }

        /* Content Area */
        .content-area {
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }

        /* Metric Cards */
        .metric-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2rem;
        }

        .metric-card {
            background: var(--bg-card);
            border: 1px solid var(--border-light);
            border-radius: 16px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.25rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 20px rgba(226, 167, 148, 0.05);
            position: relative;
            overflow: hidden;
        }

        .metric-card::after {
            content: '';
            position: absolute;
            top: 0; right: 0; bottom: 0; left: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0) 0%, rgba(226,167,148,0.05) 100%);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .metric-card:hover {
            transform: translateY(-4px);
            border-color: rgba(226, 167, 148, 0.4);
            box-shadow: 0 12px 30px rgba(226, 167, 148, 0.15);
        }

        .metric-card:hover::after {
            opacity: 1;
        }

        .metric-icon {
            width: 54px;
            height: 54px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
            z-index: 1;
        }

        .icon-revenue { background: rgba(16, 185, 129, 0.12); color: #059669; }
        .icon-orders { background: rgba(59, 130, 246, 0.12); color: #2563eb; }
        .icon-products { background: rgba(139, 92, 246, 0.12); color: #7c3aed; }
        .icon-warning { background: rgba(239, 68, 68, 0.12); color: #dc2626; }

        .metric-info { z-index: 1; }
        .metric-value { font-size: 1.5rem; font-weight: 700; color: var(--text-primary); line-height: 1.2; }
        .metric-label { font-size: 0.8rem; color: var(--text-muted); font-weight: 500; margin-top: 0.25rem; }

        /* Chart & Panels */
        .panel-card {
            background: var(--bg-card);
            border: 1px solid var(--border-light);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 20px rgba(226, 167, 148, 0.05);
            height: 100%;
        }

        .panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .panel-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }

        /* Lists */
        .list-group-custom {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .list-item-custom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 0;
            border-bottom: 1px dashed var(--border-light);
        }

        .list-item-custom:last-child {
            border-bottom: none;
        }
        
        .list-item-main {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .rank-badge {
            width: 28px;
            height: 28px;
            background: rgba(226, 167, 148, 0.15);
            color: var(--primary-dark);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
        }

        .item-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text-primary);
        }

        .item-stat {
            font-weight: 700;
            font-size: 0.9rem;
            color: var(--text-primary);
        }
        
        .item-sub {
            font-size: 0.75rem;
            color: var(--text-muted);
        }
    </style>
</head>
<body>

<div class="page-layout">
    <!-- Sidebar -->
    <?= view('App\Views\partials\sidebar') ?>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <header class="page-header">
            <h1><i class="bi bi-grid-1x2"></i> Dashboard</h1>
            <div class="d-flex align-items-center">
                <div class="user-details">
                    <div class="user-name"><?= esc($userName) ?></div>
                    <div class="user-role"><?= esc($userRole) ?></div>
                </div>
                <a href="/auth/logout" class="btn-logout"><i class="bi bi-box-arrow-right me-1"></i>Keluar</a>
            </div>
        </header>

        <!-- Content -->
        <div class="content-area">
            
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger" style="border-radius: 12px; border: none; font-size: 0.85rem; padding: 0.75rem 1.25rem;">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <!-- Metrics -->
            <div class="metric-grid">
                <?php
                    $totalRev = array_sum(array_column($revenueTrend, 'revenue'));
                ?>
                <div class="metric-card">
                    <div class="metric-icon icon-revenue"><i class="bi bi-graph-up-arrow"></i></div>
                    <div class="metric-info">
                        <div class="metric-value">Rp <?= number_format($totalRev, 0, ',', '.') ?></div>
                        <div class="metric-label">Pendapatan (7 Hari)</div>
                    </div>
                </div>

                <div class="metric-card">
                    <div class="metric-icon icon-orders"><i class="bi bi-cart-check"></i></div>
                    <div class="metric-info">
                        <div class="metric-value"><?= number_format($totalPurchases, 0, ',', '.') ?></div>
                        <div class="metric-label">Pembelian PO (Bulan Ini)</div>
                    </div>
                </div>

                <div class="metric-card">
                    <div class="metric-icon icon-products"><i class="bi bi-box-seam"></i></div>
                    <div class="metric-info">
                        <div class="metric-value"><?= number_format($totalProducts) ?></div>
                        <div class="metric-label">Total Produk SKU</div>
                    </div>
                </div>

                <div class="metric-card">
                    <div class="metric-icon icon-warning"><i class="bi bi-exclamation-triangle"></i></div>
                    <div class="metric-info">
                        <div class="metric-value" style="<?= $lowStockCount > 0 ? 'color: var(--danger);' : 'color: var(--success);' ?>">
                            <?= $lowStockCount ?> Item
                        </div>
                        <div class="metric-label">Stok < Reorder Point</div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row g-4 mb-4">
                <div class="col-lg-8">
                    <div class="panel-card">
                        <div class="panel-header">
                            <h3 class="panel-title"><i class="bi bi-activity"></i> Trend Pendapatan (7 Hari Terakhir)</h3>
                        </div>
                        <div class="chart-container">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="panel-card">
                        <div class="panel-header">
                            <h3 class="panel-title"><i class="bi bi-pie-chart"></i> Penjualan Kategori (Bulan Ini)</h3>
                        </div>
                        <div class="chart-container" style="height: 250px;">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lists Row -->
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="panel-card">
                        <div class="panel-header">
                            <h3 class="panel-title"><i class="bi bi-trophy"></i> Produk Terlaris (Bulan Ini)</h3>
                            <a href="/report/sales" class="btn btn-sm btn-light" style="font-size:0.75rem; border-radius:8px;">Lihat Report</a>
                        </div>
                        <ul class="list-group-custom">
                            <?php if(empty($bestSellers)): ?>
                                <li class="list-item-custom"><span class="text-muted" style="font-size:0.85rem;">Belum ada penjualan bulan ini.</span></li>
                            <?php else: ?>
                                <?php foreach($bestSellers as $idx => $item): ?>
                                    <li class="list-item-custom">
                                        <div class="list-item-main">
                                            <div class="rank-badge">#<?= $idx + 1 ?></div>
                                            <div class="item-name"><?= esc($item['name']) ?></div>
                                        </div>
                                        <div class="text-end">
                                            <div class="item-stat"><?= number_format($item['qty']) ?> terjual</div>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="panel-card">
                        <div class="panel-header">
                            <h3 class="panel-title"><i class="bi bi-bell"></i> Status Operasional</h3>
                        </div>
                        <ul class="list-group-custom">
                            <li class="list-item-custom">
                                <div class="list-item-main">
                                    <div class="rank-badge" style="background: rgba(16,185,129,0.15); color: #059669;"><i class="bi bi-pc-display"></i></div>
                                    <div>
                                        <div class="item-name">Sesi Kasir Aktif</div>
                                        <div class="item-sub">Sesi POS yang sedang berjalan</div>
                                    </div>
                                </div>
                                <div class="item-stat" style="color: #059669;"><?= $activeSessions ?> Sesi</div>
                            </li>
                            <li class="list-item-custom">
                                <div class="list-item-main">
                                    <div class="rank-badge" style="background: rgba(59,130,246,0.15); color: #2563eb;"><i class="bi bi-file-earmark-text"></i></div>
                                    <div>
                                        <div class="item-name">PO Berjalan</div>
                                        <div class="item-sub">Purchase order yang belum selesai</div>
                                    </div>
                                </div>
                                <div class="item-stat" style="color: #2563eb;"><?= $activePO ?> PO</div>
                            </li>
                            <li class="list-item-custom">
                                <div class="list-item-main">
                                    <div class="rank-badge" style="background: rgba(239,68,68,0.15); color: #dc2626;"><i class="bi bi-exclamation-triangle"></i></div>
                                    <div>
                                        <div class="item-name">Peringatan Stok</div>
                                        <div class="item-sub">Item perlu di-reorder</div>
                                    </div>
                                </div>
                                <div class="item-stat" style="color: #dc2626;"><?= $lowStockCount ?> Item</div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Revenue Chart
    const revData = <?= json_encode($revenueTrend) ?>;
    const revCtx = document.getElementById('revenueChart').getContext('2d');
    
    // Create gradient
    let gradient = revCtx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(226, 167, 148, 0.4)');
    gradient.addColorStop(1, 'rgba(226, 167, 148, 0.0)');

    new Chart(revCtx, {
        type: 'line',
        data: {
            labels: revData.map(d => d.date),
            datasets: [{
                label: 'Pendapatan',
                data: revData.map(d => d.revenue),
                borderColor: '#c98570',
                backgroundColor: gradient,
                borderWidth: 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#c98570',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return ' Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(226,167,148,0.1)', drawBorder: false },
                    ticks: { callback: v => 'Rp ' + (v/1000).toLocaleString('id-ID') + 'k' }
                },
                x: {
                    grid: { display: false, drawBorder: false }
                }
            }
        }
    });

    // Category Chart
    const catData = <?= json_encode($categorySales) ?>;
    const catCtx = document.getElementById('categoryChart').getContext('2d');
    
    let chartLabels = [];
    let chartValues = [];
    if (catData.length === 0) {
        chartLabels = ['Belum ada data'];
        chartValues = [1];
    } else {
        chartLabels = catData.map(d => d.category || 'Uncategorized');
        chartValues = catData.map(d => parseFloat(d.amount));
    }

    new Chart(catCtx, {
        type: 'doughnut',
        data: {
            labels: chartLabels,
            datasets: [{
                data: chartValues,
                backgroundColor: [
                    '#c98570', '#e2a794', '#f0c7ba', '#4f46e5', '#818cf8', '#d1d5db'
                ],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 12, padding: 15, font: { size: 11, family: 'Inter' } }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            if(catData.length === 0) return ' 0';
                            return ' Rp ' + context.parsed.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>
</body>
</html>
