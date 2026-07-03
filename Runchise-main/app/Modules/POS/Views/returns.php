<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Runchise — Retur Transaksi</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary: #E2A794;
            --primary-dark: #c98570;
            --bg-dark: #FAF6F3;
            --bg-card: #FFFFFF;
            --text-primary: #2C1E1A;
            --text-muted: #8A756E;
            --border: rgba(226,167,148,0.25);
            --success: #2ec4b6;
            --danger: #e63946;
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg-dark); color: var(--text-primary); min-height: 100vh; }
        .card-nexapos { 
            background: var(--bg-card); 
            border: 1px solid var(--border); 
            border-radius: 16px; 
            box-shadow: 0 4px 20px rgba(226, 167, 148, 0.06); 
            padding: 2rem; 
        }
        .table { color: var(--text-primary); }
        .table th { 
            background: #faf5f2; 
            color: var(--text-muted); 
            font-size: 0.8rem; 
            text-transform: uppercase; 
            border-bottom: 1px solid var(--border); 
            padding: 0.75rem 1rem; 
        }
        .table td { border-bottom: 1px solid var(--border); vertical-align: middle; padding: 0.75rem 1rem; }
        .btn-primary-nexapos { 
            background: linear-gradient(135deg, #E2A794, #d97757); 
            border: none; 
            color: white; 
            padding: 0.6rem 1.5rem; 
            border-radius: 10px; 
            font-weight: 600; 
            transition: all 0.2s; 
        }
        .btn-primary-nexapos:hover { transform: translateY(-1px); box-shadow: 0 4px 15px rgba(226,167,148,0.3); color: white; }
        .btn-outline-nexapos { 
            border: 1px solid var(--primary); 
            color: var(--primary); 
            background: transparent; 
            padding: 0.6rem 1.5rem; 
            border-radius: 10px; 
            font-weight: 600; 
            transition: all 0.2s; 
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-outline-nexapos:hover { background: var(--primary); color: white; }
        .form-select, .form-control {
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0.6rem 1rem;
            color: var(--text-primary);
        }
        .form-select:focus, .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(226, 167, 148, 0.15);
        }
        .nav-tabs-nexapos {
            border-bottom: 2px solid var(--border);
            margin-bottom: 2rem;
            display: flex;
            gap: 1.5rem;
        }
        .nav-link-nexapos {
            border: none;
            background: transparent;
            color: var(--text-muted);
            font-weight: 600;
            padding: 0.75rem 0.5rem;
            position: relative;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.95rem;
            text-decoration: none;
        }
        .nav-link-nexapos:hover {
            color: var(--primary-dark);
        }
        .nav-link-nexapos.active {
            color: var(--text-primary);
        }
        .nav-link-nexapos.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--primary), var(--primary-dark));
        }
        .badge-paid {
            background-color: rgba(46, 196, 182, 0.1);
            color: var(--success);
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.8rem;
        }
        .badge-refunded {
            background-color: rgba(230, 57, 70, 0.1);
            color: var(--danger);
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.8rem;
        }
        /* Custom styling for Return form in modal */
        .modal-content-nexapos {
            border-radius: 18px;
            border: 1px solid var(--border);
            background: var(--bg-card);
            box-shadow: 0 10px 30px rgba(226, 167, 148, 0.15);
        }
        .modal-header-nexapos {
            border-bottom: 1px solid var(--border);
            padding: 1.5rem;
        }
        .modal-body-nexapos {
            padding: 1.5rem;
        }
        .modal-footer-nexapos {
            border-top: 1px solid var(--border);
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .return-item-row {
            background-color: rgba(250, 246, 243, 0.5);
            border-radius: 12px;
            border: 1px solid var(--border);
            padding: 1rem;
            margin-bottom: 1rem;
            transition: all 0.2s;
        }
        .return-item-row.active {
            border-color: var(--primary);
            background-color: rgba(226, 167, 148, 0.05);
        }
        .text-price {
            font-family: monospace;
            font-weight: 600;
        }
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
            <div>
                <h2 style="font-size: 1.25rem; font-weight: 700; margin: 0; color: var(--text-primary);">🔄 Retur Transaksi</h2>
                <span class="text-muted" style="font-size:0.85rem; font-weight: 500;">Refund & Returns Management Center</span>
            </div>
            <a href="/pos/terminal" class="btn-outline-nexapos"><i class="bi bi-calculator"></i> Buka Kasir / Terminal</a>
        </div>

        <!-- Alert messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success d-flex align-items-center gap-2 mb-4" role="alert" style="border-radius: 12px; border: 1px solid #c3e6cb; background-color: #d4edda; color: #155724;">
                <i class="bi bi-check-circle-fill"></i>
                <div><?= session()->getFlashdata('success') ?></div>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" role="alert" style="border-radius: 12px; border: 1px solid #f5c6cb; background-color: #f8d7da; color: #721c24;">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div><?= session()->getFlashdata('error') ?></div>
            </div>
        <?php endif; ?>

        <!-- Tab Controls -->
        <div class="nav-tabs-nexapos">
            <button class="nav-link-nexapos active" id="tab-transactions-btn" onclick="switchTab('transactions')">
                <i class="bi bi-receipt me-1"></i> Transaksi Selesai
            </button>
            <button class="nav-link-nexapos" id="tab-history-btn" onclick="switchTab('history')">
                <i class="bi bi-clock-history me-1"></i> Riwayat Retur
            </button>
        </div>

        <!-- Tab 1: Transaksi Selesai -->
        <div id="tab-transactions">
            <div class="card-nexapos mb-4">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                    <div>
                        <h4 style="font-weight: 700; margin-bottom: 0.25rem;">🧾 Cari Transaksi untuk Diretur</h4>
                        <p style="color: var(--text-muted); font-size: 0.88rem; margin: 0;">Pilih transaksi berstatus "Paid" untuk memproses pengembalian dana atau barang.</p>
                    </div>
                    <div style="width: 320px; max-width: 100%;">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0" style="border-radius: 10px 0 0 10px; border-color: var(--border);"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" id="searchInput" onkeyup="filterTransactions()" class="form-control border-start-0" placeholder="Cari nomor invoice..." style="border-radius: 0 10px 10px 0;">
                        </div>
                    </div>
                </div>

                <div class="table-responsive" style="border-radius: 12px; border: 1px solid var(--border); overflow: hidden;">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Waktu Transaksi</th>
                                <th>Metode Pembayaran</th>
                                <th>Status Pembayaran</th>
                                <th class="text-end">Total Pembelian</th>
                                <th class="text-center" style="width: 180px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="transactionTableBody">
                            <?php if (!empty($transactions)): ?>
                                <?php foreach ($transactions as $tx): ?>
                                    <tr class="tx-row" data-invoice="<?= esc($tx['invoice_number']) ?>">
                                        <td>
                                            <div style="font-weight: 700; color: var(--text-primary);"><?= esc($tx['invoice_number']) ?></div>
                                            <div style="font-size: 0.72rem; color: var(--text-muted);">Session ID: #<?= $tx['pos_session_id'] ?></div>
                                        </td>
                                        <td style="font-size: 0.88rem; color: var(--text-muted);"><?= date('d M Y, H:i', strtotime($tx['created_at'])) ?></td>
                                        <td style="font-size: 0.88rem;"><span class="badge bg-light text-dark p-2" style="border-radius: 6px; font-weight: 600;"><?= esc($tx['payment_method']) ?></span></td>
                                        <td>
                                            <?php if ($tx['payment_status'] === 'Paid'): ?>
                                                <span class="badge-paid"><i class="bi bi-check-circle"></i> Selesai (Paid)</span>
                                            <?php else: ?>
                                                <span class="badge-refunded"><i class="bi bi-arrow-counterclockwise"></i> Refunded</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end text-price" style="font-weight: 700;">Rp <?= number_format($tx['total'], 0, ',', '.') ?></td>
                                        <td class="text-center">
                                            <?php if ($tx['payment_status'] === 'Paid'): ?>
                                                <button class="btn btn-primary-nexapos btn-sm" onclick="openReturnModal(<?= esc(json_encode($tx)) ?>)">
                                                    <i class="bi bi-arrow-counterclockwise"></i> Retur
                                                </button>
                                            <?php else: ?>
                                                <button class="btn btn-outline-secondary btn-sm" disabled style="border-radius:10px;">
                                                    <i class="bi bi-check-all"></i> Sudah Diretur
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-receipt-cutoff" style="font-size: 2rem;"></i>
                                        <div class="mt-2">Belum ada data transaksi yang tercatat.</div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab 2: Riwayat Retur -->
        <div id="tab-history" style="display: none;">
            <div class="card-nexapos">
                <div class="mb-4">
                    <h4 style="font-weight: 700; margin-bottom: 0.25rem;">📜 Log Aktivitas Retur Transaksi</h4>
                    <p style="color: var(--text-muted); font-size: 0.88rem; margin: 0;">Catatan lengkap pengembalian dana dan penyesuaian stok yang telah diproses.</p>
                </div>

                <div class="table-responsive" style="border-radius: 12px; border: 1px solid var(--border); overflow: hidden;">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>ID Retur</th>
                                <th>Invoice Transaksi</th>
                                <th>Tanggal Retur</th>
                                <th>Rincian Item yang Diretur</th>
                                <th class="text-end" style="width: 180px;">Total Refunded</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($returnsHistory)): ?>
                                <?php foreach ($returnsHistory as $ret): ?>
                                    <tr>
                                        <td><span class="badge bg-secondary p-2" style="border-radius: 6px; font-family: monospace;">#RET-<?= str_pad($ret['id'], 5, '0', STR_PAD_LEFT) ?></span></td>
                                        <td>
                                            <div style="font-weight: 700; color: var(--text-primary);"><?= esc($ret['invoice_number']) ?></div>
                                            <div style="font-size: 0.72rem; color: var(--text-muted);">Trx Date: <?= date('d M Y', strtotime($ret['transaction_date'])) ?></div>
                                        </td>
                                        <td style="font-size: 0.88rem; color: var(--text-muted);"><?= date('d M Y, H:i', strtotime($ret['created_at'])) ?></td>
                                        <td>
                                            <div class="d-flex flex-column gap-1">
                                                <?php foreach ($ret['items'] as $item): ?>
                                                    <div style="font-size: 0.85rem;" class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-light text-dark text-price"><?= (float)$item['quantity'] ?>x</span>
                                                        <span style="font-weight: 500;"><?= esc($item['product_name']) ?></span>
                                                        <span class="text-muted" style="font-size: 0.75rem;">(<?= esc($item['sku']) ?>)</span>
                                                        <span class="text-muted" style="font-size: 0.75rem; font-style: italic;">- "<?= esc($item['reason']) ?>"</span>
                                                        <?php if ($item['returned_to_stock']): ?>
                                                            <span class="badge bg-success-subtle text-success" style="font-size: 0.65rem; border-radius: 4px;">Kembali ke Stok</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-danger-subtle text-danger" style="font-size: 0.65rem; border-radius: 4px;">Dibuang / Rusak</span>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </td>
                                        <td class="text-end text-price text-success" style="font-weight: 700; font-size: 1rem;">
                                            + Rp <?= number_format($ret['total_refunded'], 0, ',', '.') ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-clock" style="font-size: 2rem;"></i>
                                        <div class="mt-2">Belum ada riwayat retur yang diproses.</div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Return Modal -->
