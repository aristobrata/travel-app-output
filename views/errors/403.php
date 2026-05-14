<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>403 — Akses Ditolak | TravelKu</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Plus Jakarta Sans',sans-serif;background:#F8FAFC;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}
.wrap{text-align:center;max-width:480px}
.code{font-family:'DM Serif Display',serif;font-size:8rem;color:#0F1B2D;line-height:1;margin-bottom:8px}
.code span{color:#EF4444}
h1{font-size:1.5rem;font-weight:700;color:#1E293B;margin-bottom:10px}
p{color:#64748B;font-size:.95rem;line-height:1.7;margin-bottom:28px}
.actions{display:flex;gap:10px;justify-content:center;flex-wrap:wrap}
a.btn{padding:10px 22px;border-radius:8px;font-weight:600;font-size:.9rem;text-decoration:none;transition:all .2s}
a.btn-primary{background:#F59E0B;color:#0F1B2D}
a.btn-primary:hover{background:#D97706;color:#fff}
a.btn-outline{background:transparent;color:#475569;border:1.5px solid #CBD5E1}
a.btn-outline:hover{background:#E2E8F0}
</style>
</head>
<body>
<div class="wrap">
  <div class="code">4<span>0</span>3</div>
  <h1>Akses Ditolak</h1>
  <p>Anda tidak memiliki izin untuk mengakses halaman ini. Silakan login dengan akun yang sesuai.</p>
  <div class="actions">
    <a href="<?= (defined('SUBFOLDER') ? SUBFOLDER : '') . '/login' ?>" class="btn btn-primary">🔐 Login</a>
    <a href="<?= (defined('SUBFOLDER') ? SUBFOLDER : '') . '/' ?>" class="btn btn-outline">🏠 Beranda</a>
  </div>
</div>
</body>
</html>
