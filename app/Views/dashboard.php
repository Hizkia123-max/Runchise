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
            --primary: #E2A794;
            --primary-dark: #c98570;
            --secondary: #4f46e5;
            --bg-dark: #140f0e;
            --bg-card: rgba(34, 26, 24, 0.85);
            --border-light: rgba(226, 167, 148, 0.15);
            --text-primary: #f5eae6;
            --text-muted: #bdafa9;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-dark);
            color: var(--text-primary);
            min-height: 100vh;
            background-image:
                radial-gradient(ellipse at 10% 20%, rgba(226, 167, 148, 0.18) 0%, transparent 50%),
                radial-gradient(ellipse at 90% 80%, rgba(226, 167, 148, 0.1) 0%, transparent 50%);
            padding-bottom: 3rem;
        }
        /* Top Navigation Navbar */
        .navbar-premium {
            background: rgba(20, 15, 14, 0.8);
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
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
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
            background: linear-gradient(135deg, rgba(226, 167, 148, 0.2), rgba(34, 26, 24, 0.85));
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
            border-color: rgba(226, 167, 148, 0.3);
            box-shadow: 0 10px 30px rgba(20, 15, 14, 0.5);
        }
        .kpi-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem;
        }
        .icon-products { background: rgba(226, 167, 148, 0.2); color: #E2A794; }
        .icon-stock { background: rgba(245, 158, 11, 0.15); color: #fbbf24; }
        .icon-sessions { background: rgba(226, 167, 148, 0.2); color: #ffbe98; }
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

<div class="d-flex" style="min-height: 100vh;">
    <!-- Shared Premium Sidebar -->
    <?= view('partials/sidebar') ?>

    <!-- Main Content Area -->
    <div class="flex-grow-1" style="overflow-x: hidden; padding-bottom: 3rem;">
        <!-- Header Navigation Bar -->
        <header class="navbar-premium">
            <div>
                <h2 style="font-size: 1.15rem; font-weight: 700; margin: 0; color: var(--text-primary);">📊 Overview Dashboard</h2>
            </div>
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
            <div class="glass-panel mb-4">
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

            <!-- Recent Sales Transactions Panel -->
            <div class="section-title">📊 Recent Sales Transactions</div>
            <div class="glass-panel">
                <?php if (empty($recentTransactions)): ?>
                    <div class="text-center py-4 text-muted" style="font-size: 0.95rem;">
                        💸 No transactions recorded yet. Open the POS Terminal to make a sale!
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Invoice Number</th>
                                    <th>Payment Method</th>
                                    <th class="text-center">Subtotal</th>
                                    <th class="text-center">PPN Tax</th>
                                    <th class="text-center">Grand Total</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Date & Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentTransactions as $tx): ?>
                                    <tr>
                                        <td>
                                            <a href="#" class="view-invoice-details" data-id="<?= $tx['id'] ?>" style="font-family: monospace; font-weight: 700; color: var(--primary); text-decoration: none; border-bottom: 1px dashed var(--primary); transition: color 0.2s;">
                                                <?= esc($tx['invoice_number']) ?>
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary" style="background-color: rgba(148,163,184,0.15) !important; color: var(--text-primary); border: 1px solid var(--border-light);"><?= esc($tx['payment_method']) ?></span>
                                        </td>
                                        <td class="text-center">Rp <?= number_format($tx['subtotal']) ?></td>
                                        <td class="text-center text-muted">Rp <?= number_format($tx['tax_amount']) ?></td>
                                        <td class="text-center" style="font-weight: bold; color: #2dd4bf;">Rp <?= number_format($tx['total']) ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-success" style="background-color: rgba(16, 185, 129, 0.2) !important; color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3);"><?= esc($tx['payment_status']) ?></span>
                                        </td>
                                        <td class="text-center" style="font-size:0.85rem; color:var(--text-muted);"><?= esc($tx['created_at']) ?></td>
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

<!-- Invoice Details Bootstrap Modal popup -->
<div class="modal fade" id="invoiceDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: #221a18; border: 1px solid rgba(226, 167, 148, 0.15); color: #f5eae6; border-radius: 16px; backdrop-filter: blur(15px); box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title" style="font-weight: 700; color: #E2A794;"><i class="bi bi-receipt"></i> Rincian Struk Pembayaran</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <div id="modalInvoiceLoader" class="text-center py-4">
                    <div class="spinner-border text-teal" style="color: #E2A794;" role="status"></div>
                    <div class="text-muted mt-2" style="font-size:0.85rem;">Memuat rincian struk belanja...</div>
                </div>
                <div id="modalInvoiceContent" style="display: none;">
                    <div class="text-center mb-4">
                        <div style="font-size: 1.5rem; font-weight: 800; color: #f5eae6; letter-spacing:-0.02em;">⚡ RUNCHISE</div>
                        <div class="text-muted" style="font-size:0.8rem;">Main Branch Store</div>
                        <hr style="border-top: 1px dashed rgba(255,255,255,0.15); margin: 0.75rem 0;">
                        <div class="d-flex justify-content-between text-start px-2" style="font-size: 0.8rem; color: #bdafa9;">
                            <div>Invoice: <strong id="invoiceReceiptNo" style="color: #f5eae6;"></strong></div>
                            <div class="text-end" id="invoiceReceiptDate"></div>
                        </div>
                    </div>
                    
                    <!-- Items Purchased -->
                    <div class="px-2 mb-3">
                        <table class="table table-sm table-borderless" style="font-size: 0.85rem; color: #f5eae6 !important;">
                            <thead>
                                <tr style="border-bottom: 1px dashed rgba(255,255,255,0.15); color: #bdafa9 !important; font-size: 0.75rem; text-transform: uppercase;">
                                    <th>Item</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody id="invoiceReceiptItems" style="color: #f5eae6 !important;">
                                <!-- Dynamically Rendered -->
                            </tbody>
                        </table>
                    </div>
                    
                    <hr style="border-top: 1px dashed rgba(255,255,255,0.15); margin: 1rem 0;">
                    
                    <!-- Pricing Totals -->
                    <div class="px-2" style="font-size: 0.85rem;">
                        <div class="d-flex justify-content-between mb-1">
                            <span style="color:#bdafa9;">Subtotal</span>
                            <span id="invoiceReceiptSubtotal"></span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span style="color:#bdafa9;">Diskon</span>
                            <span id="invoiceReceiptDiscount"></span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span style="color:#bdafa9;">PPN (11%)</span>
                            <span id="invoiceReceiptTax"></span>
                        </div>
                        <div class="d-flex justify-content-between font-weight-bold" style="font-size: 1.05rem; font-weight: 700; color: #E2A794; border-top: 1px solid rgba(255,255,255,0.1); padding-top:0.5rem; margin-top:0.5rem;">
                            <span>Grand Total</span>
                            <span id="invoiceReceiptGrandTotal"></span>
                        </div>
                    </div>
                    
                    <hr style="border-top: 1px dashed rgba(255,255,255,0.15); margin: 1.25rem 0;">
                    
                    <div class="text-center px-2" style="font-size: 0.8rem; color: #bdafa9;">
                        <div class="mb-2">Metode Pembayaran: <span class="badge" id="invoiceReceiptMethod" style="background-color: rgba(16, 185, 129, 0.2) !important; color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3); padding: 0.3rem 0.6rem;"></span></div>
                        <div>Terima kasih atas kunjungan Anda di Runchise!</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = new bootstrap.Modal(document.getElementById('invoiceDetailsModal'));
        
        document.querySelectorAll('.view-invoice-details').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const txId = btn.dataset.id;
                
                // Show loading spinner and trigger modal popup
                document.getElementById('modalInvoiceLoader').style.display = 'block';
                document.getElementById('modalInvoiceContent').style.display = 'none';
                modal.show();
                
                // Fetch transaction receipt details via AJAX
                fetch(`/api/v1/pos/transactions/${txId}`, {
                    headers: { 'Accept': 'application/json' }
                })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        const tx = res.data;
                        document.getElementById('invoiceReceiptNo').textContent = tx.invoice_number;
                        document.getElementById('invoiceReceiptDate').textContent = new Date(tx.created_at).toLocaleString('id-ID');
                        document.getElementById('invoiceReceiptMethod').textContent = tx.payment_method;
                        
                        const fmt = (n) => 'Rp ' + Math.round(n).toLocaleString('id-ID');
                        document.getElementById('invoiceReceiptSubtotal').textContent = fmt(tx.subtotal);
                        document.getElementById('invoiceReceiptDiscount').textContent = fmt(tx.discount_amount || 0);
                        document.getElementById('invoiceReceiptTax').textContent = fmt(tx.tax_amount);
                        document.getElementById('invoiceReceiptGrandTotal').textContent = fmt(tx.total);
                        
                        // Render line items
                        const tbody = document.getElementById('invoiceReceiptItems');
                        tbody.innerHTML = '';
                        tx.items.forEach(item => {
                            const tr = document.createElement('tr');
                            tr.style.color = '#f1f5f9';
                            tr.innerHTML = `
                                <td>${item.product_name || '📦 SKU Produk'}</td>
                                <td class="text-center">${Math.round(item.quantity)}</td>
                                <td class="text-end">${fmt(item.unit_price)}</td>
                                <td class="text-end">${fmt(item.total)}</td>
                            `;
                            tbody.appendChild(tr);
                        });
                        
                        document.getElementById('modalInvoiceLoader').style.display = 'none';
                        document.getElementById('modalInvoiceContent').style.display = 'block';
                    } else {
                        alert('Gagal memuat rincian transaksi: ' + res.message);
                        modal.hide();
                    }
                })
                .catch(err => {
                    alert('Terjadi kesalahan jaringan: ' + err.message);
                    modal.hide();
                });
            });
        });
    });
</script>
</body>
</html>
