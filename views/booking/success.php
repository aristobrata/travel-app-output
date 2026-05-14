<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pembayaran Berhasil — TravelKu</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Plus Jakarta Sans',sans-serif;background:#F8FAFC;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}
.card{background:#fff;border-radius:20px;padding:48px 36px;text-align:center;max-width:440px;width:100%;box-shadow:0 12px 40px rgba(0,0,0,.10)}
.icon{font-size:4rem;margin-bottom:16px;animation:pop .5s ease}
@keyframes pop{0%{transform:scale(0)}80%{transform:scale(1.1)}100%{transform:scale(1)}}
h1{font-size:1.5rem;font-weight:700;color:#065f46;margin-bottom:8px}
p{color:#64748B;font-size:.9rem;line-height:1.7;margin-bottom:24px}
.code{font-family:monospace;background:#FEF3C7;color:#92400e;padding:6px 16px;border-radius:8px;font-size:1rem;font-weight:700;display:inline-block;margin-bottom:24px}
.btn{padding:11px 28px;border-radius:10px;font-size:.95rem;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:7px;transition:all .2s;border:none;cursor:pointer;font-family:inherit}
.btn-primary{background:#F59E0B;color:#0F1B2D}
.btn-primary:hover{background:#D97706;color:#fff}
.btn-outline{background:transparent;color:#475569;border:1.5px solid #CBD5E1;margin-left:8px}
.btn-outline:hover{background:#F1F5F9}
</style>
</head>
<body>
<?php $base = defined('SUBFOLDER') ? SUBFOLDER : '';
$orderId = $_GET['order_id'] ?? '';
?>
<div class="card">
  <div class="icon">✅</div>
  <h1>Pembayaran Berhasil!</h1>
  <p>Terima kasih! Pembayaran Anda telah dikonfirmasi.<br>E-tiket sudah tersedia di akun Anda.</p>
  <?php if ($orderId): ?>
    <div class="code"><?= htmlspecialchars($orderId) ?></div><br>
  <?php endif; ?>
  <a href="<?= $base ?>/my-bookings" class="btn btn-primary">📋 Lihat Tiket Saya</a>
  <a href="<?= $base ?>/" class="btn btn-outline">🏠 Beranda</a>
</div>
</body>
</html>
