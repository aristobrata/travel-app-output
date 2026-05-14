<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Penumpang — TravelKu</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Plus Jakarta Sans',sans-serif;background:#F8FAFC;color:#1E293B}
nav{background:#0F1B2D;padding:0 24px;height:56px;display:flex;align-items:center;justify-content:space-between}
.page-wrap{max-width:820px;margin:0 auto;padding:28px 20px}
.back-link{color:#64748B;font-size:.875rem;text-decoration:none;display:inline-flex;align-items:center;gap:5px;margin-bottom:20px}
.layout{display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start}
.card{background:#fff;border-radius:16px;border:1px solid #E2E8F0;padding:22px;margin-bottom:16px}
.card h2{font-size:1rem;font-weight:700;color:#0F1B2D;margin-bottom:18px;display:flex;align-items:center;gap:7px}
.form-group{margin-bottom:14px}
label{display:block;font-size:.8rem;font-weight:600;color:#475569;margin-bottom:5px}
.required{color:#EF4444;margin-left:2px}
input,select,textarea{display:block;width:100%;padding:9px 12px;border:1.5px solid #E2E8F0;border-radius:8px;font-size:.875rem;font-family:inherit;color:#1E293B;transition:border-color .2s}
input:focus,select:focus,textarea:focus{outline:none;border-color:#F59E0B;box-shadow:0 0 0 3px rgba(245,158,11,.1)}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.passenger-section{background:#F8FAFC;border-radius:10px;padding:16px;margin-bottom:12px;border:1px solid #E2E8F0}
.passenger-header{font-weight:700;font-size:.875rem;color:#0F1B2D;margin-bottom:12px;display:flex;align-items:center;gap:7px}
.seat-badge{background:#F59E0B;color:#0F1B2D;width:26px;height:26px;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;font-size:.78rem;font-weight:800}
.summary-card{background:#fff;border-radius:16px;border:1px solid #E2E8F0;padding:20px;position:sticky;top:20px}
.summary-title{font-size:.95rem;font-weight:700;color:#0F1B2D;margin-bottom:14px}
.summary-row{display:flex;justify-content:space-between;font-size:.82rem;color:#64748B;margin-bottom:7px}
.summary-row.total{font-weight:700;font-size:.95rem;color:#0F1B2D;border-top:1px dashed #E2E8F0;padding-top:10px;margin-top:4px}
.btn-submit{width:100%;padding:11px;background:#F59E0B;color:#0F1B2D;border:none;border-radius:10px;font-weight:700;font-size:.95rem;cursor:pointer;font-family:inherit;margin-top:14px;transition:all .2s}
.btn-submit:hover{background:#D97706;color:#fff}
.hint{font-size:.75rem;color:#94A3B8;margin-top:4px}
.alert{padding:10px 14px;border-radius:8px;font-size:.82rem;margin-bottom:14px}
.alert-error{background:#FEE2E2;color:#991b1b}
@media(max-width:680px){.layout{grid-template-columns:1fr}.summary-card{position:static}.form-row{grid-template-columns:1fr}}
</style>
</head>
<body>
<?php $base = defined('SUBFOLDER') ? SUBFOLDER : '';
$draft = $_SESSION['booking_draft'] ?? [];
$seats = $draft['seats'] ?? [];
$pricePerSeat = (int)$schedule['price'];
$total = $pricePerSeat * count($seats);
?>
<nav>
  <a href="<?= $base ?>/" style="font-family:'DM Serif Display',serif;font-size:1.2rem;color:#fff;text-decoration:none">🚌 Falles<span style="color:#F59E0B">Travel</span></a>
</nav>

<div class="page-wrap">
  <a href="javascript:history.back()" class="back-link"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
  <h1 style="font-size:1.3rem;font-weight:700;color:#0F1B2D;margin-bottom:20px">Data Penumpang & Kontak</h1>

  <?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-error">⚠️ <?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>

  <form method="POST" action="<?= $base ?>/booking/confirm">
    <?php if (!empty($_SESSION['csrf_token'])): ?>
      <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    <?php endif; ?>
    <div class="layout">
      <div>
        <!-- CONTACT -->
        <div class="card">
          <h2><i class="fa-solid fa-address-card" style="color:#F59E0B"></i> Data Kontak</h2>
          <div class="form-row">
            <div class="form-group">
              <label>Nama Lengkap <span class="required">*</span></label>
              <input type="text" name="contact_name" placeholder="Nama pemesan"
                value="<?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>" required>
            </div>
            <div class="form-group">
              <label>No. Telepon <span class="required">*</span></label>
              <input type="tel" name="contact_phone" placeholder="08xxxxxxxxxx" required>
            </div>
          </div>
          <div class="form-group">
            <label>Email <span class="required">*</span></label>
            <input type="email" name="contact_email" placeholder="email@contoh.com" required>
          </div>
          <div class="form-group">
            <label>Catatan (opsional)</label>
            <textarea name="notes" rows="2" placeholder="Misal: bawa koper besar, perlu kursi khusus, dll"
              style="resize:vertical"></textarea>
          </div>
        </div>

        <!-- PASSENGERS -->
        <div class="card">
          <h2><i class="fa-solid fa-users" style="color:#F59E0B"></i> Data Penumpang</h2>
          <?php foreach ($seats as $i => $seatNo): ?>
          <div class="passenger-section">
            <div class="passenger-header">
              <span class="seat-badge"><?= $seatNo ?></span>
              Penumpang Kursi <?= $seatNo ?>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Nama Penumpang <span class="required">*</span></label>
                <input type="text" name="passenger_name_<?= $i ?>" placeholder="Nama lengkap" required>
              </div>
              <div class="form-group">
                <label>No. KTP/Identitas</label>
                <input type="text" name="passenger_id_<?= $i ?>" placeholder="Opsional">
                <div class="hint">NIK, SIM, atau paspor</div>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- SUMMARY -->
      <div>
        <div class="summary-card">
          <div class="summary-title">Ringkasan Pemesanan</div>
          <div style="font-size:.82rem;color:#0F1B2D;font-weight:600;margin-bottom:4px">
            <?= htmlspecialchars($schedule['origin']) ?> → <?= htmlspecialchars($schedule['destination']) ?>
          </div>
          <div style="font-size:.8rem;color:#64748B;margin-bottom:14px">
            <?= date('d F Y', strtotime($schedule['depart_at'])) ?>
            · <?= date('H:i', strtotime($schedule['depart_at'])) ?> WIB
          </div>
          <div class="summary-row">
            <span>Kendaraan</span>
            <span><?= htmlspecialchars($schedule['vehicle_name']) ?></span>
          </div>
          <div class="summary-row">
            <span>Kursi dipilih</span>
            <span>
              <?php foreach ($seats as $n): ?>
                <span style="background:#FEF3C7;color:#92400e;padding:1px 6px;border-radius:4px;font-size:.75rem;font-weight:600;margin-left:3px"><?= $n ?></span>
              <?php endforeach; ?>
            </span>
          </div>
          <div class="summary-row">
            <span>Harga/kursi</span>
            <span>Rp <?= number_format($pricePerSeat, 0, ',', '.') ?></span>
          </div>
          <div class="summary-row">
            <span>Jumlah penumpang</span>
            <span><?= count($seats) ?> orang</span>
          </div>
          <div class="summary-row total">
            <span>Total Pembayaran</span>
            <span style="color:#F59E0B">Rp <?= number_format($total, 0, ',', '.') ?></span>
          </div>

          <button type="submit" class="btn-submit">
            <i class="fa-solid fa-lock"></i> Lanjut ke Pembayaran
          </button>
          <div style="font-size:.72rem;color:#94A3B8;text-align:center;margin-top:10px">
            <i class="fa-solid fa-shield-check" style="color:#10B981"></i>
            Data Anda aman & terenkripsi
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
</body>
</html>
