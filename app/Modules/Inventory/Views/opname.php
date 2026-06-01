<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Runchise — Stock Opname</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary: #E2A794;
            --bg-dark: #140f0e;
            --bg-card: #221a18;
            --text-primary: #f5eae6;
            --text-muted: #bdafa9;
            --border: rgba(226,167,148,0.15);
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg-dark); color: var(--text-primary); min-height: 100vh; }
        .card-nexapos { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 2rem; max-width: 640px; }
        .btn-outline-primary { border-color: var(--primary); color: var(--primary); font-weight: 600; border-radius: 10px; }
        .btn-outline-primary:hover { background-color: var(--primary); color: white; border-color: var(--primary); }
    </style>
</head>
<body>

<div class="d-flex" style="min-height: 100vh;">
    <!-- Shared Premium Sidebar -->
    <?= view('partials/sidebar') ?>

    <!-- Main Content Area -->
    <div class="flex-grow-1" style="overflow-x: hidden; padding: 2rem;">
        <!-- Header Navigation Title -->
        <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom" style="border-color:var(--border) !important; max-width:640px;">
            <h2 style="font-size: 1.25rem; font-weight: 700; margin: 0; color: var(--text-primary);">📋 Stock Opname</h2>
            <span class="text-muted" style="font-size:0.85rem; font-weight: 500;">Inventory Opname</span>
        </div>

        <div class="card-nexapos">
            <h4>📋 Stock Opname</h4>
            <p style="color:var(--text-muted);">Physical stock reconciliation and variance reporting. <em>Full UI coming in Sprint 10.</em></p>
            <a href="/inventory/stock" class="btn btn-sm btn-outline-primary mt-2">← Back to Stock</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
