<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detail Pesanan <?= htmlspecialchars($booking['booking_code']) ?> — TravelKu</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Plus Jakarta Sans',sans-serif;background:#F8FAFC;color:#1E293B}
nav{background:#0F1B2D;padding:0 24px;height:56px;display:flex;align-items:center;justify-content:space-between}
.page-wrap{max-width:700px;margin:0 auto;padding:28px 20px}
.back-link{color:#64748B;font-size:.875rem;text-decoration:none;display:inline-flex;align-items:center;gap:5px;margin-bottom:20px}
/* E-ticket card */
.eticket{background:#fff;border-radius:20px;overflow:hidden;box-shadow:0 8px 32px rgba(0,0,0,.10);margin-bottom:20px}
.eticket-header{background:#0F1B2D;padding:24px 28px;color:#fff;display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px}
.eticket-brand{font-family:'DM Serif Display',serif;font-size:1.3rem}
.eticket-brand span{color:#F59E0B}
.eticket-code{text-align:right}
.eticket-code .label{font-size:.68rem;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.5);margin-bottom:3px}
.eticket-code .code{font-family:monospace;font-size:1.1rem;font-weight:800;color:#F59E0B;letter-spacing:.06em}
.eticket-route{padding:24px 28px;display:flex;align-items:center;gap:0;border-bottom:1px dashed #E2E8F0}
.city{flex:1}
.city .time{font-size:2rem;font-weight:800;color:#0F1B2D}
.city .name{font-size:.9rem;font-weight:600;color:#475569;margin-top:2px}
.city .date{font-size:.78rem;color:#94A3B8}
.route-mid{flex:0 0 auto;text-align:center;padding:0 20px}
.route-mid .arrow{color:#F59E0B;font-size:1.4rem}
.route-mid .dur{font-size:.68rem;color:#94A3B8;margin-top:2px;white-space:nowrap}
.eticket-details{padding:20px 28px;display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;border-bottom:1px dashed #E2E8F0}
.detail-item .label{font-size:.7rem;text-transform:uppercase;letter-spacing:.07em;color:#94A3B8;margin-bottom:3px}
.detail-item .value{font-weight:700;font-size:.9rem;color:#1E293B}
.eticket-seats{padding:20px 28px;border-bottom:1px dashed #E2E8F0}
.eticket-seats h3{font-size:.82rem;text-transform:uppercase;letter-spacing:.07em;color:#94A3B8;margin-bottom:12px}
.passenger-row{display:flex;align-items:center;gap:12px;padding:8px 0;border-bottom:1px solid #F1F5F9}
.passenger-row:last-child{border:none}
.seat-num{width:34px;height:34px;background:#0F1B2D;color:#F59E0B;border-radius:8px;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.85rem;flex-shrink:0}
.passenger-name{font-weight:600;font-size:.875rem}
.passenger-id{font-size:.75rem;color:#94A3B8;font-family:monospace}
.eticket-footer{padding:18px 28px;background:#F8FAFC;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px}
.total-label{font-size:.78rem;color:#64748B}
.total-amount{font-size:1.3rem;font-weight:800;color:#0F1B2D}
.badge{display:inline-flex;align-items:center;gap:4px;padding:5px 12px;border-radius:20px;font-size:.78rem;font-weight:700}
.badge-pending{background:#FEF3C7;color:#92400e}
.badge-paid{background:#D1FAE5;color:#065f46}
.badge-cancelled{background:#FEE2E2;color:#991b1b}
.badge-completed{background:#DBEAFE;color:#1e40af}
.actions{display:flex;gap:10px;flex-wrap:wrap}
.btn{padding:9px 18px;border-radius:8px;font-size:.875rem;font-weight:600;cursor:pointer;font-family:inherit;text-decoration:none;display:inline-flex;align-items:center;gap:6px;border:none;transition:all .2s}
.btn-primary{background:#F59E0B;color:#0F1B2D}
.btn-primary:hover{background:#D97706;color:#fff}
.btn-outline{background:transparent;color:#475569;border:1.5px solid #CBD5E1}
.btn-outline:hover{background:#F1F5F9}
.cutline{height:1px;background:repeating-linear-gradient(to right,#E2E8F0 0,#E2E8F0 8px,transparent 8px,transparent 16px)}
@media print{nav,.actions{display:none!important}.page-wrap{padding:0}}
</style>
</head>
<body>
<?php $base = defined('SUBFOLDER') ? SUBFOLDER : '';
$dur = '';
if (!empty($booking['duration_min'])) {
  $h = intdiv($booking['duration_min'], 60); $m = $booking['duration_min'] % 60;
  $dur = ($h > 0 ? "{$h}j " : '') . ($m > 0 ? "{$m}m" : '');
}
?>
<nav>
  <a href="<?= $base ?>/" style="font-family:'DM Serif Display',serif;font-size:1.2rem;color:#fff;text-decoration:none">🚌 Falles<span style="color:#F59E0B">Tarvel</span></a>
  <a href="<?= $base ?>/my-bookings" style="color:rgba(255,255,255,.7);font-size:.82rem;text-decoration:none">📋 Pesanan</a>
</nav>

<div class="page-wrap">
  <a href="<?= $base ?>/my-bookings" class="back-link"><i class="fa-solid fa-arrow-left"></i> Pesanan Saya</a>

  <div class="actions" style="margin-bottom:18px">
    <button onclick="window.print()" class="btn btn-outline">
      <i class="fa-solid fa-print"></i> Cetak Tiket
    </button>
    <?php if ($booking['status'] === 'pending'): ?>
    <a href="<?= $base ?>/booking/<?= $booking['id'] ?>/payment" class="btn btn-primary">
      <i class="fa-solid fa-credit-card"></i> Selesaikan Pembayaran
    </a>
    <?php endif; ?>
  </div>

  <!-- E-TICKET -->
  <div class="eticket">
    <div class="eticket-header">
      <div>
        <div class="eticket-brand">Travel<span>Ku</span></div>
        <div style="font-size:.75rem;color:rgba(255,255,255,.5);margin-top:3px">E-Tiket Resmi</div>
      </div>
      <div class="eticket-code">
        <div class="label">Kode Booking</div>
        <div class="code"><?= htmlspecialchars($booking['booking_code']) ?></div>
        <div style="margin-top:6px">
          <?php
            $badge = match($booking['status']) {
              'pending'   => ['class' => 'badge-pending',   'label' => '⏳ Menunggu Bayar'],
              'paid'      => ['class' => 'badge-paid',      'label' => '✅ LUNAS'],
              'cancelled' => ['class' => 'badge-cancelled', 'label' => '❌ Dibatalkan'],
              'completed' => ['class' => 'badge-completed', 'label' => '✔ Selesai'],
              default     => ['class' => 'badge-pending',   'label' => ucfirst($booking['status'])]
            };
          ?>
          <span class="badge <?= $badge['class'] ?>"><?= $badge['label'] ?></span>
        </div>
      </div>
    </div>

    <div class="eticket-route">
      <div class="city" style="text-align:left">
        <div class="time"><?= date('H:i', strtotime($booking['depart_at'])) ?></div>
        <div class="name"><?= htmlspecialchars($booking['origin']) ?></div>
        <div class="date"><?= date('d F Y', strtotime($booking['depart_at'])) ?></div>
      </div>
      <div class="route-mid">
        <div class="arrow"><i class="fa-solid fa-arrow-right"></i></div>
        <?php if ($dur): ?><div class="dur"><?= $dur ?></div><?php endif; ?>
      </div>
      <div class="city" style="text-align:right">
        <div class="time"><?= date('H:i', strtotime($booking['arrive_at'])) ?></div>
        <div class="name"><?= htmlspecialchars($booking['destination']) ?></div>
        <div class="date"><?= date('d F Y', strtotime($booking['arrive_at'])) ?></div>
      </div>
    </div>

    <div class="eticket-details">
      <div class="detail-item">
        <div class="label">Kendaraan</div>
        <div class="value"><?= htmlspecialchars($booking['vehicle_name']) ?></div>
        <div style="font-size:.72rem;color:#94A3B8;margin-top:2px"><?= htmlspecialchars($booking['plate_number'] ?? '') ?></div>
      </div>
      <div class="detail-item">
        <div class="label">Penumpang</div>
        <div class="value"><?= $booking['passenger_count'] ?> orang</div>
      </div>
      <div class="detail-item">
        <div class="label">Kontak</div>
        <div class="value" style="font-size:.82rem"><?= htmlspecialchars($booking['contact_name']) ?></div>
        <div style="font-size:.72rem;color:#94A3B8"><?= htmlspecialchars($booking['contact_phone']) ?></div>
      </div>
    </div>

    <?php if (!empty($seats)): ?>
    <div class="eticket-seats">
      <h3>Data Penumpang</h3>
      <?php foreach ($seats as $s): ?>
      <div class="passenger-row">
        <div class="seat-num"><?= $s['seat_number'] ?></div>
        <div>
          <div class="passenger-name"><?= htmlspecialchars($s['passenger_name']) ?></div>
          <?php if (!empty($s['passenger_id_no'])): ?>
            <div class="passenger-id">ID: <?= htmlspecialchars($s['passenger_id_no']) ?></div>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($booking['notes'])): ?>
    <div style="padding:14px 28px;background:#FFF3CD;font-size:.82rem;color:#664d03">
      <i class="fa-solid fa-note-sticky"></i> <strong>Catatan:</strong> <?= htmlspecialchars($booking['notes']) ?>
    </div>
    <?php endif; ?>

    <div class="cutline"></div>

    <div class="eticket-footer">
      <div>
        <div class="total-label">Total Pembayaran</div>
        <div class="total-amount">Rp <?= number_format($booking['total_price'], 0, ',', '.') ?></div>
      </div>
      <?php if ($payment): ?>
      <div style="text-align:right;font-size:.78rem;color:#94A3B8">
        <?= ucfirst($payment['gateway'] ?? 'midtrans') ?><br>
        <?php if ($payment['paid_at']): ?>Dibayar: <?= date('d/m/Y H:i', strtotime($payment['paid_at'])) ?><?php endif; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <div style="font-size:.75rem;color:#94A3B8;text-align:center;padding:10px 0">
    Tunjukkan e-tiket ini kepada petugas saat keberangkatan.
    Selamat perjalanan! 🚌
  </div>
</div>
</body>
</html>
