<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Runchise — Stock Opname</title>
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
                <h2 style="font-size: 1.25rem; font-weight: 700; margin: 0; color: var(--text-primary);">📋 Stock Opname (Penyesuaian Stok)</h2>
                <span class="text-muted" style="font-size:0.85rem; font-weight: 500;">Reconsiliation & Discrepancy Reporting</span>
            </div>
            <a href="/inventory/stock" class="btn-outline-nexapos"><i class="bi bi-arrow-left"></i> Kembali ke Stok</a>
        </div>

        <div class="card-nexapos">
            <h4 style="font-weight: 700; margin-bottom: 0.5rem;">📋 Catat Stock Opname Baru</h4>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 2rem;">Pilih cabang terlebih dahulu, masukkan kuantitas fisik yang ditemukan, lalu sistem akan menghitung selisih secara otomatis.</p>

            <form action="/inventory/opname/apply" method="POST">
                <?= csrf_field() ?>
                
                <!-- Branch Selection Dropdown -->
                <div class="row mb-4">
                    <div class="col-md-6 col-lg-4">
                        <label class="form-label" style="font-weight: 600; font-size: 0.85rem; color: var(--text-muted);">Pilih Cabang / Gudang</label>
                        <select id="branchSelector" class="form-select">
                            <?php foreach ($branches as $b): ?>
                                <option value="<?= $b['id'] ?>"><?= esc($b['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="table-responsive mb-4" style="border-radius: 12px; border: 1px solid var(--border); overflow: hidden;">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Produk / SKU</th>
                                <th>Cabang</th>
                                <th class="text-center" style="width: 150px;">Stok Sistem (Recorded)</th>
                                <th class="text-center" style="width: 150px;">Stok Fisik (Actual)</th>
                                <th class="text-center" style="width: 130px;">Selisih (Variance)</th>
                                <th>Keterangan / Alasan Selisih</th>
                            </tr>
                        </thead>
                        <tbody id="opnameTableBody">
                            <?php if (!empty($stocks)): ?>
                                <?php foreach ($stocks as $s): ?>
                                    <tr class="stock-row" data-branch-id="<?= $s['branch_id'] ?>">
                                        <td>
                                            <div style="font-weight: 600; color: var(--text-primary);"><?= esc($s['product_name']) ?></div>
                                            <div style="font-size: 0.75rem; color: var(--text-muted);"><?= esc($s['product_sku']) ?></div>
                                        </td>
                                        <td style="font-size: 0.85rem; color: var(--text-muted);"><?= esc($s['branch_name']) ?></td>
                                        <td class="text-center" style="font-weight: 700; font-size: 0.95rem;" id="recorded-qty-<?= $s['id'] ?>"><?= $s['quantity'] ?></td>
                                        <td class="text-center">
                                            <input type="number" 
                                                   name="items[<?= $s['id'] ?>]" 
                                                   class="form-control text-center physical-input" 
                                                   style="padding: 0.4rem; font-weight: 700;"
                                                   data-id="<?= $s['id'] ?>"
                                                   data-recorded="<?= $s['quantity'] ?>"
                                                   placeholder="—"
                                                   min="0">
                                        </td>
                                        <td class="text-center" style="font-weight: 700; font-size: 0.95rem;" id="variance-<?= $s['id'] ?>">0</td>
                                        <td>
                                            <input type="text" 
                                                   name="reasons[<?= $s['id'] ?>]" 
                                                   class="form-control form-control-sm" 
                                                   placeholder="Keterangan (misal: Rusak, Expired, dll)">
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        Tidak ada produk dalam persediaan cabang ini. Silakan tambahkan stok terlebih dahulu.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end gap-3">
                    <a href="/inventory/stock" class="btn btn-light py-2 px-4" style="border-radius: 10px; font-weight: 600;">Batal</a>
                    <button type="submit" class="btn-primary-nexapos"><i class="bi bi-check-circle"></i> Terapkan Penyesuaian Stok</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const branchSelector = document.getElementById('branchSelector');
    const rows = document.querySelectorAll('.stock-row');
    const physicalInputs = document.querySelectorAll('.physical-input');

    // 1. Filter rows by branch
    function filterByBranch() {
        const selectedBranchId = branchSelector.value;
        let visibleCount = 0;
        
        rows.forEach(row => {
            if (row.dataset.branchId === selectedBranchId) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
                // Reset value of hidden inputs
                const input = row.querySelector('.physical-input');
                if (input) {
                    input.value = '';
                    const stockId = input.dataset.id;
                    const varianceEl = document.getElementById(`variance-${stockId}`);
                    if (varianceEl) {
                        varianceEl.textContent = '0';
                        varianceEl.style.color = 'var(--text-primary)';
                    }
                }
                const reasonInput = row.querySelector('input[type="text"]');
                if (reasonInput) {
                    reasonInput.value = '';
                }
            }
        });
    }

    if (branchSelector) {
        branchSelector.addEventListener('change', filterByBranch);
        filterByBranch(); // Initial filter on load
    }

    // 2. Real-time variance calculations
    physicalInputs.forEach(input => {
        input.addEventListener('input', () => {
            const stockId = input.dataset.id;
            const recorded = parseInt(input.dataset.recorded);
            const physical = parseInt(input.value);
            const varianceEl = document.getElementById(`variance-${stockId}`);

            if (isNaN(physical)) {
                varianceEl.textContent = '0';
                varianceEl.style.color = 'var(--text-primary)';
                return;
            }

            const variance = physical - recorded;
            varianceEl.textContent = (variance > 0 ? '+' : '') + variance;

            if (variance > 0) {
                varianceEl.style.color = '#10b981'; // Green for surplus
            } else if (variance < 0) {
                varianceEl.style.color = '#ef4444'; // Red for deficit
            } else {
                varianceEl.style.color = 'var(--text-primary)';
            }
        });
    });
});
</script>
</body>
</html>
