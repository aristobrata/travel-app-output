<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pembayaran — TravelKu</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<?php
$isSandbox = !($cfg['midtrans']['is_production'] ?? false);
$snapUrl   = $isSandbox ? $cfg['midtrans']['snap_url_sb'] : $cfg['midtrans']['snap_url_prod'];
$clientKey = $cfg['midtrans']['client_key'] ?? '';
$base      = defined('SUBFOLDER') ? SUBFOLDER : '';
?>
<?php if ($paymentToken && $clientKey): ?>
<script src="<?= $snapUrl ?>" data-client-key="<?= htmlspecialchars($clientKey) ?>"></script>
<?php endif; ?>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Plus Jakarta Sans',sans-serif;background:#F8FAFC;color:#1E293B}
nav{background:#0F1B2D;padding:0 24px;height:56px;display:flex;align-items:center;justify-content:space-between}
.page-wrap{max-width:700px;margin:0 auto;padding:28px 20px}
.card{background:#fff;border-radius:16px;border:1px solid #E2E8F0;padding:24px;margin-bottom:16px}
.booking-code{font-family:monospace;font-size:1.4rem;font-weight:800;color:#0F1B2D;background:#FEF3C7;padding:6px 16px;border-radius:8px;letter-spacing:.06em}
.info-row{display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #F1F5F9;font-size:.875rem}
.info-row:last-child{border:none}
.info-label{color:#64748B}
.info-value{font-weight:600;color:#1E293B}
.total-price{font-size:1.5rem;font-weight:800;color:#0F1B2D}
.btn{width:100%;padding:13px;border:none;border-radius:10px;font-size:1rem;font-weight:700;cursor:pointer;font-family:inherit;transition:all .2s;display:flex;align-items:center;justify-content:center;gap:8px;text-decoration:none}
.btn-pay{background:#F59E0B;color:#0F1B2D}
.btn-pay:hover{background:#D97706;color:#fff}
.btn-outline{background:transparent;color:#475569;border:1.5px solid #CBD5E1}
.btn-outline:hover{background:#F1F5F9}
.status-pending{color:#92400e;background:#FEF3C7;padding:3px 10px;border-radius:6px;font-size:.8rem;font-weight:600}
.status-paid{color:#065f46;background:#D1FAE5;padding:3px 10px;border-radius:6px;font-size:.8rem;font-weight:600}
.timer{background:#FFF3CD;border:1px solid #FFC107;border-radius:8px;padding:12px 16px;font-size:.85rem;color:#664d03;display:flex;align-items:center;gap:8px;margin-bottom:14px}
.paid-banner{background:#D1FAE5;border:1.5px solid #6EE7B7;border-radius:12px;padding:20px;text-align:center;margin-bottom:16px}
</style>
</head>
<body>
<nav>
  <a href="<?= $base ?>/" style="font-family:'DM Serif Display',serif;font-size:1.2rem;color:#fff;text-decoration:none">🚌 Falles<span style="color:#F59E0B">Travel</span></a>
  <a href="<?= $base ?>/my-bookings" style="color:rgba(255,255,255,.7);font-size:.82rem;text-decoration:none">📋 Pesanan Saya</a>
</nav>

<div class="page-wrap">
  <h1 style="font-size:1.3rem;font-weight:700;color:#0F1B2D;margin-bottom:20px">
    <i class="fa-solid fa-credit-card" style="color:#F59E0B"></i> Pembayaran
  </h1>

  <?php if ($booking['status'] === 'paid'): ?>
    <div class="paid-banner">
      <div style="font-size:2rem;margin-bottom:8px">✅</div>
      <div style="font-weight:700;font-size:1.1rem;color:#065f46;margin-bottom:6px">Pembayaran Berhasil!</div>
      <div style="font-size:.875rem;color:#047857">Tiket Anda telah dikonfirmasi.</div>
      <a href="<?= $base ?>/my-bookings" style="display:inline-block;margin-top:14px;padding:9px 22px;background:#10B981;color:#fff;border-radius:8px;font-weight:700;text-decoration:none">
        Lihat Tiket Saya
      </a>
    </div>
  <?php else: ?>
    <?php if ($payment && $payment['expired_at']): ?>
    <div class="timer">
      <i class="fa-solid fa-clock"></i>
      Selesaikan pembayaran sebelum:
      <strong><?= date('d/m/Y H:i', strtotime($payment['expired_at'])) ?> WIB</strong>
    </div>
    <?php endif; ?>
  <?php endif; ?>

  <!-- Booking Info -->
  <div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;flex-wrap:wrap;gap:8px">
      <div>
        <div style="font-size:.75rem;color:#94A3B8;margin-bottom:4px">Kode Booking</div>
        <div class="booking-code"><?= htmlspecialchars($booking['booking_code']) ?></div>
      </div>
      <?php if ($booking['status'] === 'paid'): ?>
        <span class="status-paid">✅ TERBAYAR</span>
      <?php else: ?>
        <span class="status-pending">⏳ PENDING</span>
      <?php endif; ?>
    </div>

    <div class="info-row">
      <span class="info-label">Rute</span>
      <span class="info-value"><?= htmlspecialchars($booking['origin']) ?> → <?= htmlspecialchars($booking['destination']) ?></span>
    </div>
    <div class="info-row">
      <span class="info-label">Kendaraan</span>
      <span class="info-value"><?= htmlspecialchars($booking['vehicle_name']) ?></span>
    </div>
    <div class="info-row">
      <span class="info-label">Keberangkatan</span>
      <span class="info-value"><?= date('d F Y, H:i', strtotime($booking['depart_at'])) ?> WIB</span>
    </div>
    <div class="info-row">
      <span class="info-label">Penumpang</span>
      <span class="info-value"><?= $booking['passenger_count'] ?> orang</span>
    </div>
    <div class="info-row">
      <span class="info-label">Kursi</span>
      <span class="info-value">
        <?php foreach ($seats as $s): ?>
          <span style="background:#FEF3C7;color:#92400e;padding:2px 7px;border-radius:4px;font-size:.78rem;font-weight:700;margin-left:3px"><?= $s['seat_number'] ?></span>
        <?php endforeach; ?>
      </span>
    </div>
    <div class="info-row" style="border-top:2px solid #E2E8F0;padding-top:12px;margin-top:4px">
      <span class="info-label" style="font-weight:700">Total Pembayaran</span>
      <span class="total-price">Rp <?= number_format($booking['total_price'], 0, ',', '.') ?></span>
    </div>
  </div>

  <!-- Payment Action -->
  <?php if ($booking['status'] !== 'paid' && $booking['status'] !== 'cancelled'): ?>
  <div class="card">
    <h3 style="font-size:.95rem;font-weight:700;margin-bottom:14px;color:#0F1B2D">
      <i class="fa-solid fa-wallet" style="color:#F59E0B"></i> Pilih Metode Pembayaran
    </h3>
    <?php if ($paymentToken && $clientKey): ?>
      <button class="btn btn-pay" id="pay-btn" onclick="startPayment()">
        <i class="fa-solid fa-lock"></i> Bayar Sekarang — Rp <?= number_format($booking['total_price'], 0, ',', '.') ?>
      </button>
      <div style="font-size:.72rem;color:#94A3B8;text-align:center;margin-top:10px">
        <i class="fa-solid fa-shield-check" style="color:#10B981"></i>
        Pembayaran aman dengan enkripsi SSL · Powered by Midtrans
      </div>
    <?php else: ?>
      <div style="text-align:center;padding:20px;color:#94A3B8;font-size:.875rem">
        <i class="fa-solid fa-circle-exclamation" style="font-size:1.5rem;margin-bottom:8px;display:block;color:#F59E0B"></i>
        Gateway pembayaran belum dikonfigurasi.<br>
        <span style="font-size:.78rem">Hubungi admin untuk menyelesaikan pembayaran.</span>
      </div>
    <?php endif; ?>
  </div>
  <?php endif; ?>

  <a href="<?= $base ?>/my-bookings" class="btn btn-outline" style="margin-top:8px">
    <i class="fa-solid fa-list"></i> Lihat Semua Pesanan
  </a>
</div>

<script>
function startPayment() {
  <?php if ($paymentToken): ?>
  const btn = document.getElementById('pay-btn');
  btn.disabled = true;
  btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Memuat...';

  window.snap.pay('<?= $paymentToken ?>', {
    onSuccess: function(result) {
      window.location.href = '<?= $base ?>/payment/finish?order_id=<?= $booking['booking_code'] ?>';
    },
    onPending: function(result) {
      window.location.href = '<?= $base ?>/payment/unfinish?order_id=<?= $booking['booking_code'] ?>';
    },
    onError: function(result) {
      btn.disabled = false;
      btn.innerHTML = '<i class="fa-solid fa-lock"></i> Bayar Sekarang';
      alert('Pembayaran gagal. Silakan coba lagi.');
    },
    onClose: function() {
      btn.disabled = false;
      btn.innerHTML = '<i class="fa-solid fa-lock"></i> Bayar Sekarang — Rp <?= number_format($booking['total_price'], 0, ',', '.') ?>';
    }
  });
  <?php endif; ?>
}
</script>
</body>
</html>
