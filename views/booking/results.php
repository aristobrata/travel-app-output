<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Hasil Pencarian — TravelKu</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="<?= (defined('SUBFOLDER') ? SUBFOLDER : '') ?>/assets/css/app.css">
<style>
body{background:#F8FAFC;font-family:'Plus Jakarta Sans',sans-serif}
.topbar-search{background:#0F1B2D;padding:14px 24px}
.search-inline{display:flex;gap:10px;align-items:flex-end;max-width:800px;margin:0 auto;flex-wrap:wrap}
.search-inline .fg{display:flex;flex-direction:column;gap:4px;flex:1;min-width:140px}
.search-inline label{font-size:.72rem;font-weight:600;color:rgba(255,255,255,.5);text-transform:uppercase;letter-spacing:.05em}
.search-inline input{padding:8px 12px;border:1.5px solid rgba(255,255,255,.2);border-radius:8px;background:rgba(255,255,255,.08);color:#fff;font-size:.875rem;font-family:inherit}
.search-inline input::placeholder{color:rgba(255,255,255,.35)}
.search-inline input:focus{outline:none;border-color:#F59E0B}
.search-inline button{padding:8px 20px;background:#F59E0B;color:#0F1B2D;border:none;border-radius:8px;font-weight:700;cursor:pointer;font-family:inherit;white-space:nowrap}
.page-wrap{max-width:900px;margin:0 auto;padding:28px 20px}
.result-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:10px}
.result-header h1{font-size:1.2rem;font-weight:700;color:#0F1B2D}
.result-header .meta{font-size:.82rem;color:#64748B}
.schedule-card{background:#fff;border-radius:16px;border:1.5px solid #E2E8F0;padding:22px 24px;margin-bottom:14px;display:flex;align-items:center;justify-content:space-between;gap:16px;transition:all .2s;flex-wrap:wrap}
.schedule-card:hover{border-color:#F59E0B;box-shadow:0 6px 24px rgba(245,158,11,.12)}
.sch-route{flex:1;min-width:200px}
.sch-times{display:flex;align-items:center;gap:12px;margin-bottom:6px}
.sch-time{font-size:1.3rem;font-weight:700;color:#0F1B2D}
.sch-arrow{color:#F59E0B;font-size:.85rem}
.sch-duration{font-size:.78rem;color:#94A3B8;background:#F8FAFC;padding:2px 8px;border-radius:10px}
.sch-cities{font-size:.85rem;color:#64748B;display:flex;align-items:center;gap:6px}
.sch-vehicle{min-width:160px}
.sch-vehicle-name{font-weight:600;color:#1E293B;font-size:.9rem}
.sch-vehicle-type{font-size:.75rem;color:#64748B;margin-top:2px}
.sch-fac{display:flex;gap:4px;margin-top:6px;flex-wrap:wrap}
.fac-tag{background:#F1F5F9;color:#475569;padding:2px 7px;border-radius:5px;font-size:.68rem;font-weight:600}
.sch-seats{text-align:center;min-width:80px}
.seats-num{font-size:1.4rem;font-weight:700}
.seats-num.ok{color:#10B981}
.seats-num.low{color:#F59E0B}
.seats-num.full{color:#EF4444}
.seats-label{font-size:.72rem;color:#94A3B8}
.sch-price{text-align:right;min-width:140px}
.price-amount{font-size:1.3rem;font-weight:700;color:#0F1B2D}
.price-per{font-size:.72rem;color:#94A3B8}
.btn-book{margin-top:8px;padding:9px 20px;background:#F59E0B;color:#0F1B2D;border:none;border-radius:8px;font-weight:700;cursor:pointer;font-family:inherit;width:100%;font-size:.875rem;transition:all .2s;text-decoration:none;display:block;text-align:center}
.btn-book:hover{background:#D97706;color:#fff}
.btn-book.disabled{background:#E2E8F0;color:#94A3B8;cursor:not-allowed;pointer-events:none}
.empty-state{text-align:center;padding:64px 20px;background:#fff;border-radius:16px;border:1.5px dashed #E2E8F0}
.empty-state .icon{font-size:3rem;margin-bottom:12px;color:#CBD5E1}
.empty-state h2{font-size:1.1rem;font-weight:700;color:#475569;margin-bottom:8px}
.empty-state p{color:#94A3B8;font-size:.875rem}
.back-link{color:#64748B;font-size:.875rem;text-decoration:none;display:inline-flex;align-items:center;gap:5px;margin-bottom:20px}
.back-link:hover{color:#0F1B2D}
.date-tag{background:#FEF3C7;color:#92400e;padding:3px 10px;border-radius:6px;font-size:.75rem;font-weight:600}
</style>
</head>
<body>

<!-- NAVBAR -->
<?php $base = defined('SUBFOLDER') ? SUBFOLDER : ''; ?>
<nav style="background:#0F1B2D;padding:0 24px;height:56px;display:flex;align-items:center;justify-content:space-between">
  <a href="<?= $base ?>/" style="font-family:'DM Serif Display',serif;font-size:1.2rem;color:#fff;text-decoration:none">🚌 Falles<span style="color:#F59E0B">Travel</span></a>
  <div style="display:flex;align-items:center;gap:10px">
    <?php if (!empty($_SESSION['user_id'])): ?>
      <a href="<?= $base ?>/my-bookings" style="color:rgba(255,255,255,.7);font-size:.82rem;text-decoration:none">📋 Pesanan</a>
      <a href="<?= $base ?>/logout" style="background:#F59E0B;color:#0F1B2D;padding:6px 14px;border-radius:6px;font-size:.82rem;font-weight:700;text-decoration:none"
        onclick="return confirm('Yakin keluar?')">Keluar</a>
    <?php else: ?>
      <a href="<?= $base ?>/login" style="color:rgba(255,255,255,.7);font-size:.82rem;text-decoration:none">Masuk</a>
      <a href="<?= $base ?>/register" style="background:#F59E0B;color:#0F1B2D;padding:6px 14px;border-radius:6px;font-size:.82rem;font-weight:700;text-decoration:none">Daftar</a>
    <?php endif; ?>
  </div>
</nav>

<!-- SEARCH BAR -->
<div class="topbar-search">
  <form method="GET" action="<?= $base ?>/search" class="search-inline">
    <div class="fg">
      <label>Dari</label>
      <input type="text" name="origin" value="<?= htmlspecialchars($origin) ?>" placeholder="Kota asal" required>
    </div>
    <div class="fg">
      <label>Ke</label>
      <input type="text" name="destination" value="<?= htmlspecialchars($destination) ?>" placeholder="Kota tujuan" required>
    </div>
    <div class="fg" style="max-width:170px">
      <label>Tanggal</label>
      <input type="date" name="date" value="<?= htmlspecialchars($date) ?>" min="<?= date('Y-m-d') ?>" required>
    </div>
    <button type="submit"><i class="fa-solid fa-search"></i> Cari</button>
  </form>
</div>

<div class="page-wrap">
  <a href="<?= $base ?>/" class="back-link"><i class="fa-solid fa-arrow-left"></i> Kembali</a>

  <div class="result-header">
    <div>
      <h1>
        <?= htmlspecialchars($origin) ?>
        <i class="fa-solid fa-arrow-right" style="color:#F59E0B;font-size:.85rem;margin:0 4px"></i>
        <?= htmlspecialchars($destination) ?>
      </h1>
      <div class="meta">
        <span class="date-tag"><i class="fa-regular fa-calendar"></i> <?= date('l, d F Y', strtotime($date)) ?></span>
      </div>
    </div>
    <div style="font-size:.85rem;color:#64748B">
      <strong><?= count($schedules) ?></strong> jadwal tersedia
    </div>
  </div>

  <?php if (empty($schedules)): ?>
    <div class="empty-state">
      <div class="icon"><i class="fa-solid fa-bus-simple"></i></div>
      <h2>Tidak Ada Jadwal</h2>
      <p>Maaf, tidak ada jadwal perjalanan untuk rute dan tanggal yang dipilih.<br>Coba tanggal lain atau rute berbeda.</p>
      <a href="<?= $base ?>/" style="display:inline-block;margin-top:18px;padding:10px 22px;background:#F59E0B;color:#0F1B2D;border-radius:8px;font-weight:700;text-decoration:none">
        Ubah Pencarian
      </a>
    </div>
  <?php else: ?>
    <?php foreach ($schedules as $s):
      $fac = is_string($s['facilities']) ? (json_decode($s['facilities'], true) ?? []) : ($s['facilities'] ?? []);
      $seats = (int)$s['available_seats'];
      $seatsClass = $seats === 0 ? 'full' : ($seats <= 3 ? 'low' : 'ok');
      $dur = '';
      if ($s['duration_min'] > 0) {
        $h = intdiv($s['duration_min'], 60); $m = $s['duration_min'] % 60;
        $dur = ($h > 0 ? "{$h}j " : '') . ($m > 0 ? "{$m}m" : '');
      }
    ?>
    <div class="schedule-card">
      <div class="sch-route">
        <div class="sch-times">
          <span class="sch-time"><?= date('H:i', strtotime($s['depart_at'])) ?></span>
          <span class="sch-arrow"><i class="fa-solid fa-arrow-right"></i></span>
          <span class="sch-time"><?= date('H:i', strtotime($s['arrive_at'])) ?></span>
          <?php if ($dur): ?><span class="sch-duration"><?= $dur ?></span><?php endif; ?>
        </div>
        <div class="sch-cities">
          <span><?= htmlspecialchars($s['origin']) ?></span>
          <i class="fa-solid fa-arrow-right" style="font-size:.6rem;color:#CBD5E1"></i>
          <span><?= htmlspecialchars($s['destination']) ?></span>
        </div>
      </div>

      <div class="sch-vehicle">
        <div class="sch-vehicle-name"><i class="fa-solid fa-bus" style="color:#F59E0B;margin-right:5px"></i><?= htmlspecialchars($s['vehicle_name']) ?></div>
        <div class="sch-vehicle-type"><?= ucfirst($s['vehicle_type'] ?? '') ?> · <?= $s['capacity'] ?> kursi</div>
        <div class="sch-fac">
          <?php if (!empty($fac['ac'])): ?><span class="fac-tag">❄ AC</span><?php endif; ?>
          <?php if (!empty($fac['wifi'])): ?><span class="fac-tag">📶 WiFi</span><?php endif; ?>
          <?php if (!empty($fac['usb'])): ?><span class="fac-tag">🔌 USB</span><?php endif; ?>
          <?php if (!empty($fac['tv'])): ?><span class="fac-tag">📺 TV</span><?php endif; ?>
        </div>
      </div>

      <div class="sch-seats">
        <div class="seats-num <?= $seatsClass ?>"><?= $seats ?></div>
        <div class="seats-label">kursi tersisa</div>
      </div>

      <div class="sch-price">
        <div class="price-amount">Rp <?= number_format($s['price'], 0, ',', '.') ?></div>
        <div class="price-per">per orang</div>
        <?php if ($seats > 0): ?>
          <a href="<?= $base ?>/seat/<?= $s['id'] ?>" class="btn-book">
            Pilih Kursi <i class="fa-solid fa-arrow-right"></i>
          </a>
        <?php else: ?>
          <span class="btn-book disabled">Kursi Penuh</span>
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
