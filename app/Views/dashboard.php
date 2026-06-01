<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Runchise — Overview Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #0d9488;
            --primary-dark: #0f766e;
            --secondary: #4f46e5;
            --bg-dark: #0f172a;
            --bg-card: rgba(30, 41, 59, 0.7);
            --border-light: rgba(148, 163, 184, 0.1);
            --text-primary: #f1f5f9;
            --text-muted: #94a3b8;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-dark);
            color: var(--text-primary);
            min-height: 100vh;
            background-image:
                radial-gradient(ellipse at 10% 20%, rgba(13,148,136,0.1) 0%, transparent 50%),
                radial-gradient(ellipse at 90% 80%, rgba(79,70,229,0.1) 0%, transparent 50%);
            padding-bottom: 3rem;
        }
        /* Top Navigation Navbar */
        .navbar-premium {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid var(--border-light);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar-brand-premium {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }
        .navbar-logo {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.25rem; font-weight: bold; color: white;
        }
        .navbar-title {
            color: var(--text-primary);
            font-weight: 700; font-size: 1.25rem; margin: 0;
        }
        .navbar-user-profile {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .user-details {
            text-align: right;
        }
        .user-name {
            font-size: 0.9rem; font-weight: 600; color: var(--text-primary);
        }
        .user-role {
            font-size: 0.75rem; color: var(--text-muted);
        }
        .btn-logout-premium {
            padding: 0.5rem 1rem;
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: 8px;
            color: #fca5a5;
            font-size: 0.85rem; font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-logout-premium:hover {
            background: rgba(239, 68, 68, 0.25);
            border-color: rgba(239, 68, 68, 0.4);
            transform: translateY(-1px);
        }

        /* Container Main */
        .main-container {
            max-width: 1200px;
            margin: 2.5rem auto 0;
            padding: 0 1.5rem;
        }

        /* Welcome Message Banner */
        .welcome-banner {
            background: linear-gradient(135deg, rgba(13, 148, 136, 0.15), rgba(79, 70, 229, 0.15));
            border: 1px solid var(--border-light);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2.5rem;
            backdrop-filter: blur(10px);
        }
        .welcome-banner h2 { font-weight: 700; font-size: 1.75rem; margin-bottom: 0.5rem; }
        .welcome-banner p { color: var(--text-muted); margin: 0; font-size: 0.95rem; }

        /* KPI Card grid */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }
        .kpi-card {
            background: var(--bg-card);
            border: 1px solid var(--border-light);
            border-radius: 16px;
            padding: 1.5rem;
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            gap: 1.25rem;
            transition: all 0.2s;
        }
        .kpi-card:hover {
            transform: translateY(-3px);
            border-color: rgba(148, 163, 184, 0.2);
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.3);
        }
        .kpi-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem;
        }
        .icon-products { background: rgba(13, 148, 136, 0.15); color: #2dd4bf; }
        .icon-stock { background: rgba(245, 158, 11, 0.15); color: #fbbf24; }
        .icon-sessions { background: rgba(79, 70, 229, 0.15); color: #818cf8; }
        .kpi-details h3 { font-size: 1.75rem; font-weight: 700; margin: 0 0 0.25rem 0; }
        .kpi-details p { font-size: 0.85rem; color: var(--text-muted); margin: 0; font-weight: 500; }

        /* Quick Action Panels */
        .section-title {
            font-size: 1.15rem; font-weight: 600; color: var(--text-primary);
            margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;
        }
        .action-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.25rem;
            margin-bottom: 3rem;
        }
        .action-card {
            background: var(--bg-card);
            border: 1px solid var(--border-light);
            border-radius: 14px;
            padding: 1.25rem;
            backdrop-filter: blur(10px);
            text-decoration: none;
            color: var(--text-primary);
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            transition: all 0.2s;
        }
        .action-card:hover {
            transform: translateY(-2px);
            border-color: var(--primary);
            box-shadow: 0 8px 20px rgba(13, 148, 136, 0.15);
            color: var(--text-primary);
        }
        .action-title { font-weight: 600; font-size: 0.95rem; display: flex; align-items: center; gap: 0.5rem; }
        .action-desc { font-size: 0.8rem; color: var(--text-muted); margin: 0; line-height: 1.4; }

        /* Table Design */
        .glass-panel {
            background: var(--bg-card);
            border: 1px solid var(--border-light);
            border-radius: 16px;
            padding: 1.5rem;
            backdrop-filter: blur(10px);
        }
        .table {
            color: var(--text-primary);
            margin: 0;
        }
        .table th {
            border-bottom: 2px solid rgba(148, 163, 184, 0.15);
            color: var(--text-muted);
            font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;
            padding: 0.75rem 1rem;
        }
        .table td {
            border-bottom: 1px solid rgba(148, 163, 184, 0.08);
            font-size: 0.9rem; padding: 1rem;
            vertical-align: middle;
        }
        .table tr:last-child td { border-bottom: none; }
        .badge-alert {
            background: rgba(239, 68, 68, 0.15);
            color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 6px; padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: 600;
        }
    </style>
</head>
<body>

    <!-- Header Navigation -->
    <header class="navbar-premium">
        <a href="/dashboard" class="navbar-brand-premium">
            <div class="navbar-logo">⚡</div>
            <h1 class="navbar-title">Runchise</h1>
        </a>
        <div class="navbar-user-profile">
            <div class="user-details">
                <div class="user-name"><?= esc($userName) ?></div>
                <div class="user-role"><?= esc($userRole) ?></div>
            </div>
            <a href="/auth/logout" class="btn-logout-premium">Sign Out →</a>
        </div>
    </header>

    <div class="main-container">
        <!-- Welcome banner -->
        <div class="welcome-banner">
            <h2>Welcome back, <?= esc($userName) ?>!</h2>
            <p>Platform status is nominal. Here is the operational overview for your tenant store branch.</p>
        </div>

        <!-- KPI Grid -->
        <div class="kpi-grid">
            <div class="kpi-card">
                <div class="kpi-icon icon-products">📦</div>
                <div class="kpi-details">
                    <h3><?= esc($totalProducts) ?></h3>
                    <p>Total Registered Products</p>
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon icon-stock">⚠</div>
                <div class="kpi-details">
                    <h3><?= esc($lowStockCount) ?></h3>
                    <p>Low Stock Alert Items</p>
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon icon-sessions">💻</div>
                <div class="kpi-details">
                    <h3><?= esc($activeSessions) ?></h3>
                    <p>Active POS Sessions</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions Section -->
        <div class="section-title">⚡ Quick Navigation Panels</div>
        <div class="action-grid">
            <a href="/pos/terminal" class="action-card">
                <div class="action-title">🖥 POS Terminal</div>
                <p class="action-desc">Launch the sales checkout register interface to process customer orders and print invoice receipts.</p>
            </a>
            <a href="/pos/sessions" class="action-card">
                <div class="action-title">📅 POS Shifts & Sessions</div>
                <p class="action-desc">Manage cashier terminal sessions, track cash drawer opening/closing balances and active cashier shifts.</p>
            </a>
            <a href="/inventory/stock" class="action-card">
                <div class="action-title">📈 Stock Inventory</div>
                <p class="action-desc">Monitor inventory quantities across active branch stores, record stock levels, and view reorder limits.</p>
            </a>
            <a href="/inventory/products" class="action-card">
                <div class="action-title">📦 Product Catalog</div>
                <p class="action-desc">View registered SKU catalog products, configure sales prices, purchase costs, and SKU details.</p>
            </a>
        </div>

        <!-- Low Stock Alerts Panel -->
        <div class="section-title">⚠ Current Low Stock Alerts</div>
        <div class="glass-panel">
            <?php if (empty($lowStockItems)): ?>
                <div class="text-center py-4 text-muted" style="font-size: 0.95rem;">
                    🟢 All inventory stock levels are healthy! No low stock alert triggered.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>SKU</th>
                                <th>Product Name</th>
                                <th class="text-center">Current Quantity</th>
                                <th class="text-center">Reorder Threshold</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lowStockItems as $item): ?>
                                <tr>
                                    <td style="font-family: monospace; font-weight: bold; color: var(--primary);"><?= esc($item['sku']) ?></td>
                                    <td style="font-weight: 500;"><?= esc($item['name']) ?></td>
                                    <td class="text-center" style="font-weight: bold; color: #fca5a5;"><?= esc($item['quantity']) ?></td>
                                    <td class="text-center text-muted"><?= esc($item['reorder_point']) ?></td>
                                    <td class="text-center">
                                        <span class="badge-alert">Reorder Required</span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
