<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daftar — TravelKu</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Plus Jakarta Sans',sans-serif;min-height:100vh;background:linear-gradient(135deg,#0F1B2D 0%,#1A2D45 50%,#243C5C 100%);display:flex;align-items:center;justify-content:center;padding:20px}
.card{background:#fff;border-radius:20px;padding:40px;width:100%;max-width:460px;box-shadow:0 24px 64px rgba(0,0,0,.3)}
.brand{text-align:center;margin-bottom:24px}
.brand-icon{width:48px;height:48px;background:#F59E0B;border-radius:12px;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;font-size:1.3rem}
.brand-name{font-family:'DM Serif Display',serif;font-size:1.5rem;color:#0F1B2D}
h2{font-size:1.15rem;font-weight:700;color:#1E293B;margin-bottom:4px}
p.sub{font-size:.83rem;color:#64748B;margin-bottom:22px}
.form-group{margin-bottom:14px}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
label{display:block;font-size:.8rem;font-weight:600;color:#475569;margin-bottom:4px}
input{display:block;width:100%;padding:9px 12px;border:1.5px solid #CBD5E1;border-radius:8px;font-size:.875rem;font-family:inherit;transition:border-color .2s}
input:focus{outline:none;border-color:#F59E0B;box-shadow:0 0 0 3px rgba(245,158,11,.12)}
.btn{display:block;width:100%;padding:11px;background:#F59E0B;color:#0F1B2D;border:none;border-radius:8px;font-size:.92rem;font-weight:700;cursor:pointer;font-family:inherit;transition:all .2s;margin-top:4px}
.btn:hover{background:#D97706;color:#fff}
.alert{padding:10px 13px;border-radius:8px;margin-bottom:14px;font-size:.83rem}
.alert-error{background:#FEE2E2;color:#991b1b;border:1px solid #fca5a5}
.input-error{border-color:#EF4444!important}
.err-msg{font-size:.72rem;color:#EF4444;margin-top:3px}
.footer-link{text-align:center;margin-top:16px;font-size:.83rem;color:#64748B}
.footer-link a{color:#F59E0B;font-weight:600;text-decoration:none}
.footer-link a:hover{text-decoration:underline}
.divider{border:none;border-top:1px solid #E2E8F0;margin:16px 0}
@media(max-width:480px){.form-row{grid-template-columns:1fr}}
</style>
</head>
<body>
<div class="card">
  <div class="brand">
    <div class="brand-icon">🚌</div>
    <div class="brand-name">Falles Travel</div>
  </div>

  <h2>Buat Akun Baru</h2>
  <p class="sub">Daftar gratis dan mulai pesan tiket perjalanan Anda.</p>

  <?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-error">⚠️ <?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>

  <?php
    $errors = $_SESSION['errors'] ?? [];
    $old = $_SESSION['old'] ?? [];
    unset($_SESSION['errors'], $_SESSION['old']);
  ?>

  <form method="POST" action="<?= (defined('SUBFOLDER') ? SUBFOLDER : '') . '/register' ?>">
    <?php if (!empty($_SESSION['csrf_token'])): ?>
      <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    <?php endif; ?>

    <div class="form-group">
      <label>Nama Lengkap <span style="color:#EF4444">*</span></label>
      <input type="text" name="name" placeholder="Nama Anda"
        class="<?= isset($errors['name']) ? 'input-error' : '' ?>"
        value="<?= htmlspecialchars($old['name'] ?? '') ?>" required>
      <?php if (!empty($errors['name'])): ?><div class="err-msg"><?= $errors['name'] ?></div><?php endif; ?>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label>Email <span style="color:#EF4444">*</span></label>
        <input type="email" name="email" placeholder="nama@email.com"
          class="<?= isset($errors['email']) ? 'input-error' : '' ?>"
          value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
        <?php if (!empty($errors['email'])): ?><div class="err-msg"><?= $errors['email'] ?></div><?php endif; ?>
      </div>
      <div class="form-group">
        <label>No. Telepon <span style="color:#EF4444">*</span></label>
        <input type="tel" name="phone" placeholder="08xxxxxxxxxx"
          class="<?= isset($errors['phone']) ? 'input-error' : '' ?>"
          value="<?= htmlspecialchars($old['phone'] ?? '') ?>" required>
        <?php if (!empty($errors['phone'])): ?><div class="err-msg"><?= $errors['phone'] ?></div><?php endif; ?>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label>Password <span style="color:#EF4444">*</span></label>
        <input type="password" name="password" placeholder="Min. 8 karakter"
          class="<?= isset($errors['password']) ? 'input-error' : '' ?>" required>
        <?php if (!empty($errors['password'])): ?><div class="err-msg"><?= $errors['password'] ?></div><?php endif; ?>
      </div>
      <div class="form-group">
        <label>Konfirmasi Password <span style="color:#EF4444">*</span></label>
        <input type="password" name="password_confirmation" placeholder="Ulangi password"
          class="<?= isset($errors['password_confirmation']) ? 'input-error' : '' ?>" required>
        <?php if (!empty($errors['password_confirmation'])): ?><div class="err-msg"><?= $errors['password_confirmation'] ?></div><?php endif; ?>
      </div>
    </div>

    <button type="submit" class="btn">Daftar Sekarang →</button>
  </form>

  <hr class="divider">
  <div class="footer-link">
    Sudah punya akun? <a href="<?= (defined('SUBFOLDER') ? SUBFOLDER : '') . '/login' ?>">Masuk di sini</a>
  </div>
  <div class="footer-link" style="margin-top:8px">
    <a href="<?= (defined('SUBFOLDER') ? SUBFOLDER : '') . '/' ?>">← Kembali ke Beranda</a>
  </div>
</div>
</body>
</html>
