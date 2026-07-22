<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Runchise — Diskon & Promo</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #FAF6F3;
            --bg-card: #FFFFFF;
            --text-primary: #2C1E1A;
            --text-muted: #8A756E;
            --border: rgba(226,167,148,0.25);
            --primary: #E2A794;
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg-dark); color: var(--text-primary); min-height: 100vh; padding: 2rem; }
        .card-nexapos { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(226, 167, 148, 0.06); padding: 2rem; }
        .btn-primary-nexapos { background: linear-gradient(135deg, #E2A794, #d97757); border: none; color: white; padding: 0.5rem 1.25rem; border-radius: 10px; font-weight: 600; text-decoration: none; transition: all 0.2s; }
        .btn-primary-nexapos:hover { transform: translateY(-1px); box-shadow: 0 5px 15px rgba(226,167,148,0.3); color: white; }
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
            <h2 style="font-size: 1.25rem; font-weight: 700; margin: 0; color: var(--text-primary);">🎉 Diskon & Promo</h2>
            <span class="text-muted" style="font-size:0.85rem; font-weight: 500;">Manajemen Kampanye Promosi</span>
        </div>

        <div class="card-nexapos text-center py-5">
            <i class="bi bi-tags-fill d-block mb-3" style="font-size: 3rem; color: var(--primary); opacity: 0.5;"></i>
            <h4 style="font-weight: 700;">Diskon & Promo</h4>
            <p style="color: var(--text-muted); max-width: 400px; margin: 0 auto 1.5rem auto;">
                Fitur ini sedang dalam tahap pengembangan. Nantinya Anda dapat membuat diskon persentase, potongan harga langsung, atau kampanye promosi berbatas waktu di sini.
            </p>
            <button class="btn btn-primary-nexapos" disabled>
                <i class="bi bi-plus-lg me-1"></i> Buat Promo Baru
            </button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
