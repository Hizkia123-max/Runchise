<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Runchise — Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #E2A794;
            --primary-dark: #c98570;
            --secondary: #4f46e5;
            --bg-dark: #FAF6F3;
            --bg-card: #FFFFFF;
            --text-primary: #2C1E1A;
            --text-muted: #8A756E;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image:
                radial-gradient(ellipse at 20% 50%, rgba(226, 167, 148, 0.12) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 10%, rgba(226, 167, 148, 0.08) 0%, transparent 60%);
        }
        .login-wrapper {
            width: 100%;
            max-width: 440px;
            padding: 2rem;
        }
        .brand {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        .brand-logo {
            width: 52px; height: 52px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 14px;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 1.5rem; margin-bottom: 1rem;
        }
        .brand h1 {
            font-size: 1.6rem; font-weight: 700;
            color: var(--text-primary); margin: 0;
        }
        .brand p { color: var(--text-muted); font-size: 0.9rem; margin-top: 0.3rem; }
        .login-card {
            background: #FFFFFF;
            border: 1px solid rgba(226, 167, 148, 0.25);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 10px 40px rgba(226, 167, 148, 0.08);
        }
        .form-label { color: var(--text-muted); font-size: 0.85rem; font-weight: 500; margin-bottom: 0.5rem; }
        .form-control {
            background: #ffffff;
            border: 1px solid rgba(226, 167, 148, 0.25);
            color: var(--text-primary);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.2s;
        }
        .form-control:focus {
            background: #ffffff;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(226, 167, 148, 0.25);
            color: var(--text-primary);
        }
        .form-control::placeholder { color: rgba(148, 163, 184, 0.5); }
        .btn-login {
            width: 100%;
            padding: 0.85rem;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 1rem;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(226, 167, 148, 0.35);
        }
        .btn-login:active { transform: translateY(0); }
        .alert-error {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 10px;
            color: #fca5a5;
            padding: 0.75rem 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }
        .footer-text {
            text-align: center;
            margin-top: 2rem;
            color: var(--text-muted);
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
<div class="login-wrapper">
    <div class="brand">
        <div class="brand-logo">⚡</div>
        <h1>Runchise</h1>
        <p>Cloud Business Management Platform</p>
    </div>

    <div class="login-card">
        <h4 style="color: var(--text-primary); font-weight: 600; margin-bottom: 1.5rem; text-align: center;">Sign in to Runchise</h4>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert-error">⚠ <?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <form action="/auth/login" method="POST">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input id="email" type="email" name="email" class="form-control"
                       placeholder="your@email.com" required autocomplete="email">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input id="password" type="password" name="password" class="form-control"
                       placeholder="••••••••" required autocomplete="current-password">
            </div>
            <button id="btn-login" type="submit" class="btn-login">Sign In →</button>
        </form>
    </div>

    <div class="footer-text">
        © 2026 Runchise · Powered by CodeIgniter 4
    </div>
</div>
</body>
</html>
