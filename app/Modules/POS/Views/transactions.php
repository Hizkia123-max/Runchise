<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 text-dark fw-bold"><i class="bi bi-receipt me-2 text-primary"></i>Riwayat Transaksi Kasir</h4>
        <a href="<?= base_url('pos/terminal') ?>" class="btn btn-primary shadow-sm rounded-3 fw-bold">
            <i class="bi bi-display"></i> Kembali ke POS
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 pt-4 pb-2 px-4 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0 text-secondary">Semua Transaksi Hari Ini</h6>
            <div class="d-flex gap-2">
                <input type="text" id="searchInput" class="form-control form-control-sm border-0 bg-light" placeholder="🔍 Cari Invoice / Kasir..." style="border-radius: 8px;">
            </div>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
            <div class="table-responsive p-0">
                <table class="table align-items-center mb-0 table-hover" id="dataTable">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-4">Invoice</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Waktu</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Kasir</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Metode</th>
                            <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 pe-4">Total (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($transactions)): ?>
                            <?php foreach($transactions as $trx): ?>
                            <tr class="searchable-row">
                                <td class="px-4">
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="mb-0 text-sm fw-bold"><?= esc($trx['invoice_number'] ?? 'N/A') ?></h6>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-xs text-muted fw-bold"><?= date('d M Y, H:i', strtotime($trx['created_at'])) ?></span>
                                </td>
                                <td>
                                    <span class="text-xs fw-bold text-dark"><?= esc($trx['cashier_name'] ?? 'Admin') ?></span>
                                </td>
                                <td>
                                    <?php 
                                        $method = $trx['payment_method'] ?? 'Cash'; 
                                        $badge = 'bg-success';
                                        if($method == 'QRIS') $badge = 'bg-info';
                                        elseif($method == 'Card') $badge = 'bg-primary';
                                    ?>
                                    <span class="badge <?= $badge ?> badge-sm rounded-pill"><?= esc($method) ?></span>
                                </td>
                                <td class="text-end pe-4">
                                    <span class="text-sm fw-bold text-dark">Rp <?= number_format($trx['total'] ?? 0, 0, ',', '.') ?></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Belum ada transaksi tercatat.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
    let value = this.value.toLowerCase();
    let rows = document.querySelectorAll('#dataTable tbody tr.searchable-row');
    
    rows.forEach(row => {
        if (row.textContent.toLowerCase().includes(value)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>
<?= $this->endSection() ?>