<div class="modal fade" id="returnModal" tabindex="-1" aria-labelledby="returnModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modal-content-nexapos">
            <form action="/pos/returns/process" method="POST" onsubmit="return validateReturnSubmit()">
                <?= csrf_field() ?>
                <input type="hidden" name="transaction_id" id="modalTransactionId">
                
                <div class="modal-header modal-header-nexapos">
                    <div>
                        <h5 class="modal-title" id="returnModalLabel" style="font-weight: 700; color: var(--text-primary);"><i class="bi bi-arrow-counterclockwise"></i> Form Retur Transaksi</h5>
                        <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.25rem;">
                            Invoice: <strong id="modalInvoiceText" class="text-dark"></strong> &bull; 
                            Total Pembayaran: <strong id="modalTotalText" class="text-dark text-price"></strong>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body modal-body-nexapos" style="max-height: 480px; overflow-y: auto;">
                    <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1.5rem;">Centang item yang ingin diretur, masukkan jumlah yang dikembalikan, dan tentukan apakah item akan ditambahkan kembali ke inventori.</p>
                    
                    <div id="modalItemsContainer">
                        <!-- Dynamic JS Populated Items -->
                    </div>
                </div>
                
                <div class="modal-footer modal-footer-nexapos">
                    <div>
                        <div class="text-muted" style="font-size: 0.8rem; font-weight: 600;">ESTIMASI PENGEMBALIAN DANA (REFUND)</div>
                        <div class="text-success text-price" style="font-size: 1.35rem; font-weight: 700;" id="modalTotalRefundText">Rp 0</div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 10px; font-weight: 600;">Batal</button>
                        <button type="submit" class="btn-primary-nexapos" id="submitReturnBtn" disabled><i class="bi bi-check-circle"></i> Terapkan Retur</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let returnModalInstance;

    document.addEventListener('DOMContentLoaded', () => {
        returnModalInstance = new bootstrap.Modal(document.getElementById('returnModal'));
    });

    function switchTab(tabName) {
        // Toggle tab visibility
        if (tabName === 'transactions') {
            document.getElementById('tab-transactions').style.display = 'block';
            document.getElementById('tab-history').style.display = 'none';
            document.getElementById('tab-transactions-btn').classList.add('active');
            document.getElementById('tab-history-btn').classList.remove('active');
        } else {
            document.getElementById('tab-transactions').style.display = 'none';
            document.getElementById('tab-history').style.display = 'block';
            document.getElementById('tab-transactions-btn').classList.remove('active');
            document.getElementById('tab-history-btn').classList.add('active');
        }
    }

    function filterTransactions() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toUpperCase();
        const rows = document.querySelectorAll('.tx-row');

        rows.forEach(row => {
            const invoice = row.getAttribute('data-invoice') || '';
            if (invoice.toUpperCase().indexOf(filter) > -1) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function openReturnModal(tx) {
        document.getElementById('modalTransactionId').value = tx.id;
        document.getElementById('modalInvoiceText').textContent = tx.invoice_number;
        document.getElementById('modalTotalText').textContent = 'Rp ' + Number(tx.total).toLocaleString('id-ID');
        
        const container = document.getElementById('modalItemsContainer');
        container.innerHTML = '';
        
        tx.items.forEach(item => {
            const remainingQty = Number(item.quantity) - Number(item.returned_quantity);
            
            // Calculate item single rate including tax/discounts
            const singleLineRate = Number(item.total) / Number(item.quantity);
            
            const isReturnable = remainingQty > 0;
            
            const itemRow = document.createElement('div');
            itemRow.className = `return-item-row d-flex flex-wrap align-items-center justify-content-between gap-3 ${!isReturnable ? 'opacity-50' : ''}`;
            
            itemRow.innerHTML = `
                <div class="d-flex align-items-center gap-3 col-12 col-md-5">
                    <div class="form-check">
                        <input class="form-check-input return-checkbox" type="checkbox" 
                               id="chk-${item.id}" 
                               data-item-id="${item.id}"
                               data-line-rate="${singleLineRate}"
                               onchange="toggleItemRow(${item.id})"
                               ${!isReturnable ? 'disabled' : ''}>
                    </div>
                    <div>
                        <div style="font-weight: 700; color: var(--text-primary);">${item.product_name}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">
                            SKU: ${item.sku} &bull; 
                            Beli: ${Number(item.quantity)} &bull; 
                            Retur: ${Number(item.returned_quantity)}
                        </div>
                    </div>
                </div>
                
                <div class="d-flex align-items-center justify-content-end gap-3 flex-grow-1">
                    <div style="width: 120px;">
                        <label class="form-label mb-1" style="font-size: 0.75rem; font-weight: 600; color: var(--text-muted);">Jumlah Retur</label>
                        <input type="number" 
                               name="return_items[${item.id}]" 
                               id="qty-${item.id}" 
                               class="form-control text-center qty-input" 
                               style="padding: 0.4rem;"
                               value="0" 
                               min="0.01" 
                               max="${remainingQty}" 
                               step="any"
                               disabled
                               oninput="calculateRefund()"
                               data-item-id="${item.id}">
                    </div>
                    
                    <div style="width: 180px;">
                        <label class="form-label mb-1" style="font-size: 0.75rem; font-weight: 600; color: var(--text-muted);">Alasan</label>
                        <select name="return_reasons[${item.id}]" id="reason-${item.id}" class="form-select form-select-sm" disabled>
                            <option value="Salah Ukuran / Varian">Salah Varian</option>
                            <option value="Barang Cacat / Rusak">Cacat / Rusak</option>
                            <option value="Expired / Kualitas Buruk">Expired</option>
                            <option value="Kemauan Pelanggan (Cancel)">Batal Pelanggan</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="form-check form-switch pt-4">
                        <input class="form-check-input" type="checkbox" role="switch" 
                               name="return_to_stock[${item.id}]" 
                               id="stock-${item.id}" 
                               value="1" 
                               checked 
                               disabled>
                        <label class="form-check-label" for="stock-${item.id}" style="font-size: 0.75rem; font-weight: 600; color: var(--text-muted);">Masuk Stok</label>
                    </div>
                </div>
            `;
            
            container.appendChild(itemRow);
        });
        
        calculateRefund();
        returnModalInstance.show();
    }

    function toggleItemRow(itemId) {
        const checkbox = document.getElementById(`chk-${itemId}`);
        const qtyInput = document.getElementById(`qty-${itemId}`);
        const reasonInput = document.getElementById(`reason-${itemId}`);
        const stockSwitch = document.getElementById(`stock-${itemId}`);
        
        const row = checkbox.closest('.return-item-row');
        
        if (checkbox.checked) {
            qtyInput.disabled = false;
            reasonInput.disabled = false;
            stockSwitch.disabled = false;
            row.classList.add('active');
            
            // Set qty to max returnable default if 0
            if (Number(qtyInput.value) === 0) {
                qtyInput.value = qtyInput.getAttribute('max');
            }
        } else {
            qtyInput.disabled = true;
            reasonInput.disabled = true;
            stockSwitch.disabled = true;
            row.classList.remove('active');
            qtyInput.value = 0;
        }
        
        calculateRefund();
    }

    function calculateRefund() {
        let totalRefund = 0;
        let anyChecked = false;
        
        const checkboxes = document.querySelectorAll('.return-checkbox');
        checkboxes.forEach(chk => {
            if (chk.checked) {
                anyChecked = true;
                const itemId = chk.getAttribute('data-item-id');
                const lineRate = Number(chk.getAttribute('data-line-rate'));
                const qtyInput = document.getElementById(`qty-${itemId}`);
                const qty = Number(qtyInput.value) || 0;
                
                totalRefund += qty * lineRate;
            }
        });
        
        document.getElementById('modalTotalRefundText').textContent = 'Rp ' + Math.round(totalRefund).toLocaleString('id-ID');
        document.getElementById('submitReturnBtn').disabled = !anyChecked;
    }

    function validateReturnSubmit() {
        let isValid = true;
        const checkboxes = document.querySelectorAll('.return-checkbox');
        let checkedCount = 0;
        
        checkboxes.forEach(chk => {
            if (chk.checked) {
                checkedCount++;
                const itemId = chk.getAttribute('data-item-id');
                const qtyInput = document.getElementById(`qty-${itemId}`);
                const qty = Number(qtyInput.value) || 0;
                const max = Number(qtyInput.getAttribute('max'));
                
                if (qty <= 0) {
                    alert('Kuantitas retur harus lebih besar dari 0.');
                    isValid = false;
                } else if (qty > max) {
                    alert(`Kuantitas retur melebihi batas pengembalian (${max}).`);
                    isValid = false;
                }
            }
        });
        
        if (checkedCount === 0) {
            alert('Silakan pilih minimal satu item untuk diretur.');
            isValid = false;
        }
        
        return isValid;
    }
</script>

</body>
</html>
