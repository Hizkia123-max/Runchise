<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Runchise — Stock Transfers</title>
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
                <h2 style="font-size: 1.25rem; font-weight: 700; margin: 0; color: var(--text-primary);">🔄 Mutasi Stok (Stock Transfers)</h2>
                <span class="text-muted" style="font-size:0.85rem; font-weight: 500;">Inter-branch stock distribution manager</span>
            </div>
            <a href="/inventory/stock" class="btn-outline-nexapos"><i class="bi bi-arrow-left"></i> Kembali ke Stok</a>
        </div>

        <div class="card-nexapos">
            <h4 style="font-weight: 700; margin-bottom: 0.5rem;">🔄 Buat Mutasi Stok Baru</h4>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 2rem;">Distribusikan persediaan barang dari satu cabang asal ke cabang tujuan dengan aman dan tercatat.</p>

            <form action="/inventory/transfers/apply" method="POST" id="transferForm">
                <?= csrf_field() ?>
                
                <!-- Branch Selection -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6 col-lg-4">
                        <label class="form-label" style="font-weight: 600; font-size: 0.85rem; color: var(--text-muted);">Cabang Asal (Source)</label>
                        <select name="from_branch" id="fromBranch" class="form-select">
                            <?php foreach ($branches as $b): ?>
                                <option value="<?= $b['id'] ?>"><?= esc($b['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <label class="form-label" style="font-weight: 600; font-size: 0.85rem; color: var(--text-muted);">Cabang Tujuan (Target)</label>
                        <select name="to_branch" id="toBranch" class="form-select">
                            <?php 
                            // Select second branch by default if exists
                            $idx = 0;
                            foreach ($branches as $b): 
                                $selected = ($idx === 1) ? 'selected' : '';
                                $idx++;
                            ?>
                                <option value="<?= $b['id'] ?>" <?= $selected ?>><?= esc($b['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Products Table -->
                <div class="table-responsive mb-4" style="border-radius: 12px; border: 1px solid var(--border); overflow: hidden;">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Produk / SKU</th>
                                <th>Cabang Asal</th>
                                <th class="text-center" style="width: 180px;">Stok Tersedia Saat Ini</th>
                                <th class="text-center" style="width: 180px;">Jumlah Mutasi</th>
                            </tr>
                        </thead>
                        <tbody id="transferTableBody">
                            <?php if (!empty($stocks)): ?>
                                <?php foreach ($stocks as $s): ?>
                                    <tr class="transfer-row" data-branch-id="<?= $s['branch_id'] ?>">
                                        <td>
                                            <div style="font-weight: 600; color: var(--text-primary);"><?= esc($s['product_name']) ?></div>
                                            <div style="font-size: 0.75rem; color: var(--text-muted);"><?= esc($s['product_sku']) ?></div>
                                        </td>
                                        <td style="font-size: 0.85rem; color: var(--text-muted);"><?= esc($s['branch_name']) ?></td>
                                        <td class="text-center" style="font-weight: 700; font-size: 0.95rem;"><?= $s['quantity'] ?></td>
                                        <td class="text-center">
                                            <input type="number" 
                                                   name="items[<?= $s['product_id'] ?>]" 
                                                   class="form-control text-center transfer-input" 
                                                   style="padding: 0.4rem; font-weight: 700;"
                                                   data-max="<?= $s['quantity'] ?>"
                                                   placeholder="0"
                                                   min="0"
                                                   max="<?= $s['quantity'] ?>">
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        Tidak ada produk dalam persediaan cabang ini. Silakan tambahkan stok terlebih dahulu.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end gap-3">
                    <a href="/inventory/stock" class="btn btn-light py-2 px-4" style="border-radius: 10px; font-weight: 600;">Batal</a>
                    <button type="submit" class="btn-primary-nexapos"><i class="bi bi-arrow-left-right"></i> Kirim Mutasi Stok</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const fromBranch = document.getElementById('fromBranch');
    const toBranch = document.getElementById('toBranch');
    const rows = document.querySelectorAll('.transfer-row');
    const form = document.getElementById('transferForm');

    // 1. Filter rows based on Source Branch selection
    function filterBySourceBranch() {
        const sourceId = fromBranch.value;
        
        rows.forEach(row => {
            if (row.dataset.branchId === sourceId) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
                // Reset value of hidden inputs
                const input = row.querySelector('.transfer-input');
                if (input) input.value = '';
            }
        });
    }

    if (fromBranch) {
        fromBranch.addEventListener('change', filterBySourceBranch);
        filterBySourceBranch(); // Initial filter
    }

    // 2. Validate transfer submit
    form.addEventListener('submit', (e) => {
        if (fromBranch.value === toBranch.value) {
            alert('Cabang Asal dan Cabang Tujuan tidak boleh sama!');
            e.preventDefault();
            return;
        }

        let hasItems = false;
        let limitExceeded = false;
        const visibleInputs = document.querySelectorAll('.transfer-row:not([style*="display: none"]) .transfer-input');
        
        visibleInputs.forEach(input => {
            const val = parseInt(input.value);
            if (val > 0) {
                hasItems = true;
                const max = parseInt(input.dataset.max);
                if (val > max) {
                    limitExceeded = true;
                }
            }
        });

        if (!hasItems) {
            alert('Masukkan setidaknya satu jumlah produk yang ingin dimutasi!');
            e.preventDefault();
            return;
        }

        if (limitExceeded) {
            alert('Beberapa jumlah mutasi melebihi stok yang tersedia saat ini!');
            e.preventDefault();
            return;
        }
    });
});
</script>
</body>
</html>
