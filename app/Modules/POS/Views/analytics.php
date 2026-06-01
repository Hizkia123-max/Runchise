<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Runchise — Laporan & Laba Rugi</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
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
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-dark);
            color: var(--text-primary);
            min-height: 100vh;
            background-image:
                radial-gradient(ellipse at 10% 20%, rgba(226, 167, 148, 0.12) 0%, transparent 60%),
                radial-gradient(ellipse at 90% 80%, rgba(226, 167, 148, 0.08) 0%, transparent 60%);
        }
        .main-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }
        .glass-panel {
            background: var(--bg-card);
            border: 1px solid var(--border-light);
            border-radius: 16px;
            padding: 1.5rem;
            backdrop-filter: blur(10px);
            margin-bottom: 1.5rem;
        }
        .section-title {
            font-size: 1.15rem; font-weight: 600; color: var(--text-primary);
            margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;
        }
        
        /* Metric Cards */
        .metric-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .metric-card {
            background: var(--bg-card);
            border: 1px solid var(--border-light);
            border-radius: 14px;
            padding: 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: transform 0.2s;
            box-shadow: 0 4px 20px rgba(226, 167, 148, 0.06);
        }
        .metric-card:hover {
            transform: translateY(-2px);
            border-color: rgba(226, 167, 148, 0.4);
            box-shadow: 0 8px 25px rgba(226, 167, 148, 0.12);
        }
        .metric-icon {
            width: 44px; height: 44px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.25rem;
        }
        .icon-revenue { background: rgba(16, 185, 129, 0.12); color: #059669; }
        .icon-cogs { background: rgba(79, 70, 229, 0.12); color: #4f46e5; }
        .icon-waste { background: rgba(239, 68, 68, 0.12); color: #dc2626; }
        .icon-profit { background: rgba(226, 167, 148, 0.15); color: #b07765; }
        .metric-details h4 { font-size: 1.35rem; font-weight: 700; margin: 0 0 0.15rem 0; color: var(--text-primary); }
        .metric-details p { font-size: 0.75rem; color: var(--text-muted); margin: 0; font-weight: 500; text-transform: uppercase; }

        /* P&L Statement */
        .pl-table {
            color: var(--text-primary);
            width: 100%;
        }
        .pl-table tr {
            border-bottom: 1px solid rgba(226, 167, 148, 0.1);
        }
        .pl-table tr.total-row {
            border-top: 1px solid rgba(226, 167, 148, 0.3);
            border-bottom: 2px double rgba(226, 167, 148, 0.4);
            font-weight: 700;
        }
        .pl-table td {
            padding: 0.85rem 1rem;
            font-size: 0.9rem;
        }
        .pl-indent {
            padding-left: 2rem !important;
        }
    </style>
</head>
<body>

<div class="d-flex" style="min-height: 100vh;">
    <!-- Shared Premium Sidebar -->
    <?= view('partials/sidebar') ?>

    <!-- Main Content Area -->
    <div class="flex-grow-1" style="overflow-x: hidden; padding-bottom: 3rem;">
        <!-- Header Navigation Bar -->
        <header class="navbar-premium" style="background: rgba(250, 246, 243, 0.8); backdrop-filter: blur(15px); border-bottom: 1px solid var(--border-light); padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="font-size: 1.15rem; font-weight: 700; margin: 0; color: var(--text-primary);">📈 Laporan Keuangan & Laba Rugi</h2>
            </div>
            <div class="navbar-user-profile" style="display: flex; align-items: center; gap: 1rem;">
                <div class="user-details" style="text-align: right;">
                    <div class="user-name" style="font-size: 0.9rem; font-weight: 600; color: var(--text-primary);"><?= esc($userName) ?></div>
                    <div class="user-role" style="font-size: 0.75rem; color: var(--text-muted);"><?= esc($userRole) ?></div>
                </div>
                <a href="/auth/logout" class="btn-logout-premium" style="padding: 0.5rem 1rem; background: rgba(239, 68, 68, 0.08); border: 1px solid rgba(239, 68, 68, 0.15); border-radius: 8px; color: #d94646; font-size: 0.85rem; text-decoration: none; font-weight: 500; transition: all 0.2s;">Sign Out →</a>
            </div>
        </header>

        <div class="main-container">
            <!-- Financial KPI Metrics -->
            <div class="metric-grid">
                <div class="metric-card">
                    <div class="metric-icon icon-revenue"><i class="bi bi-wallet2"></i></div>
                    <div class="metric-details">
                        <h4>Rp <?= number_format($totalRevenue) ?></h4>
                        <p>Total Penjualan</p>
                    </div>
                </div>
                <div class="metric-card">
                    <div class="metric-icon icon-cogs"><i class="bi bi-cart-x"></i></div>
                    <div class="metric-details">
                        <h4>Rp <?= number_format($totalCOGS) ?></h4>
                        <p>Modal Terjual (COGS)</p>
                    </div>
                </div>
                <div class="metric-card">
                    <div class="metric-icon icon-waste"><i class="bi bi-trash"></i></div>
                    <div class="metric-details">
                        <h4 style="color:#fca5a5;">Rp <?= number_format($totalWasteCost) ?></h4>
                        <p>Kerugian Barang Terbuang</p>
                    </div>
                </div>
                <div class="metric-card">
                    <div class="metric-icon icon-profit"><i class="bi bi-cash-coin"></i></div>
                    <div class="metric-details">
                        <h4 style="color:#2dd4bf;">Rp <?= number_format($netProfit) ?></h4>
                        <p>Laba Bersih Toko</p>
                    </div>
                </div>
            </div>

            <!-- Visual Charts -->
            <div class="row mb-4">
                <!-- Sales Trend Chart -->
                <div class="col-md-7 mb-3">
                    <div class="glass-panel h-100">
                        <h6 style="font-weight: 600; font-size: 0.9rem; color: var(--text-muted); text-transform: uppercase; margin-bottom: 1.25rem;"><i class="bi bi-graph-up text-primary"></i> Grafik Laporan Penjualan (7 Hari Terakhir)</h6>
                        <div style="position: relative; height: 260px; width: 100%;">
                            <canvas id="salesTrendChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Best Sellers Pie Chart & Money breakdown -->
                <div class="col-md-5 mb-3">
                    <div class="glass-panel h-100">
                        <h6 style="font-weight: 600; font-size: 0.9rem; color: var(--text-muted); text-transform: uppercase; margin-bottom: 1.25rem;"><i class="bi bi-star text-warning"></i> Barang Terfavorit (Populer)</h6>
                        <div style="position: relative; height: 160px; width: 100%; margin-bottom: 1rem;">
                            <canvas id="bestSellersChart"></canvas>
                        </div>
                        <div style="font-size: 0.8rem; color: var(--text-muted); border-top: 1px solid var(--border-light); padding-top: 0.75rem;">
                            <div class="d-flex justify-content-between mb-1">
                                <span>💵 Tunai (Cash):</span>
                                <strong style="color:var(--text-primary);">Rp <?= number_format($payments['Cash']) ?></strong>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>💳 Kartu (Card):</span>
                                <strong style="color:var(--text-primary);">Rp <?= number_format($payments['Card']) ?></strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>📱 QRIS / E-Wallet:</span>
                                <strong style="color:var(--text-primary);">Rp <?= number_format($payments['QRIS']) ?></strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profit & Loss Statement Panel -->
            <div class="section-title"><i class="bi bi-file-earmark-bar-graph"></i> Lembar Laba Rugi Finansial (Profit & Loss)</div>
            <div class="glass-panel">
                <div class="table-responsive">
                    <table class="pl-table">
                        <tbody>
                            <!-- Revenue -->
                            <tr>
                                <td style="font-weight: 600;"><i class="bi bi-arrow-right-circle text-success me-1"></i> Pendapatan Operasional (Sales Revenue)</td>
                                <td class="text-end" style="font-weight: 600; color: #b07765;">Rp <?= number_format($totalRevenue) ?></td>
                            </tr>
                            
                            <!-- Cost of Goods Sold -->
                            <tr>
                                <td class="pl-indent text-muted">Dikurangi: Harga Pokok Penjualan (Cost of Goods Sold - COGS)</td>
                                <td class="text-end text-muted">(Rp <?= number_format($totalCOGS) ?>)</td>
                            </tr>
                            
                            <!-- Gross Profit -->
                            <tr style="background: rgba(226, 167, 148, 0.05); font-weight: 600;">
                                <td>Laba Kotor Operasional (Gross Profit)</td>
                                <td class="text-end" style="color:var(--text-primary);">Rp <?= number_format($grossProfit) ?></td>
                            </tr>
                            
                            <!-- Operational Expense - Waste Goods -->
                            <tr>
                                <td class="pl-indent text-muted">Dikurangi: Kerugian Barang Rusak & Expired (Wasted Cost)</td>
                                <td class="text-end" style="color: #d94646;">(Rp <?= number_format($totalWasteCost) ?>)</td>
                            </tr>
                            
                            <!-- Net Profit -->
                            <tr class="total-row" style="background: rgba(226, 167, 148, 0.15);">
                                <td style="font-size: 1rem; color: #b07765;"><i class="bi bi-cash-coin me-1"></i> Laba Bersih Toko (Net Income)</td>
                                <td class="text-end" style="font-size: 1rem; color: #b07765;">Rp <?= number_format($netProfit) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // --- Sales Trend Chart ---
        const salesData = <?= json_encode($dailySales) ?>;
        const trendCtx = document.getElementById('salesTrendChart').getContext('2d');
        
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: salesData.map(d => d.date),
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: salesData.map(d => d.amount),
                    borderColor: '#E2A794',
                    backgroundColor: 'rgba(226, 167, 148, 0.15)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#E2A794',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(226, 167, 148, 0.1)' },
                        ticks: { color: '#8A756E', font: { family: 'Inter', size: 10 } }
                    },
                    y: {
                        grid: { color: 'rgba(226, 167, 148, 0.1)' },
                        ticks: {
                            color: '#8A756E',
                            font: { family: 'Inter', size: 10 },
                            callback: function(val) {
                                return 'Rp ' + (val >= 1000000 ? (val/1000000).toFixed(1) + 'M' : (val/1000).toFixed(0) + 'k');
                            }
                        }
                    }
                }
            }
        });

        // --- Best Sellers Chart ---
        const favorites = <?= json_encode($bestSellers) ?>;
        const favCtx = document.getElementById('bestSellersChart').getContext('2d');
        
        new Chart(favCtx, {
            type: 'bar',
            data: {
                labels: favorites.map(f => f.name),
                datasets: [{
                    label: 'Unit Terjual',
                    data: favorites.map(f => f.qty),
                    backgroundColor: 'rgba(226, 167, 148, 0.75)',
                    hoverBackgroundColor: '#E2A794',
                    borderWidth: 0,
                    borderRadius: 6
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#8A756E', font: { family: 'Inter', size: 10 } }
                    },
                    y: {
                        grid: { display: false },
                        ticks: { color: '#2C1E1A', font: { family: 'Inter', size: 10 } }
                    }
                }
            }
        });
    });
</script>
</body>
</html>
