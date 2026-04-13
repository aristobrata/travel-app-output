<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — TravelKu</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Plus Jakarta Sans',sans-serif;min-height:100vh;background:linear-gradient(135deg,#0F1B2D 0%,#1A2D45 50%,#243C5C 100%);display:flex;align-items:center;justify-content:center;padding:20px}
.card{background:#fff;border-radius:20px;padding:40px;width:100%;max-width:420px;box-shadow:0 24px 64px rgba(0,0,0,.3)}
.brand{text-align:center;margin-bottom:30px}
.brand-icon{width:52px;height:52px;background:#F59E0B;border-radius:12px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:1.4rem}
.brand-name{font-family:'DM Serif Display',serif;font-size:1.7rem;color:#0F1B2D}
.brand-sub{font-size:.8rem;color:#94A3B8;margin-top:2px}
h2{font-size:1.2rem;font-weight:700;color:#1E293B;margin-bottom:4px}
p.sub{font-size:.85rem;color:#64748B;margin-bottom:24px}
.form-group{margin-bottom:16px}
label{display:block;font-size:.82rem;font-weight:600;color:#475569;margin-bottom:5px}
input{display:block;width:100%;padding:10px 13px;border:1.5px solid #CBD5E1;border-radius:8px;font-size:.9rem;font-family:inherit;transition:border-color .2s,box-shadow .2s;color:#1E293B}
input:focus{outline:none;border-color:#F59E0B;box-shadow:0 0 0 3px rgba(245,158,11,.15)}
.btn{display:block;width:100%;padding:11px;background:#F59E0B;color:#0F1B2D;border:none;border-radius:8px;font-size:.95rem;font-weight:700;cursor:pointer;font-family:inherit;transition:all .2s;margin-top:4px}
.btn:hover{background:#D97706;color:#fff}
.alert{padding:11px 14px;border-radius:8px;margin-bottom:16px;font-size:.85rem;display:flex;align-items:flex-start;gap:8px}
.alert-error{background:#FEE2E2;color:#991b1b;border:1px solid #fca5a5}
.alert-success{background:#D1FAE5;color:#065f46;border:1px solid #6ee7b7}
.input-error{border-color:#EF4444!important}
.err-msg{font-size:.75rem;color:#EF4444;margin-top:3px}
.footer-link{text-align:center;margin-top:20px;font-size:.83rem;color:#64748B}
.footer-link a{color:#F59E0B;font-weight:600;text-decoration:none}
.footer-link a:hover{text-decoration:underline}
.divider{border:none;border-top:1px solid #E2E8F0;margin:20px 0}
</style>
</head>
<body>
<div class="card">
  <div class="brand">
    <div class="brand-icon">🚌</div>
    <div class="brand-name">Falles Travel</div>
    <div class="brand-sub">Tiket Bus & Travel Online</div>
  </div>

  <h2>Masuk ke Akun</h2>
  <p class="sub">Selamat datang kembali! Silakan login untuk melanjutkan.</p>

  <?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-error">⚠️ <?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>
  <?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success">✅ <?= htmlspecialchars($_SESSION['success']) ?></div>
    <?php unset($_SESSION['success']); ?>
  <?php endif; ?>

  <?php
    $errors = $_SESSION['errors'] ?? [];
    $old = $_SESSION['old'] ?? [];
    unset($_SESSION['errors'], $_SESSION['old']);
  ?>

  <form method="POST" action="<?= (defined('SUBFOLDER') ? SUBFOLDER : '') . '/login' ?>">
    <?php if (!empty($_SESSION['csrf_token'])): ?>
      <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    <?php endif; ?>
    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" id="email" name="email"
        placeholder="nama@email.com"
        class="<?= isset($errors['email']) ? 'input-error' : '' ?>"
        value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
      <?php if (!empty($errors['email'])): ?><div class="err-msg"><?= $errors['email'] ?></div><?php endif; ?>
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" id="password" name="password"
        placeholder="Masukkan password"
        class="<?= isset($errors['password']) ? 'input-error' : '' ?>" required>
      <?php if (!empty($errors['password'])): ?><div class="err-msg"><?= $errors['password'] ?></div><?php endif; ?>
    </div>
    <button type="submit" class="btn">Masuk →</button>
  </form>

  <hr class="divider">
  <div class="footer-link">
    Belum punya akun? <a href="<?= (defined('SUBFOLDER') ? SUBFOLDER : '') . '/register' ?>">Daftar sekarang</a>
  </div>
  <div class="footer-link" style="margin-top:8px">
    <a href="<?= (defined('SUBFOLDER') ? SUBFOLDER : '') . '/' ?>">← Kembali ke Beranda</a>
  </div>
</div>
</body>
</html>
