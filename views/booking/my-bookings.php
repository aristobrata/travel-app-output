<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pesanan Saya — TravelKu</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Plus Jakarta Sans',sans-serif;background:#F8FAFC;color:#1E293B}
nav{background:#0F1B2D;padding:0 24px;height:56px;display:flex;align-items:center;justify-content:space-between}
.page-wrap{max-width:820px;margin:0 auto;padding:28px 20px}
.booking-card{background:#fff;border-radius:14px;border:1.5px solid #E2E8F0;padding:20px;margin-bottom:14px;display:flex;gap:18px;align-items:flex-start;flex-wrap:wrap;transition:all .2s}
.booking-card:hover{border-color:#F59E0B;box-shadow:0 4px 18px rgba(245,158,11,.1)}
.booking-icon{width:46px;height:46px;border-radius:12px;background:#0F1B2D;color:#F59E0B;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0}
.booking-info{flex:1;min-width:200px}
.booking-code{font-family:monospace;font-size:.78rem;font-weight:700;color:#64748B;background:#F1F5F9;padding:2px 8px;border-radius:4px}
.booking-route{font-size:1rem;font-weight:700;color:#0F1B2D;margin:5px 0 3px;display:flex;align-items:center;gap:6px}
.booking-date{font-size:.82rem;color:#64748B}
.booking-price{font-size:1.05rem;font-weight:700;color:#0F1B2D}
.booking-actions{display:flex;flex-direction:column;gap:7px;align-items:flex-end;min-width:120px}
.badge{display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:20px;font-size:.72rem;font-weight:600;white-space:nowrap}
.badge-pending{background:#FEF3C7;color:#92400e}
.badge-paid{background:#D1FAE5;color:#065f46}
.badge-cancelled{background:#FEE2E2;color:#991b1b}
.badge-completed{background:#DBEAFE;color:#1e40af}
.btn{padding:7px 14px;border-radius:7px;font-size:.8rem;font-weight:600;cursor:pointer;font-family:inherit;text-decoration:none;display:inline-flex;align-items:center;gap:5px;border:none;transition:all .2s}
.btn-primary{background:#F59E0B;color:#0F1B2D}
.btn-primary:hover{background:#D97706;color:#fff}
.btn-outline{background:transparent;color:#475569;border:1.5px solid #CBD5E1}
.btn-outline:hover{background:#F1F5F9}
.empty-state{text-align:center;padding:64px 20px;background:#fff;border-radius:16px;border:1.5px dashed #E2E8F0}
.empty-icon{font-size:3rem;color:#CBD5E1;margin-bottom:12px}
</style>
</head>
<body>
<?php $base = defined('SUBFOLDER') ? SUBFOLDER : ''; ?>
<nav>
  <a href="<?= $base ?>/" style="font-family:'DM Serif Display',serif;font-size:1.2rem;color:#fff;text-decoration:none">🚌 Travel<span style="color:#F59E0B">Ku</span></a>
  <div style="display:flex;align-items:center;gap:10px">
    <span style="color:rgba(255,255,255,.65);font-size:.82rem">👤 <?= htmlspecialchars($_SESSION['user_name'] ?? '') ?></span>
    <a href="<?= $base ?>/logout" style="background:#F59E0B;color:#0F1B2D;padding:6px 14px;border-radius:6px;font-size:.82rem;font-weight:700;text-decoration:none"
      onclick="return confirm('Yakin keluar?')">Keluar</a>
  </div>
</nav>

<div class="page-wrap">
  <?php if (!empty($_SESSION['success'])): ?>
    <div style="background:#D1FAE5;color:#065f46;border:1px solid #6EE7B7;border-radius:8px;padding:10px 14px;margin-bottom:16px;font-size:.875rem">
      ✅ <?= htmlspecialchars($_SESSION['success']) ?>
    </div>
    <?php unset($_SESSION['success']); ?>
  <?php endif; ?>

  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:10px">
    <div>
      <h1 style="font-size:1.3rem;font-weight:700;color:#0F1B2D">📋 Pesanan Saya</h1>
      <p style="font-size:.85rem;color:#64748B;margin-top:3px">Riwayat pemesanan tiket Anda</p>
    </div>
    <a href="<?= $base ?>/" class="btn btn-outline">
      <i class="fa-solid fa-plus"></i> Pesan Lagi
    </a>
  </div>

  <?php if (empty($bookings)): ?>
    <div class="empty-state">
      <div class="empty-icon"><i class="fa-solid fa-ticket"></i></div>
      <h2 style="font-size:1.1rem;font-weight:700;color:#475569;margin-bottom:8px">Belum Ada Pesanan</h2>
      <p style="font-size:.875rem;color:#94A3B8;margin-bottom:20px">Anda belum pernah melakukan pemesanan tiket.</p>
      <a href="<?= $base ?>/" class="btn btn-primary">🔍 Cari Jadwal</a>
    </div>
  <?php else: ?>
    <?php foreach ($bookings as $b): ?>
    <div class="booking-card">
      <div class="booking-icon">
        <?= match($b['vehicle_type'] ?? '') {
          'bus' => '🚌',
          'minibus' => '🚐',
          'travel' => '🚗',
          default => '🚌'
        } ?>
      </div>
      <div class="booking-info">
        <span class="booking-code"><?= htmlspecialchars($b['booking_code']) ?></span>
        <div class="booking-route">
          <?= htmlspecialchars($b['origin']) ?>
          <i class="fa-solid fa-arrow-right" style="color:#F59E0B;font-size:.7rem"></i>
          <?= htmlspecialchars($b['destination']) ?>
        </div>
        <div class="booking-date">
          <i class="fa-regular fa-calendar" style="color:#F59E0B"></i>
          <?= date('d F Y, H:i', strtotime($b['depart_at'])) ?> WIB
        </div>
        <div style="margin-top:6px;font-size:.82rem;color:#64748B">
          <?= $b['passenger_count'] ?> penumpang ·
          <strong style="color:#0F1B2D">Rp <?= number_format($b['total_price'], 0, ',', '.') ?></strong>
        </div>
      </div>
      <div class="booking-actions">
        <?php
          $badge = match($b['status']) {
            'pending'   => ['class' => 'badge-pending',   'label' => '⏳ Pending'],
            'paid'      => ['class' => 'badge-paid',      'label' => '✅ Terbayar'],
            'cancelled' => ['class' => 'badge-cancelled', 'label' => '❌ Dibatalkan'],
            'completed' => ['class' => 'badge-completed', 'label' => '✔ Selesai'],
            default     => ['class' => 'badge-pending',   'label' => ucfirst($b['status'])]
          };
        ?>
        <span class="badge <?= $badge['class'] ?>"><?= $badge['label'] ?></span>
        <a href="<?= $base ?>/booking/<?= $b['id'] ?>" class="btn btn-outline">
          <i class="fa-solid fa-eye"></i> Detail
        </a>
        <?php if ($b['status'] === 'pending'): ?>
        <a href="<?= $base ?>/booking/<?= $b['id'] ?>/payment" class="btn btn-primary">
          <i class="fa-solid fa-credit-card"></i> Bayar
        </a>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<footer style="background:#0F1B2D;color:rgba(255,255,255,.5);text-align:center;padding:20px;font-size:.8rem">
  © <?= date('Y') ?> Falles Travel — Selamat Perjalanan!
</footer>
</body>
</html>
